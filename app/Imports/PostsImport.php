<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PostsImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public int $missingImages = 0;
    public array $errors = [];

    private array $authorCache = [];
    private array $categoryCache = [];

    public function collection(Collection $rows): void
    {
        Model::unguard();

        try {
            foreach ($rows as $index => $row) {
                $line = $index + 2; // +1 header, +1 karena index mulai dari 0

                if (blank($row['title']) || blank($row['slug']) || blank($row['body'])) {
                    $this->skipped++;
                    $this->errors[] = "Baris {$line}: title/slug/body kosong";
                    continue;
                }

                try {
                    DB::transaction(fn () => $this->importRow($row));
                } catch (\Throwable $e) {
                    $this->skipped++;
                    $this->errors[] = "Baris {$line}: " . $e->getMessage();
                }
            }
        } finally {
            Model::reguard();
        }
    }

    private function importRow($row): void
    {
        $author = $this->resolveAuthor($row['author'] ?? null);

        // withTrashed() agar slug milik post yang sudah di-soft-delete tidak bentrok
        $post = Post::withTrashed()->updateOrCreate(
            ['slug' => trim($row['slug'])],
            [
                'title'        => trim($row['title']),
                'body'         => $row['body'],
                'image'        => $this->resolveImage($row['image'] ?? null),
                'published_at' => $this->parseDate($row['published_at'] ?? null),
                'featured'     => filter_var($row['featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'user_id'      => $author->id,
            ]
        );

        $post->wasRecentlyCreated ? $this->created++ : $this->updated++;

        $categoryIds = collect(explode(',', (string) ($row['categories'] ?? '')))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->map(fn ($name) => $this->resolveCategory($name)->id)
            ->all();

        $post->categories()->sync($categoryIds);
    }

    private function resolveAuthor(?string $name): User
    {
        $name = trim((string) $name) ?: 'Redaksi Pelajar Kudus';

        return $this->authorCache[$name] ??= User::firstOrCreate(
            ['name' => $name],
            [
                'email'    => Str::slug($name) . '@pelajarkudus.local',
                'password' => Hash::make(Str::random(32)),
            ]
        );
    }

    private function resolveCategory(string $title): Category
    {
        return $this->categoryCache[$title] ??= Category::firstOrCreate(
            ['title' => $title],
            ['slug' => Str::slug($title)]
        );
    }

    private function resolveImage(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        // Sesuai FileUpload::make('image')->directory('posts/thumbnails')
        $path = 'posts/thumbnails/' . basename($path);
        $disk = config('filament.default_filesystem_disk', 'public');

        if (! Storage::disk($disk)->exists($path)) {
            $this->missingImages++;
            return null;
        }

        return $path;
    }

    private function parseDate($value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        if (is_numeric($value)) { // Excel kadang menyimpan tanggal sebagai angka serial
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
        }

        return Carbon::parse($value);
    }
}