<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public $menu = 'konfigurasi';
    public $cmenu ='general';
    public function index(){
        return view('contents.konfigurasi-data.general.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
