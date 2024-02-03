<?php

namespace App\Livewire\Konfigurasidata;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Konfigurasi_siswa;
use App\Models\Siswa as ModelsSiswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class Siswa extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';
    public $kelas_id;
    public $siswas_id;
    public $id;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;
    public $kelasDesc;
    public $siswaIdSelected = false;
    public $searchSiswasData;
    public $siswasDesc;
    public $kelas_taid;

    public function mount($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function setSiswasId($id){
        $this->siswas_id  = $id;
        $siswas = ModelsSiswa::findOrFail($id);
        if ($siswas) {
            $this->siswasDesc = "[".$siswas->nis."] ".$siswas->nama;
            $this->siswaIdSelected = true;
        }
    }

    public function clearGroupId(){
        $this->siswasDesc = "";
        $this->siswas_id  ="";
        $this->siswaIdSelected=false;
        $this->searchSiswasData ="";
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
            $dataKonfSiswa = Konfigurasi_siswa::join('kelas', 'konfigurasi_siswas.kelas_id', '=', 'kelas.id')
                ->join('siswas', 'konfigurasi_siswas.siswas_id', '=', 'siswas.id')
                ->select('konfigurasi_siswas.*', 'siswas.nis', 'siswas.nama')
                ->where(
                function ($query) {
                    $query->where('siswas.nis', 'like', '%'.$this->searchData.'%')
                    ->orWhere('siswas.nama', 'like', '%'.$this->searchData.'%');
                })
                ->where('kelas.status', 'A')
                ->where('konfigurasi_siswas.status', 'A')
                ->where('konfigurasi_siswas.kelas_id', $this->kelas_id)
                ->where('siswas.status', 'A')
                ->orderBy('siswas.nama','asc')
                ->paginate($this->totalPagging);
        }else{
            $dataKonfSiswa = Konfigurasi_siswa::join('kelas', 'konfigurasi_siswas.kelas_id', '=', 'kelas.id')
            ->join('siswas', 'konfigurasi_siswas.siswas_id', '=', 'siswas.id')
            ->select('konfigurasi_siswas.*', 'siswas.nis', 'siswas.nama')
            ->where('kelas.status', 'A')
            ->where('konfigurasi_siswas.status', 'A')
            ->where('konfigurasi_siswas.kelas_id', $this->kelas_id)
            ->where('siswas.status', 'A')
            ->orderBy('siswas.nama','asc')
            ->paginate($this->totalPagging);
        }
        $this->getKelasDesc();

        $excludeSiswa = Konfigurasi_siswa::join('kelas', 'konfigurasi_siswas.kelas_id', '=', 'kelas.id')
            ->join('siswas', 'konfigurasi_siswas.siswas_id', '=', 'siswas.id')
            ->select('siswas_id')
            ->where('kelas.status', 'A')
            ->where('konfigurasi_siswas.status', 'A')
            ->where('konfigurasi_siswas.kelas_id', $this->kelas_id)
            ->where('siswas.status', 'A')
            ->orderBy('siswas.nama','asc')->get();

        if($this->searchSiswasData!=""){
            $dataSiswas = ModelsSiswa::where(
                function ($query) {
                    $query->where('nis', 'like', '%'.$this->searchSiswasData.'%')
                    ->orWhere('nama', 'like', '%'.$this->searchSiswasData.'%');
                })
                ->whereNotIn('id', $excludeSiswa)
                ->where('status', 'A')
                ->orderBy('nama','asc')
                ->paginate(5);
        }else{
            $dataSiswas = ModelsSiswa::where('status', 'XXXXX')
                ->orderBy('nama','asc')
                ->paginate(5);
        }

        return view('livewire.konfigurasidata.siswa',['dataKonfSiswa'=>$dataKonfSiswa,'kelasDesc'=>$this->kelasDesc,'ta_id'=>$this->kelas_taid,'dataSiswas'=>$dataSiswas]);
    }

    public function store(){
        $roles = [
            'siswas_id' => 'required',
        ];

        $message = [
            'siswas_id.required' => 'Siswa wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            Konfigurasi_siswa::create([
                'kelas_id' => $this->kelas_id,
                'siswas_id' => $this->siswas_id
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Siswa berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Siswa gagal disimpan'. $e->getMessage());
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Siswa gagal disimpan'. $e->getMessage());
        }
        $this->setClearModel();
    }

    public function setAdd(){
        $this->setClearModel();
        $this->clearGroupId();
    }

    public function setDeleted($id){
        $this->id = $id;
    }

    public function setEdited($id){
        $this->id = $id;
        $konfSiswa = Konfigurasi_siswa::findOrFail($this->id);
        if ($konfSiswa) {
            $this->siswas_id = $konfSiswa->siswas_id;
            if($this->siswas_id!=""){
                $this->setSiswasId($this->siswas_id);
            }
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $konfSiswa = Konfigurasi_siswa::findOrFail($this->id);
        if ($konfSiswa) {
            $this->siswas_id = $konfSiswa->siswas_id;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->siswas_id = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'siswas_id' => 'required',
        ];

        $message = [
            'siswas_id.required' => 'Siswa wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $konfSiswa = Konfigurasi_siswa::findOrFail($this->id);
            if (!$konfSiswa) {
                session()->flash('error', 'Siswa tidak ditemukan');
            }else{
                $konfSiswa->siswas_id = $this->siswas_id;
                $konfSiswa->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Siswa berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Siswa gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Siswa gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $konfSiswa = Konfigurasi_siswa::findOrFail($this->id);
            if (!$konfSiswa) {
                session()->flash('error', 'Siswa tidak ditemukan');
            }else{
                $konfSiswa->status = 'N';
                $konfSiswa->save();
                session()->flash('message', 'Siswa berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Siswa gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Siswa gagal dihapus');
        }
        $this->setClearModel();
    }
}
