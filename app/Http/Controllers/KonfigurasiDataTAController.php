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
}
