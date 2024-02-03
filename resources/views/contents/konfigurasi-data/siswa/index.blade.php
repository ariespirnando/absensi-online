@extends('layout.app')
@section('contents')
@livewire('konfigurasidata\siswa', ['kelas_id' => $id])
@endsection
