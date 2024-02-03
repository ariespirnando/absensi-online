<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KonfigurasiSiswaController extends Controller
{
    public $menu = 'konfigurasi';
    public $cmenu ='kta';
    public function index($id){
        return view('contents.konfigurasi-data.siswa.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }
}
