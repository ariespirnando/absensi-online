<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public $menu = 'point';
    public $cmenu ='konfpoint';
    public function index(){
        return view('contents.point.konfigurasi-point.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
