<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public $menu ='absensi';
    public $cmenu ='';
    public function index(){
        return view('contents.absensi.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
