@extends('layout.app')
@section('contents')
@livewire('absensi-details-siswa', ['absensis_id' => $id])
@endsection
