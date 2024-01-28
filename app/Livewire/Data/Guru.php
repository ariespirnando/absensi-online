<?php

namespace App\Livewire\Data;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Guru as ModelsGuru;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class Guru extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $nip;
    public $nama;
    public $jenis_kelamin;
    public $pendidikan;
    public $alamat;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $telepon;
    public $tanggal_bergabung;
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
            $dataGuru = ModelsGuru::where(
                function ($query) {
                    $query->where('nip', 'like', '%'.$this->searchData.'%')
                    ->orWhere('nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('telepon', 'like', '%'.$this->searchData.'%')
                    ->orWhere('pendidikan', 'like', '%'.$this->searchData.'%');
                })->where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }else{
            $dataGuru = ModelsGuru::where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }

        return view('livewire.data.guru',['dataGuru'=>$dataGuru]);
    }

    public function store(){
        $roles = [
            'nip' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'telepon' => 'required',
            'tanggal_bergabung' => 'required',
            'pendidikan' => 'required',
            'jenis_kelamin' => 'required'
        ];

        $message = [
            'nip.required' => 'NIP wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tempat_lahir.required' => 'Tempat Lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal Lahir wajib diisi',
            'telepon.required' => 'No Telepon wajib diisi',
            'tanggal_bergabung.required' => 'Tanggal bergabung wajib diisi',
            'pendidikan.required' => 'Pendidikan wajib diisi',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            ModelsGuru::create([
                'nip' => $this->nip,
                'nama' => $this->nama,
                'jenis_kelamin' => $this->jenis_kelamin,
                'pendidikan' => $this->pendidikan,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'telepon' => $this->telepon,
                'tanggal_bergabung' => $this->tanggal_bergabung,
                'alamat' => $this->alamat,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Data guru berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data guru gagal disimpan');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data guru gagal disimpan');
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
        $guru = ModelsGuru::findOrFail($this->id);
        if ($guru) {
            $this->nip = $guru->nip;
            $this->nama = $guru->nama;
            $this->jenis_kelamin = $guru->jenis_kelamin;
            $this->pendidikan = $guru->pendidikan;
            $this->alamat = $guru->alamat;
            $this->tempat_lahir = $guru->tempat_lahir;
            $this->tanggal_lahir = $guru->tanggal_lahir;
            $this->telepon = $guru->telepon;
            $this->tanggal_bergabung = $guru->tanggal_bergabung;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $guru = ModelsGuru::findOrFail($this->id);
        if ($guru) {
            $this->nip = $guru->nip;
            $this->nama = $guru->nama;
            $this->jenis_kelamin = $guru->jenis_kelamin;
            $this->pendidikan = $guru->pendidikan;
            $this->alamat = $guru->alamat;
            $this->tempat_lahir = $guru->tempat_lahir;
            $this->tanggal_lahir = $guru->tanggal_lahir;
            $this->telepon = $guru->telepon;
            $this->tanggal_bergabung = $guru->tanggal_bergabung;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->id = '';
        $this->nip = '';
        $this->nama = '';
        $this->jenis_kelamin = '';
        $this->pendidikan = '';
        $this->alamat = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = '';
        $this->telepon = '';
        $this->tanggal_bergabung = '';
        $this->editMode = false;
        $this->detailMode = false;
    }
    public function update_data(){
        $roles = [
            'nip' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'telepon' => 'required',
            'tanggal_bergabung' => 'required'
        ];

        $message = [
            'nip.required' => 'NIP wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tempat_lahir.required' => 'Tempat Lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal Lahir wajib diisi',
            'telepon.required' => 'No Telepon wajib diisi',
            'tanggal_bergabung.required' => 'Tanggal bergabung wajib diisi',
        ];

        $this->validate($roles,$message);

        try {
            $guru = ModelsGuru::findOrFail($this->id);
            if (!$guru) {
                session()->flash('error', 'Data guru tidak ditemukan');
            }else{
                $guru->nip = $this->nip;
                $guru->nama = $this->nama;
                $guru->jenis_kelamin = $this->jenis_kelamin;
                $guru->pendidikan = $this->pendidikan;
                $guru->tempat_lahir = $this->tempat_lahir;
                $guru->tanggal_lahir = $this->tanggal_lahir;
                $guru->telepon = $this->telepon;
                $guru->tanggal_bergabung = $this->tanggal_bergabung;
                $guru->alamat = $this->alamat;
                $guru->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Data guru berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data guru gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data guru gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $guru = ModelsGuru::findOrFail($this->id);
            if (!$guru) {
                session()->flash('error', 'Data guru tidak ditemukan');
            }else{
                $guru->status = 'N';
                $guru->save();
                session()->flash('message', 'Data guru berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Data guru gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Data guru gagal dihapus');
        }
        $this->setClearModel();
    }
}
