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
            Absensi Kelas
          </h3>
        </div>

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
              <h3 class="card-title">Absensi Kelas</h3>
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
                    <th width="10%">Tahun / Semester</th>
                    <th width="10%">Grup</th>
                    <th width="10%">Kelas</th>
                    <th width="40%">Mata Pelajaran</th>
                    <th width="10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dataKelas as $key => $values )
                  <tr>
                    <td><span class="text-muted">{{ $dataKelas->firstItem() + $key }}</span></td>
                    <td><span class="text-muted">{{ $values->tahun."/".$values->semester }}</span></td>
                    <td><span class="text-muted">{{ $values->group }}</span></td>
                    <td><span class="text-muted">{{ $values->nama }}</span></td>
                    <td><span class="text-muted">{{ $values->pelajaransnames }}</span></td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            <a href="{{ route('absensi_siswa', ['id'=>encrypt($values->id)]) }}" class="btn">
                              Tambah Absensi
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
  {{ csrf_field() }}
</div>
@livewireScripts
<script>
Livewire.on('afterProcess', function () {
    document.getElementById('close_form_data').click();
});
</script>
