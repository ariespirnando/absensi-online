@extends('layout.app')
@section('contents')
@livewire('absensi-details', ['konfigurasi_pelajarans_id' => $id])
@endsection
