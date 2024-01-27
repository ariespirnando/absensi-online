<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapitulasiSiswaController extends Controller
{
    public $menu = 'rekapitulasi';
    public $cmenu ='rsiswa';
    public function index(){
        return view('contents.rekapitulasi.siswa.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
