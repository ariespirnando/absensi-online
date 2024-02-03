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
    public $gurus_id;
    public $id;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;

    public $tahun_ajars_id;
    public $tahun_ajarDesc;
    public $searchGurusData;
    public $gurusDesc;
    public $gurusIdSelected = false;

    public function mount($tahun_ajars_id)
    {
        $this->tahun_ajars_id = $tahun_ajars_id;
    }

    public function getTahunAjarDesc(){
        if($this->tahun_ajarDesc == ""){
            $tahunAjar = TahunAjar::findOrFail($this->tahun_ajars_id);
            if ($tahunAjar) {
                $this->tahun_ajarDesc = $tahunAjar->tahun."/".$tahunAjar->semester;
            }
        }
    }

    public function setGurusId($id){
        $this->gurus_id  = $id;
        $gurus = Guru::findOrFail($id);
        if ($gurus) {
            $this->gurusDesc = "[".$gurus->nip."] ".$gurus->nama;
            $this->gurusIdSelected = true;
        }
    }

    public function clearGroupId(){
        $this->gurusDesc = "";
        $this->gurus_id  ="";
        $this->gurusIdSelected=false;
        $this->searchGurusData ="";
    }

    public function render()
    {
        if (!is_numeric($this->totalPagging)) {
            $this->totalPagging = 10;
        }


        if($this->searchData!=""){
            $dataKelas = ModelsKelas::join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
                ->leftjoin('gurus', 'kelas.gurus_id', '=', 'gurus.id')
                ->select('kelas.*', 'tahun_ajars.tahun', 'tahun_ajars.semester', 'gurus.nip', 'gurus.nama as gurusnames')
                ->where(
                function ($query) {
                    $query->where('kelas.group', 'like', '%'.$this->searchData.'%')
                    ->orWhere('kelas.nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('kelas.keterangan', 'like', '%'.$this->searchData.'%')
                    ->orWhere('gurus.nama', 'like', '%'.$this->searchData.'%');
                })
                ->where('kelas.status', 'A')
                ->where('tahun_ajars.status', 'A')
                ->where('tahun_ajars.id', $this->tahun_ajars_id)
                ->orderBy('kelas.group','asc')
                ->paginate($this->totalPagging);
        }else{
            $dataKelas = ModelsKelas::join('tahun_ajars', 'kelas.tahun_ajars_id', '=', 'tahun_ajars.id')
            ->leftjoin('gurus', 'kelas.gurus_id', '=', 'gurus.id')
            ->select('kelas.*', 'tahun_ajars.tahun', 'tahun_ajars.semester', 'gurus.nip', 'gurus.nama as gurusnames')
            ->where('kelas.status', 'A')
            ->where('tahun_ajars.status', 'A')
            ->where('tahun_ajars.id', $this->tahun_ajars_id)
            ->orderBy('kelas.group','asc')
            ->paginate($this->totalPagging);
        }
        $this->getTahunAjarDesc();

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
        return view('livewire.konfigurasidata.kelas',['dataKelas'=>$dataKelas,'tahun_ajarDesc'=>$this->tahun_ajarDesc,'dataGurus'=>$dataGurus]);
    }

    public function store(){

        $roles = [
            'group' => 'required',
            'nama' => 'required'
        ];

        $message = [
            'group.required' => 'Kelas Wajib diisi',
            'nama.required' => 'Nama wajib diisi'
        ];

        $this->validate($roles,$message);

        try {
            ModelsKelas::create([
                'group' => $this->group,
                'nama' => $this->nama,
                'gurus_id' => $this->gurus_id,
                'tahun_ajars_id' => $this->tahun_ajars_id,
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

    public function setAdd(){
        $this->setClearModel();
        $this->clearGroupId();
    }

    public function setDeleted($id){
        $this->id = $id;
    }

    public function setEdited($id){
        $this->id = $id;
        $kelas = ModelsKelas::findOrFail($this->id);
        if ($kelas) {
            $this->group = $kelas->group;
            $this->nama = $kelas->nama;
            if($kelas->gurus_id!=""){
                $this->setGurusId($kelas->gurus_id);
            }
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $kelas = ModelsKelas::findOrFail($this->id);
        if ($kelas) {
            $this->group = $kelas->group;
            $this->nama = $kelas->nama;
            if($kelas->gurus_id!=""){
                $this->setGurusId($kelas->gurus_id);
            }
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->group = '';
        $this->nama = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'group' => 'required',
            'nama' => 'required'
        ];

        $message = [
            'group.required' => 'Kelas Wajib diisi',
            'nama.required' => 'Nama wajib diisi'
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
