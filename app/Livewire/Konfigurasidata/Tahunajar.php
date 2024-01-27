<?php

namespace App\Livewire\Konfigurasidata;

use Livewire\Component;

class Tahunajar extends Component
{
    public $tahun;
    public $semester;
    public $keterangan;

    public function render()
    {
        return view('livewire.konfigurasidata.tahunajar');
    }
}
