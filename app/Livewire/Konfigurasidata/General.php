<?php

namespace App\Livewire\Konfigurasidata;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\General as ModelsGeneral;

class General extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $kode_id;
    public $kode_desc_id;
    public $desc;
    public $value;
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
            $dataGeneral = ModelsGeneral::where(
                function ($query) {
                    $query->where('kode_id', 'like', '%'.$this->searchData.'%')
                    ->orWhere('kode_desc_id', 'like', '%'.$this->searchData.'%')
                    ->orWhere('desc', 'like', '%'.$this->searchData.'%')
                    ->orWhere('value', 'like', '%'.$this->searchData.'%');
                })->where('status', 'A')->orderBy('kode_id','asc')->paginate($this->totalPagging);
        }else{
            $dataGeneral = ModelsGeneral::where('status', 'A')->orderBy('kode_id','asc')->paginate($this->totalPagging);
        }
        return view('livewire.konfigurasidata.general',['dataGeneral'=>$dataGeneral]);
    }

    public function setEdited($id){
        $this->id = $id;
        $dataGeneral = ModelsGeneral::findOrFail($this->id);
        if ($dataGeneral) {
            $this->kode_id = $dataGeneral->kode_id;
            $this->kode_desc_id = $dataGeneral->kekode_desc_idterangan;
            $this->desc = $dataGeneral->desc;
            $this->value = $dataGeneral->value;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $dataGeneral = ModelsGeneral::findOrFail($this->id);
        if ($dataGeneral) {
            $this->kode_id = $dataGeneral->kode_id;
            $this->kode_desc_id = $dataGeneral->kekode_desc_idterangan;
            $this->desc = $dataGeneral->desc;
            $this->value = $dataGeneral->value;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->kode_id = '';
        $this->kode_desc_id = '';
        $this->desc = '';
        $this->value = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'value' => 'required'
        ];

        $message = [
            'value.required' => 'Value wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $dataGeneral = ModelsGeneral::findOrFail($this->id);
            if (!$dataGeneral) {
                session()->flash('error', 'Data tidak ditemukan');
            }else{
                $dataGeneral->value = $this->value;
                $dataGeneral->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Data berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data gagal diubah');
        }
        $this->setClearModel();
    }
}
