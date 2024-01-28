<?php

namespace App\Livewire\Data;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Pelajaran as ModelsPelajaran;

class Pelajaran extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $nama;
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
            $dataPelajaran = ModelsPelajaran::where(
                function ($query) {
                    $query->where('nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('keterangan', 'like', '%'.$this->searchData.'%');
                })->where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }else{
            $dataPelajaran = ModelsPelajaran::where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }

        return view('livewire.data.pelajaran',['dataPelajaran'=>$dataPelajaran]);
    }
    public function store(){
        $roles = [
            'nama' => 'required',
        ];

        $message = [
            'nama.required' => 'Nama wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            ModelsPelajaran::create([
                'nama' => $this->nama,
                'keterangan' => $this->keterangan,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Data Pelajaran berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Pelajaran gagal disimpan');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Pelajaran gagal disimpan');
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
        $pelajaran = ModelsPelajaran::findOrFail($this->id);
        if ($pelajaran) {
            $this->nama = $pelajaran->nama;
            $this->keterangan = $pelajaran->keterangan;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $pelajaran = ModelsPelajaran::findOrFail($this->id);
        if ($pelajaran) {
            $this->nama = $pelajaran->nama;
            $this->keterangan = $pelajaran->keterangan;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->nama = '';
        $this->keterangan = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'nama' => 'required',
        ];

        $message = [
            'nama.required' => 'Nama wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $pelajaran = ModelsPelajaran::findOrFail($this->id);
            if (!$pelajaran) {
                session()->flash('error', 'Data Pelajaran tidak ditemukan');
            }else{
                $pelajaran->nama = $this->nama;
                $pelajaran->keterangan = $this->keterangan;
                $pelajaran->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Data Pelajaran berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Pelajaran gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Pelajaran gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $pelajaran = ModelsPelajaran::findOrFail($this->id);
            if (!$pelajaran) {
                session()->flash('error', 'Data Pelajaran tidak ditemukan');
            }else{
                $pelajaran->status = 'N';
                $pelajaran->save();
                session()->flash('message', 'Data Pelajaran berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Data Pelajaran gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Data Pelajaran gagal dihapus');
        }
        $this->setClearModel();
    }
}
