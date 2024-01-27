<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public $menu = 'dashboard';
    public $cmenu ='';
    public function index(){
        return view('contents.dashboard.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
}
