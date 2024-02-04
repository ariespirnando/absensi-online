<?php

namespace App\Livewire;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Absensi;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Konfigurasi_pelajaran;
use Illuminate\Database\QueryException;

class AbsensiDetails extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';
    public $konfigurasi_pelajarans_id;
    public $gurus_id;
    public $users_id;
    public $keterangan;
    public $id;
    public $resetTimeAbs;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;

    public $kelasDesc;

    public function mount($konfigurasi_pelajarans_id)
    {
        $this->konfigurasi_pelajarans_id = $konfigurasi_pelajarans_id;
    }

    public function getKelasDesc(){
        if($this->kelasDesc==""){
            $kelas = Konfigurasi_pelajaran::join('kelas', 'kelas.id', '=', 'konfigurasi_pelajarans.kelas_id')
                ->join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
                ->join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
                ->select('konfigurasi_pelajarans.id',
                        'kelas.group',
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
                ->where('konfigurasi_pelajarans.id', $this->konfigurasi_pelajarans_id)
                ->orderBy('kelas.group','asc')
                ->first();
            $this->kelasDesc = $kelas->pelajaransnames." [".$kelas->group."/".$kelas->nama." TA ".$kelas->tahun."/".$kelas->semester."]";
        }
    }

    public function render()
    {
        $user = Auth::user();

        $this->users_id = $user->id;
        $this->gurus_id = $user->gurus_id;

        if (!is_numeric($this->totalPagging)) {
            $this->totalPagging = 10;
        }
        if($this->searchData!=""){
            $dataAbsensi = Absensi::where('keterangan', 'like', '%'.$this->searchData.'%')
            ->where('status', 'A')
            ->where('konfigurasi_pelajarans_id', $this->konfigurasi_pelajarans_id)
            ->orderBy('id','desc')->paginate($this->totalPagging);
        }else{
            $dataAbsensi = Absensi::where('status', 'A')->where('konfigurasi_pelajarans_id', $this->konfigurasi_pelajarans_id)->orderBy('id','desc')->paginate($this->totalPagging);
        }

        $this->getKelasDesc();
        return view('livewire.absensi-details',['kelasDesc'=>$this->kelasDesc,'dataAbsensi'=>$dataAbsensi]);
    }

    public function store(){
        $roles = [
            'keterangan' => 'required',
        ];

        $message = [
            'keterangan.required' => 'Keterangan wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $absensis_start = Carbon::now();
            $absensis_end   = Carbon::now()->addHours(1);

            Absensi::create([
                'konfigurasi_pelajarans_id' => $this->konfigurasi_pelajarans_id,
                'keterangan' => $this->keterangan,
                'users_id' => $this->users_id,
                'gurus_id' => $this->gurus_id,
                'absensis_start' => $absensis_start,
                'absensis_end' => $absensis_end,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Data Absensi berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Absensi gagal disimpan');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Absensi gagal disimpan');
        }
        $this->setClearModel();
    }

    public function setAdd(){
        $this->setClearModel();
    }

    public function setDeleted($id){
        $this->setClearModel();
        $this->id = $id;
    }

    public function setEdited($id){
        $this->setClearModel();
        $this->id = $id;
        $absensi = Absensi::findOrFail($this->id);
        if ($absensi) {
            $this->konfigurasi_pelajarans_id = $absensi->konfigurasi_pelajarans_id;
            $this->gurus_id = $absensi->gurus_id;
            $this->users_id = $absensi->users_id;
            $this->keterangan = $absensi->keterangan;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->setClearModel();
        $this->id = $id;
        $absensi = Absensi::findOrFail($this->id);
        if ($absensi) {
            $this->konfigurasi_pelajarans_id = $absensi->konfigurasi_pelajarans_id;
            $this->gurus_id = $absensi->gurus_id;
            $this->users_id = $absensi->users_id;
            $this->keterangan = $absensi->keterangan;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->keterangan = '';
        $this->resetTimeAbs = 'T';
        $this->editMode = false;
        $this->detailMode = false;
    }
    public function update_data(){
        $roles = [
            'keterangan' => 'required',
        ];

        $message = [
            'keterangan.required' => 'Keterangan wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $absensi = Absensi::findOrFail($this->id);
            if (!$absensi) {
                session()->flash('error', 'Data Absensi tidak ditemukan');
            }else{
                $absensi->keterangan = $this->keterangan;
                $absensi->users_id = $this->users_id;
                $absensi->gurus_id = $this->gurus_id;
                if($this->resetTimeAbs=="Y"){
                    $absensis_start = Carbon::now();
                    $absensis_end   = Carbon::now()->addHours(1);

                    $absensi->absensis_start = $absensis_start;
                    $absensi->absensis_end = $absensis_end;
                }
                $absensi->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Data Absensi berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Absensi gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Absensi gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $absensi = Absensi::findOrFail($this->id);
            if (!$absensi) {
                session()->flash('error', 'Data Absensi tidak ditemukan');
            }else{
                $absensi->status = 'N';
                $absensi->save();
                session()->flash('message', 'Data Absensi berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Data Absensi gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Data Absensi gagal dihapus');
        }
        $this->setClearModel();
    }

}
