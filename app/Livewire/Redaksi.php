<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Redaksi - Syarat dan Ketentuan Pengiriman Naskah')]
class Redaksi extends Component
{
    public const RUBRIK = ['Opini', 'Esai', 'Cerpen', 'Artikel'];

    public string $rubrik = 'Opini';

    public string $nama = '';

    public function pilihRubrik(string $rubrik): void
    {
        if (in_array($rubrik, self::RUBRIK, true)) {
            $this->rubrik = $rubrik;
        }
    }

    #[Computed]
    public function email(): string
    {
        // Atur di config/app.php: 'redaksi_email' => env('REDAKSI_EMAIL', '...')
        return config('app.redaksi_email', 'redaksi@pelajarkudus.com');
    }

    #[Computed]
    public function subjek(): string
    {
        $nama = trim($this->nama) !== '' ? trim($this->nama) : 'Nama Penulis';

        return "{$this->rubrik} – {$nama}";
    }

    #[Computed]
    public function mailto(): string
    {
        return 'mailto:' . $this->email . '?subject=' . rawurlencode($this->subjek);
    }

    public function render()
    {
        return view('livewire.redaksi');
    }
}