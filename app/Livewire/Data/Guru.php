<?php

namespace App\Livewire\Data;

use Livewire\Component;

class Guru extends Component
{
    public $nip;
    public $nama;
    public $jenis_kelamin;
    public $pendidikan;
    public $alamat;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $telepon;
    public $tanggal_bergabung;

    public function render()
    {
        return view('livewire.data.guru');
    }
}
