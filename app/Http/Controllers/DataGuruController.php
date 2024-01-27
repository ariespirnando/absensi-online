<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataGuruController extends Controller
{
    public $menu = 'data';
    public $cmenu ='dguru';
    public function index(){
        return view('contents.data.guru.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
