<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataPelajaranController extends Controller
{
    public $menu = 'data';
    public $cmenu ='dpelajaran';
    public function index(){
        return view('contents.data.pelajaran.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
