<?php

namespace App\Livewire\Konfigurasidata;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\TahunAjar as ModelsTahunAjar;

class Tahunajar extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $tahun;
    public $semester;
    public $keterangan;
    public $id;

    public $totalPagging = 10;
    public $editMode = false;
    public $detailMode = false;
    public $searchData;

    public function render()
    {
        if (!is_numeric($this->totalPagging)) {
            $this->totalPagging = 10;
        }
        if($this->searchData!=""){
            $dataTa = ModelsTahunAjar::where(
                function ($query) {
                    $query->where('tahun', 'like', '%'.$this->searchData.'%')
                    ->orWhere('semester', 'like', '%'.$this->searchData.'%')
                    ->orWhere('keterangan', 'like', '%'.$this->searchData.'%');
                })->where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }else{
            $dataTa = ModelsTahunAjar::where('status', 'A')->orderBy('tahun','asc')->paginate($this->totalPagging);
        }

        return view('livewire.konfigurasidata.tahunajar',['dataTa'=>$dataTa]);
    }

    public function store(){
        $roles = [
            'tahun' => 'required',
            'semester' => 'required'
        ];

        $message = [
            'tahun.required' => 'NIP wajib diisi',
            'semester.required' => 'Nama wajib diisi'
        ];

        $this->validate($roles,$message);

        try {
            ModelsTahunAjar::create([
                'tahun' => $this->tahun,
                'semester' => $this->semester,
                'keterangan' => $this->keterangan,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Tahun Ajar berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Tahun Ajar gagal disimpan'. $e->getMessage());
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Tahun Ajar gagal disimpan'. $e->getMessage());
        }
        $this->setClearModel();
    }

    public function setAdd(){
        $this->setClearModel();
    }

    public function setDeleted($id){
        $this->id = $id;
    }

    public function setEdited($id){
        $this->id = $id;
        $ta = ModelsTahunAjar::findOrFail($this->id);
        if ($ta) {
            $this->tahun = $ta->tahun;
            $this->semester = $ta->semester;
            $this->keterangan = $ta->keterangan;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $ta = ModelsTahunAjar::findOrFail($this->id);
        if ($ta) {
            $this->tahun = $ta->tahun;
            $this->semester = $ta->semester;
            $this->keterangan = $ta->keterangan;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->tahun = '';
        $this->semester = '';
        $this->keterangan = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'tahun' => 'required',
            'semester' => 'required'
        ];

        $message = [
            'tahun.required' => 'NIP wajib diisi',
            'semester.required' => 'Nama wajib diisi'
        ];

        $this->validate($roles,$message);

        try {
            $ta = ModelsTahunAjar::findOrFail($this->id);
            if (!$ta) {
                session()->flash('error', 'Tahun Ajar tidak ditemukan');
            }else{
                $ta->tahun = $this->tahun;
                $ta->semester = $this->semester;
                $ta->keterangan = $this->keterangan;
                $ta->save();
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
            $ta = ModelsTahunAjar::findOrFail($this->id);
            if (!$ta) {
                session()->flash('error', 'Tahun Ajar tidak ditemukan');
            }else{
                $ta->status = 'N';
                $ta->save();
                session()->flash('message', 'Tahun Ajar berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Tahun Ajar gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Tahun Ajar gagal dihapus');
        }
        $this->setClearModel();
    }
}
