<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataSiswaController extends Controller
{
    public $menu = 'data';
    public $cmenu ='dsiswa';
    public function index(){
        return view('contents.data.siswa.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
