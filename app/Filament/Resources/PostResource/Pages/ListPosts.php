<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Imports\PostsImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel / CSV')
                        ->disk('local')
                        ->directory('imports')
                        ->maxSize(51200) // 50 MB
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    set_time_limit(0);
                    ini_set('memory_limit', '1G');

                    $import = new PostsImport();
                    Excel::import($import, $data['file'], 'local');
                    Storage::disk('local')->delete($data['file']);

                    $body = "Baru: {$import->created}, diperbarui: {$import->updated}, dilewati: {$import->skipped}";
                    if ($import->missingImages) {
                        $body .= ", gambar tidak ditemukan: {$import->missingImages}";
                    }
                    if ($import->errors) {
                        $body .= "\n" . implode("\n", array_slice($import->errors, 0, 5));
                    }

                    Notification::make()
                        ->title('Import selesai')
                        ->body($body)
                        ->{$import->errors ? 'warning' : 'success'}()
                        ->persistent()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}