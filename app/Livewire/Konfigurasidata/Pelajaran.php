<?php

namespace App\Livewire\Konfigurasidata;

use App\Models\Guru;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Konfigurasi_pelajaran;
use Illuminate\Database\QueryException;
use App\Models\Pelajaran as ModelsPelajaran;

class Pelajaran extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';
    public $kelas_id;
    public $pelajarans_id;
    public $gurus_id;
    public $id;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;

    public $kelasDesc;

    public $pelajaranIdSelected = false;
    public $searchPelajaranData;
    public $pelajaranDesc;

    public $gurusIdSelected = false;
    public $searchGurusData;
    public $gurusDesc;

    public $kelas_taid;

    public function mount($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function setGurusId($id){
        $this->gurus_id  = $id;
        $gurus = Guru::findOrFail($id);
        if ($gurus) {
            $this->gurusDesc = "[".$gurus->nip."] ".$gurus->nama;
            $this->gurusIdSelected = true;
        }
    }

    public function clearGurusId(){
        $this->gurusDesc = "";
        $this->gurus_id  ="";
        $this->gurusIdSelected=false;
        $this->searchGurusData ="";
    }

    public function setPelajaranId($id){
        $this->pelajarans_id  = $id;
        $pelajaran = ModelsPelajaran::findOrFail($id);
        if ($pelajaran) {
            $this->pelajaranDesc = $pelajaran->nama;
            $this->pelajaranIdSelected = true;
        }
    }

    public function clearPelajaranId(){
        $this->pelajaranDesc = "";
        $this->pelajarans_id  ="";
        $this->pelajaranIdSelected=false;
        $this->searchPelajaranData ="";
    }

    public function getKelasDesc(){
        if($this->kelasDesc==""){
            $kelas = Kelas::join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
            ->select('kelas.*', 'tahun_ajars.tahun', 'tahun_ajars.semester')
            ->where('kelas.status', 'A')
            ->where('kelas.id', $this->kelas_id)
            ->where('tahun_ajars.status', 'A')
            ->first();
            $this->kelasDesc = $kelas->group."/".$kelas->nama." TA ".$kelas->tahun."/".$kelas->semester;
            $this->kelas_taid = $kelas->tahun_ajars_id;
        }
    }

    public function render()
    {
        if (!is_numeric($this->totalPagging)) {
            $this->totalPagging = 10;
        }
        if($this->searchData!=""){
            $dataKonfPelajaran = Konfigurasi_pelajaran::join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
                ->leftjoin('gurus', 'konfigurasi_pelajarans.gurus_id', '=', 'gurus.id')
                ->select('konfigurasi_pelajarans.*', 'pelajarans.nama', 'gurus.nama as gurusnama', 'gurus.nip')
                ->where(
                function ($query) {
                    $query->where('pelajarans.nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('gurus.nis', 'like', '%'.$this->searchData.'%')
                    ->orWhere('gurus.nama', 'like', '%'.$this->searchData.'%');
                })
                ->where('pelajarans.status', 'A')
                ->where('konfigurasi_pelajarans.status', 'A')
                ->where('konfigurasi_pelajarans.kelas_id', $this->kelas_id)
                ->where('gurus.status', 'A')
                ->orderBy('pelajarans.nama','asc')
                ->paginate($this->totalPagging);
        }else{
            $dataKonfPelajaran = Konfigurasi_pelajaran::join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
            ->leftjoin('gurus', 'konfigurasi_pelajarans.gurus_id', '=', 'gurus.id')
            ->select('konfigurasi_pelajarans.*', 'pelajarans.nama', 'gurus.nama as gurusnama', 'gurus.nip')
            ->where('pelajarans.status', 'A')
            ->where('konfigurasi_pelajarans.status', 'A')
            ->where('konfigurasi_pelajarans.kelas_id', $this->kelas_id)
            ->where('gurus.status', 'A')
            ->orderBy('pelajarans.nama','asc')
            ->paginate($this->totalPagging);
        }
        $this->getKelasDesc();

        $excludePelajarans = Konfigurasi_pelajaran::join('pelajarans', 'konfigurasi_pelajarans.pelajarans_id', '=', 'pelajarans.id')
        ->leftjoin('gurus', 'konfigurasi_pelajarans.gurus_id', '=', 'gurus.id')
        ->select('konfigurasi_pelajarans.pelajarans_id')
        ->where('pelajarans.status', 'A')
        ->where('konfigurasi_pelajarans.status', 'A')
        ->where('konfigurasi_pelajarans.kelas_id', $this->kelas_id)
        ->where('gurus.status', 'A')
        ->orderBy('pelajarans.nama','asc')->get();

        if($this->searchPelajaranData!=""){
            $dataPelajaran = ModelsPelajaran::whereNotIn('id', $excludePelajarans)
                ->where('status', 'A')
                ->where('nama', 'like', '%'.$this->searchPelajaranData.'%')
                ->orderBy('nama','asc')
                ->paginate(5);
        }else{
            $dataPelajaran = ModelsPelajaran::where('status', 'XXXXX')
                ->orderBy('nama','asc')
                ->paginate(5);
        }

        if($this->searchGurusData!=""){
            $dataGurus = Guru::where(
                function ($query) {
                    $query->where('nip', 'like', '%'.$this->searchGurusData.'%')
                    ->orWhere('nama', 'like', '%'.$this->searchGurusData.'%');
                })
                ->where('status', 'A')
                ->orderBy('nama','asc')
                ->paginate(5);
        }else{
            $dataGurus = Guru::where('status', 'XXXXX')
                ->orderBy('nama','asc')
                ->paginate(5);
        }

        return view('livewire.konfigurasidata.pelajaran',
            ['dataKonfPelajaran'=>$dataKonfPelajaran,
            'kelasDesc'=>$this->kelasDesc,
            'ta_id'=>$this->kelas_taid,
            'dataPelajaran'=>$dataPelajaran,
            'dataGurus'=>$dataGurus]);

    }

    public function store(){
        $roles = [
            'pelajarans_id' => 'required',
        ];

        $message = [
            'pelajarans_id.required' => 'Pelajaran wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            Konfigurasi_pelajaran::create([
                'kelas_id' => $this->kelas_id,
                'pelajarans_id' => $this->pelajarans_id,
                'gurus_id' => $this->gurus_id
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Pelajaran berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Pelajaran gagal disimpan'. $e->getMessage());
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Pelajaran gagal disimpan'. $e->getMessage());
        }
        $this->setClearModel();
    }

    public function setAdd(){
        $this->setClearModel();
        $this->clearGurusId();
        $this->clearPelajaranId();
    }

    public function setDeleted($id){
        $this->setClearModel();
        $this->clearGurusId();
        $this->clearPelajaranId();
        $this->id = $id;
    }

    public function setEdited($id){
        $this->setClearModel();
        $this->clearGurusId();
        $this->clearPelajaranId();
        $this->id = $id;
        $konfPelajaran = Konfigurasi_pelajaran::findOrFail($this->id);
        if ($konfPelajaran) {
            $this->pelajarans_id = $konfPelajaran->pelajarans_id;
            if($this->pelajarans_id!=""){
                $this->setPelajaranId($this->pelajarans_id);
            }
            $this->gurus_id = $konfPelajaran->gurus_id;
            if($this->gurus_id!=""){
                $this->setGurusId($this->gurus_id);
            }
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $konfPelajaran = Konfigurasi_pelajaran::findOrFail($this->id);
        if ($konfPelajaran) {
            $this->pelajarans_id = $konfPelajaran->pelajarans_id;
            $this->gurus_id = $konfPelajaran->gurus_id;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->pelajarans_id = '';
        $this->gurus_id = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'pelajarans_id' => 'required',
        ];

        $message = [
            'pelajarans_id.required' => 'Pelajaran wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $konfPelajaran = Konfigurasi_pelajaran::findOrFail($this->id);
            if (!$konfPelajaran) {
                session()->flash('error', 'Pelajaran tidak ditemukan');
            }else{
                $konfPelajaran->pelajarans_id = $this->pelajarans_id;
                $konfPelajaran->gurus_id = $this->gurus_id;
                $konfPelajaran->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Pelajaran berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Pelajaran gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Pelajaran gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $konfPelajaran = Konfigurasi_pelajaran::findOrFail($this->id);
            if (!$konfPelajaran) {
                session()->flash('error', 'Pelajaran tidak ditemukan');
            }else{
                $konfPelajaran->status = 'N';
                $konfPelajaran->save();
                session()->flash('message', 'Pelajaran berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Pelajaran gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Pelajaran gagal dihapus');
        }
        $this->setClearModel();
    }
}
