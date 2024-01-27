<?php

namespace App\Livewire\Data;

use Livewire\Component;

class Siswa extends Component
{
    public $nis;
    public $nama;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $nama_orang_tua;
    public $telepon_orang_tua;
    public $tanggal_masuk;

    public function render()
    {
        return view('livewire.data.siswa');
    }
}
