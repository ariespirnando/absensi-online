<?php

namespace App\Livewire\Data;

use Livewire\Component;

class Pelajaran extends Component
{
    public $nama;
    public $keterangan;

    public function render()
    {
        return view('livewire.data.pelajaran');
    }
    public function store(){

    }

    public function remove(){

    }
}
