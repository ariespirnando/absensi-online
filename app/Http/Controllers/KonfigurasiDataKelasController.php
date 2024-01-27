<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KonfigurasiDataKelasController extends Controller
{
    public $menu = 'konfigurasi';
    public $cmenu ='kkelas';
    public function index(){
        return view('contents.konfigurasi-data.kelas.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
