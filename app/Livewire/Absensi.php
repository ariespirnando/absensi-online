<?php

namespace App\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Absensi extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $userId;
    public $gurusId;
    public $siswasId;
    public $userLevel;
    public $userGroup;

    public $totalPagging = 10;
    public $detailMode = false;
    public $searchData;

    public function render()
    {
        if(!Auth::check()){
            return redirect('/');
        }

        $user = Auth::user();

        $this->userId = $user->id;
        $this->gurusId = $user->gurus_id;
        $this->siswasId = $user->siswas_id;
        $this->userLevel = $user->level;
        $this->userGroup = $user->group_level;

        if($this->userGroup=="ADMIN" && ($this->userLevel=="ADMIN" || $this->userLevel=="BK")){
            if($this->searchData!=""){
            }else{
                $dataKelas = Kelas::join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
                ->join('konfigurasi_pelajarans', 'kelas.id', '=', 'konfigurasi_pelajarans.kelas_id')
                ->join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
                ->select('kelas.group',
                        'kelas.nama',
                        'kelas.gurus_id as walikelasid',
                        'tahun_ajars.tahun',
                        'tahun_ajars.semester',
                        'konfigurasi_pelajarans.gurus_id as pengampupelajaranid',
                        'pelajarans.nama as pelajaransnames')
                ->where('kelas.status', 'A')
                ->where('tahun_ajars.status', 'A')
                ->where('konfigurasi_pelajarans.status', 'A')
                ->where('pelajarans.status', 'A')
                ->orderBy('kelas.group','asc')
                ->paginate($this->totalPagging);
            }
        }elseif($this->userGroup=="ADMIN" && $this->userLevel=="GURU"){
            if($this->searchData!=""){
            }else{
                $dataKelas = Kelas::join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
                    ->join('konfigurasi_pelajarans', 'kelas.id', '=', 'konfigurasi_pelajarans.kelas_id')
                    ->join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
                    ->select('kelas.group',
                            'kelas.nama',
                            'kelas.gurus_id as walikelasid',
                            'tahun_ajars.tahun',
                            'tahun_ajars.semester',
                            'konfigurasi_pelajarans.gurus_id as pengampupelajaranid',
                            'pelajarans.nama as pelajaransnames')
                    ->where('kelas.status', 'A')
                    ->where('tahun_ajars.status', 'A')
                    ->where('konfigurasi_pelajarans.status', 'A')
                    ->where('pelajarans.status', 'A')
                    ->where(
                        function ($query) {
                            $query->where('konfigurasi_pelajarans.gurus_id', '=', $this->gurusId)
                            ->orWhere('kelas.gurus_id', '=', $this->gurusId);
                        })
                    ->orderBy('kelas.group','asc')
                    ->paginate($this->totalPagging);
            }
        }
        return view('livewire.absensi',['dataSiswa'=>$dataKelas]);
    }
}
