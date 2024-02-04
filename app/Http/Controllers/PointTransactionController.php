<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    public $menu = 'point';
    public $cmenu ='point';
    public function index(){
        return view('contents.point.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
