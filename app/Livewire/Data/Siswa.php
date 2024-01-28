<?php

namespace App\Livewire\Data;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Siswa as ModelsSiswa;
use Illuminate\Database\QueryException;

class Siswa extends Component
{
    use WithPagination;
    protected $paginationTheme='bootstrap';

    public $nis;
    public $nama;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $nama_orang_tua;
    public $telepon_orang_tua;
    public $tanggal_masuk;
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
            $dataSiswa = ModelsSiswa::where(
                function ($query) {
                    $query->where('nip', 'like', '%'.$this->searchData.'%')
                    ->orWhere('nama', 'like', '%'.$this->searchData.'%')
                    ->orWhere('telepon', 'like', '%'.$this->searchData.'%')
                    ->orWhere('pendidikan', 'like', '%'.$this->searchData.'%');
                })->where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }else{
            $dataSiswa = ModelsSiswa::where('status', 'A')->orderBy('nama','asc')->paginate($this->totalPagging);
        }

        return view('livewire.data.siswa',['dataSiswa'=>$dataSiswa]);
    }

    public function store(){

        $roles = [
            'nis' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'tanggal_masuk' => 'required'
        ];

        $message = [
            'nis.required' => 'NIP wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Tempat Lahir wajib diisi',
            'tempat_lahir.required' => 'Tanggal Lahir wajib diisi',
            'tanggal_lahir.required' => 'No Telepon wajib diisi',
            'tanggal_masuk.required' => 'Tanggal bergabung wajib diisi'
        ];

        $this->validate($roles,$message);
        try {
            ModelsSiswa::create([
                'nis' => $this->nis,
                'nama' => $this->nama,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'alamat' => $this->alamat,
                'nama_orang_tua' => $this->nama_orang_tua,
                'telepon_orang_tua' => $this->telepon_orang_tua,
                'tanggal_masuk' => $this->tanggal_masuk,
            ]);
            $this->dispatch('afterProcess');
            session()->flash('message', 'Data Siswa berhasil disimpan');
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Siswa gagal disimpan');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Siswa gagal disimpan');
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
        $siswa = ModelsSiswa::findOrFail($this->id);
        if ($siswa) {
            $this->nis = $siswa->nis;
            $this->nama = $siswa->nama;
            $this->jenis_kelamin = $siswa->jenis_kelamin;
            $this->tempat_lahir = $siswa->tempat_lahir;
            $this->tanggal_lahir = $siswa->tanggal_lahir;
            $this->alamat = $siswa->alamat;
            $this->telepon_orang_tua = $siswa->telepon_orang_tua;
            $this->tanggal_masuk = $siswa->tanggal_masuk;
            $this->editMode = true;
            $this->detailMode = false;
        }
    }
    public function setDetails($id){
        $this->id = $id;
        $siswa = ModelsSiswa::findOrFail($this->id);
        if ($siswa) {
            $this->nis = $siswa->nis;
            $this->nama = $siswa->nama;
            $this->jenis_kelamin = $siswa->jenis_kelamin;
            $this->tempat_lahir = $siswa->tempat_lahir;
            $this->tanggal_lahir = $siswa->tanggal_lahir;
            $this->alamat = $siswa->alamat;
            $this->telepon_orang_tua = $siswa->telepon_orang_tua;
            $this->tanggal_masuk = $siswa->tanggal_masuk;
            $this->editMode = false;
            $this->detailMode = true;
        }
    }
    private function setClearModel(){
        $this->nis = '';
        $this->nama = '';
        $this->jenis_kelamin = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = '';
        $this->alamat = '';
        $this->telepon_orang_tua = '';
        $this->tanggal_masuk = '';
        $this->editMode = false;
        $this->detailMode = false;
    }

    public function update_data(){
        $roles = [
            'nis' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'tanggal_masuk' => 'required'
        ];

        $message = [
            'nis.required' => 'NIP wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Tempat Lahir wajib diisi',
            'tempat_lahir.required' => 'Tanggal Lahir wajib diisi',
            'tanggal_lahir.required' => 'No Telepon wajib diisi',
            'tanggal_masuk.required' => 'Tanggal bergabung wajib diisi'
        ];

        $this->validate($roles,$message);

        try {
            $siswa = ModelsSiswa::findOrFail($this->id);
            if (!$siswa) {
                session()->flash('error', 'Data Siswa tidak ditemukan');
            }else{
                $siswa->nis = $this->nis;
                $siswa->nama = $this->nama;
                $siswa->jenis_kelamin = $this->jenis_kelamin;
                $siswa->tempat_lahir = $this->tempat_lahir;
                $siswa->tanggal_lahir = $this->tanggal_lahir;
                $siswa->alamat = $this->alamat;
                $siswa->telepon_orang_tua = $this->telepon_orang_tua;
                $siswa->tanggal_masuk = $this->tanggal_masuk;
                $siswa->save();
                $this->dispatch('afterProcess');
                session()->flash('message', 'Data Siswa berhasil diubah');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Siswa gagal diubah');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            $this->dispatch('afterProcess');
            session()->flash('error', 'Data Siswa gagal diubah');
        }
        $this->setClearModel();
    }

    public function remove_data(){
        try {
            $siswa = ModelsSiswa::findOrFail($this->id);
            if (!$siswa) {
                session()->flash('error', 'Data Siswa tidak ditemukan');
            }else{
                $siswa->status = 'N';
                $siswa->save();
                session()->flash('message', 'Data Siswa berhasil dihapus');
            }
        } catch (QueryException $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            session()->flash('error', 'Data Siswa gagal dihapus');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            session()->flash('error', 'Data Siswa gagal dihapus');
        }
        $this->setClearModel();
    }
}
