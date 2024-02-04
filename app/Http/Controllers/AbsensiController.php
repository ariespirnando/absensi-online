<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public $menu ='absensi';
    public $cmenu ='absensi';
    public function index(){
        return view('contents.absensi.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu]);
    }
    public function details($id){
        return view('contents.absensi.details.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }

    public function detailssiswa($id){
        return view('contents.absensi.details.siswa.index',['menu'=> $this->menu, 'cmenu'=>$this->cmenu, 'id'=>decrypt($id)]);
    }
}
