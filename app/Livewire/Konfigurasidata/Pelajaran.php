<?php

namespace App\Livewire\Konfigurasidata;

use App\Models\Guru;
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
            $this->pelajaranDesc = $pelajaran->name;
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
        return view('livewire.konfigurasidata.pelajaran');
    }

    public function store(){
        $roles = [
            'pelajarans_id' => 'required',
        ];

        $message = [
            'pelajarans_id.required' => 'Siswa wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            Konfigurasi_pelajaran::create([
                'kelas_id' => $this->kelas_id,
                'pelajarans_id' => $this->pelajarans_id,
                'gurus_id' => $this->gurus_id
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
                session()->flash('error', 'Tahun Ajar tidak ditemukan');
            }else{
                $konfSiswa->siswas_id = $this->siswas_id;
                $konfSiswa->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Tahun Ajar berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Tahun Ajar gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Tahun Ajar gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $konfSiswa = Konfigurasi_siswa::findOrFail($this->id);
            if (!$konfSiswa) {
                session()->flash('error', 'Kelas tidak ditemukan');
            }else{
                $konfSiswa->status = 'N';
                $konfSiswa->save();
                session()->flash('message', 'Kelas berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Kelas gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Kelas gagal dihapus');
        }
        $this->setClearModel();
    }
}
