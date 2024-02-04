<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public $menu = 'data';

    public function guru(){
        return view('contents.data.guru.index',['menu'=> $this->menu, 'cmenu'=>'dguru']);
    }

    public function siswa(){
        return view('contents.data.siswa.index',['menu'=> $this->menu, 'cmenu'=>'dsiswa']);
    }

    public function pelajaran(){
        return view('contents.data.pelajaran.index',['menu'=> $this->menu, 'cmenu'=>'dpelajaran']);
    }

}
