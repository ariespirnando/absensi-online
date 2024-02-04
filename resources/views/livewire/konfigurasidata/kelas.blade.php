<div>


    <!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            Overview
          </div>
          <h3 class="page-title">
            Konfigurasi TA & Kelas
          </h3>
        </div>
        <br>

        @if(session('message'))

        <div class="alert alert-success alert-dismissible" role="alert">
        <div class="d-flex">
            <div>
            <h4 class="alert-title">Pesan Aksi</h4>
            <div class="text-secondary">{{ session('message') }}</div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>

        @endif

        @if(session('error'))

        <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div>
            <h4 class="alert-title">Pesan Aksi</h4>
            <div class="text-secondary">{{ session('error') }}</div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>

        @endif

        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a wire:click='setAdd()' class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              Tambah Data
            </a>
            <a wire:click='setAdd()' class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            </a>
            <a href="{{ route('konfigurasi_ta') }}" class="btn btn-default d-none d-sm-inline-block">
                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                Kembali
            </a>
            <a href="{{ route('konfigurasi_ta') }}" class="btn btn-default d-sm-none btn-icon" >
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Page body -->
  <div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Kelas TA {{$tahun_ajarDesc}}</h3>
            </div>
            <div class="card-body border-bottom py-3">
              <div class="d-flex">
                <div class="text-muted">
                  Show
                  <div class="mx-2 d-inline-block">
                    <input wire:model.live='totalPagging' type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                  </div>
                  entries
                </div>
                <div class="ms-auto text-muted">
                  Search:
                  <div class="ms-2 d-inline-block">
                    <input wire:model.live='searchData' type="text" class="form-control form-control-sm" aria-label="Search invoice">
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <tr>
                     <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
                    </th>
                    <th width="10%">Group</th>
                    <th width="10%">Kelas</th>
                    <th width="40%">Wali Kelas</th>
                    <th width="40%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dataKelas as $key => $values )
                  <tr>
                    <td><span class="text-muted">{{ $dataKelas->firstItem() + $key }}</span></td>
                    <td><span class="text-muted">{{ $values->group }}</span></td>
                    <td><span class="text-muted">{{ $values->nama }}</span></td>
                    <td><span class="text-muted">{{ $values->gurusnames }}</span></td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            <a href="{{ route('konfigurasi_pelajaran', ['id'=>encrypt($values->id)]) }}" class="btn">
                                Daftar Pelajaran
                            </a>
                            <a href="{{ route('konfigurasi_siswa', ['id'=>encrypt($values->id)]) }}" class="btn">
                                Daftar Siswa
                            </a>
                            <a wire:click='setEdited({{$values->id}})' class="btn" data-bs-toggle="modal" data-bs-target="#modal-report">
                              Ubah
                            </a>
                            <a wire:click='setDeleted({{$values->id}})' class="btn" data-bs-toggle="modal" data-bs-target="#modal-simple">
                              Hapus
                            </a>
                        </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex align-items-center">
             {{ $dataKelas->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- MODAL --}}
  <div wire:ignore.self class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          @if ($editMode==true)
          <h5 class="modal-title">Ubah Data Kelas</h5>
          @elseif ($detailMode==true)
          <h5 class="modal-title">Detail Data Kelas</h5>
          @else
          <h5 class="modal-title">Tambah Guru</h5>
          @endif
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Group Kelas</label>
                    <select {{ $detailMode?"disabled":""}} wire:model='group' class="form-select" name="group">
                    <option value="">Pilih Group Kelas</option>
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                    </select>
                    @error('group')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                    <div class="input-group input-group-flat">
                    <input {{ $detailMode?"disabled":""}} type="text" class="form-control" name="nama" placeholder="Nama Kelas" wire:model='nama'>
                    </div>
                    @error('nama')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        </div>
        <div class="modal-body">
            <div class="row">
                @if ($gurusIdSelected==true)
                <div class="col-lg-12">
                    <div class="mb-3">
                    <label class="form-label">Wali Kelas</label>
                    @if ($detailMode)
                        <div class="input-group input-group-flat">
                        <input disabled type="text" class="form-control" name="nama" placeholder="Nama wali kelas" wire:model='gurusDesc'>
                        </div>
                    @else
                    <div class="input-group mb-2">
                        <input disabled type="text" class="form-control" placeholder="" wire:model='gurusDesc'>
                        <button wire:click='clearGroupId()' class="btn" type="button">Clear</button>
                    </div>
                    @endif
                    </div>
                </div>
                @else
                <div class="col-lg-12">
                    <div class="mb-3">
                    <label class="form-label">Wali Kelas</label>
                        <div class="input-group input-group-flat">
                        <input type="text" class="form-control" name="nama" placeholder="Nama wali kelas" wire:model.live='searchGurusData'>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                        <tr>
                            <th width="10%">Aksi</th>
                            <th width="10%">NIP</th>
                            <th width="20%">Wali Kelas</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataGurus as $key => $values )
                            <tr>
                                <td>
                                <span wire:click='setGurusId({{$values->id}})' class="btn">
                                    Pilih
                                </span>
                                </td>
                                <td><span class="text-muted">{{ $values->nip }}</span></td>
                                <td><span class="text-muted">{{ $values->nama }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        {{ $dataGurus->links()}}
                    </div>
                </div>
                @endif
        </div>

        <div class="modal-footer">
          <a href="#" id="close_form_data" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancel
          </a>
          @if ($editMode == true)
          <a wire:click='update_data()' class="btn btn-primary ms-auto">
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
             Ubah Data Kelas
          </a>
          @elseif ($detailMode == false && $editMode== false)
          <a wire:click='store()' class="btn btn-primary ms-auto">
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Simpan Data Kelas
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div wire:ignore.self class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Semua data yang berkaitan akan terhapus, Apakah anda yakin ingin menghapus ini ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
          <button wire:click="remove_data()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Hapus</button>
        </div>
      </div>
    </div>
  </div>
  {{ csrf_field() }}
</div>

@livewireScripts
<script>
Livewire.on('afterProcess', function () {
    document.getElementById('close_form_data').click();
});
</script>
