<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapitulasiKelasController extends Controller
{
    public $menu = 'rekapitulasi';
    public $cmenu ='rkelas';
    public function index(){
        return view('contents.rekapitulasi.kelas.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
