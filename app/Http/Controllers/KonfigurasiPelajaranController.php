<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KonfigurasiPelajaranController extends Controller
{
    public $menu = 'konfigurasi';
    public $cmenu ='kta';
    public function index($id){
        return view('contents.konfigurasi-data.pelajaran.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }
}
