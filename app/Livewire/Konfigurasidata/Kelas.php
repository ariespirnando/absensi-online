<?php

namespace App\Livewire\Konfigurasidata;

use Livewire\Component;

class Kelas extends Component
{
    public $group;
    public $nama;
    public $tahun_ajars_id;
    public $gurus_id;

    public function render()
    {
        return view('livewire.konfigurasidata.kelas');
    }
}
