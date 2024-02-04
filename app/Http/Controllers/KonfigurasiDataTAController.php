<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KonfigurasiDataTAController extends Controller
{
    public $menu = 'konfigurasi';
    public $cmenu ='kta';
    public function index(){
        return view('contents.konfigurasi-data.tahun-ajar.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }

    public function kelas($id){
        return view('contents.konfigurasi-data.kelas.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }

    public function pelajaran($id){
        return view('contents.konfigurasi-data.pelajaran.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }

    public function siswa($id){
        return view('contents.konfigurasi-data.siswa.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }

}
