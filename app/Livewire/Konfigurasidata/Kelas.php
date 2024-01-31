<?php

namespace App\Livewire\Konfigurasidata;

use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Kelas as ModelsKelas;
use App\Models\TahunAjar;
use Illuminate\Database\QueryException;

class Kelas extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $group;
    public $nama;
    public $tahun_ajars_id;
    public $gurus_id;
    public $id;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;

    public $gurusData;
    public $tahunAjarsData;
    public $allreadyAddAdditionalData = false;

    public function render()
    {
        if (!is_numeric($this->totalPagging)) {
            $this->totalPagging = 10;
        }
        if($this->searchData!=""){
            $dataKelas = ModelsKelas::join('gurus', 'kelas.gurus_id', '=', 'gurus.id')
                ->join('tahun_ajars', 'kelas.gurus_id', '=', 'tahun_ajars.id')
                ->select('kelas.*', 'tahun_ajars.tahun', 'tahun_ajars.semester', 'gurus.nip', 'gurus.nama')
                ->where(
                function ($query) {
                    $query->where('kelas.group', 'like', '%'.$this->searchData.'%')
                    ->orWhere('kelas.nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('kelas.keterangan', 'like', '%'.$this->searchData.'%')
                    ->orWhere('tahun_ajars.tahun', 'like', '%'.$this->searchData.'%')
                    ->orWhere('tahun_ajars.semester', 'like', '%'.$this->searchData.'%')
                    ->orWhere('gurus.nama', 'like', '%'.$this->searchData.'%');
                })
                ->where('kelas.status', 'A')
                ->where('tahun_ajars.status', 'A')
                ->orderBy('kelas.group','asc')
                ->paginate($this->totalPagging);
        }else{
            $dataKelas = ModelsKelas::join('gurus', 'kelas.gurus_id', '=', 'gurus.id')
            ->join('tahun_ajars', 'kelas.gurus_id', '=', 'tahun_ajars.id')
            ->select('kelas.*', 'tahun_ajars.tahun', 'tahun_ajars.semester', 'gurus.nip', 'gurus.nama')
            ->orderBy('kelas.group','asc')
            ->paginate($this->totalPagging);
        }
        return view('livewire.konfigurasidata.kelas',['dataKelas'=>$dataKelas]);
    }

    public function store(){

        $roles = [
            'group' => 'required',
            'nama' => 'required',
            'tahun_ajars_id' => 'tahun_ajars_id',
            'gurus_id' => 'gurus_id'
        ];

        $message = [
            'group.required' => 'Kelas Wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tahun_ajars_id.required' => 'Tahun Ajar wajib diisi',
            'gurus_id.required' => 'Wali Kelas wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            ModelsKelas::create([
                'group' => $this->group,
                'nama' => $this->nama,
                'tahun_ajars_id' => $this->tahun_ajars_id,
                'gurus_id' => $this->gurus_id,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Kelas berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Kelas gagal disimpan'. $e->getMessage());
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Kelas gagal disimpan'. $e->getMessage());
        }
        $this->setClearModel();
    }

    public function setAdditionalDatas(){
        $this->gurusData = Guru::select('id', 'nip', 'nama')->where('status', 'A')->orderBy('nama','asc')->get();
        $this->tahunAjarsData = TahunAjar::select('id', 'tahun', 'semester')->where('status', 'A')->orderBy('tahun','asc')->get();
        $this->allreadyAddAdditionalData = true;
    }

    public function setAdd(){
        $this->setClearModel();
        if(!$this->allreadyAddAdditionalData){
            $this->setAdditionalDatas();
        }
    }

    public function setDeleted($id){
        $this->id = $id;
    }

    public function setEdited($id){
        $this->id = $id;
        $kelas = ModelsKelas::findOrFail($this->id);
        if ($kelas) {
            $this->group = $kelas->tahun;
            $this->nama = $kelas->semester;
            $this->tahun_ajars_id = $kelas->keterangan;
            $this->gurus_id = $kelas->keterangan;
            $this->editMode = true;
            $this->detailMode = false;
        }
        if(!$this->allreadyAddAdditionalData){
            $this->setAdditionalDatas();
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $kelas = ModelsKelas::findOrFail($this->id);
        if ($kelas) {
            $this->group = $kelas->tahun;
            $this->nama = $kelas->semester;
            $this->tahun_ajars_id = $kelas->keterangan;
            $this->gurus_id = $kelas->keterangan;
            $this->editMode = false;
            $this->detailMode = true;
        }
        if(!$this->allreadyAddAdditionalData){
            $this->setAdditionalDatas();
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->group = '';
        $this->nama = '';
        $this->tahun_ajars_id = '';
        $this->gurus_id = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'group' => 'required',
            'nama' => 'required',
            'tahun_ajars_id' => 'tahun_ajars_id',
            'gurus_id' => 'gurus_id'
        ];

        $message = [
            'group.required' => 'Kelas Wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tahun_ajars_id.required' => 'Tahun Ajar wajib diisi',
            'gurus_id.required' => 'Wali Kelas wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $kelas = ModelsKelas::findOrFail($this->id);
            if (!$kelas) {
                session()->flash('error', 'Kelas tidak ditemukan');
            }else{
                $kelas->group = $this->group;
                $kelas->nama = $this->nama;
                $kelas->tahun_ajars_id = $this->tahun_ajars_id;
                $kelas->gurus_id = $this->gurus_id;
                $kelas->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Kelas berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Kelas gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Kelas gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $kelas = ModelsKelas::findOrFail($this->id);
            if (!$kelas) {
                session()->flash('error', 'Kelas tidak ditemukan');
            }else{
                $kelas->status = 'N';
                $kelas->save();
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
