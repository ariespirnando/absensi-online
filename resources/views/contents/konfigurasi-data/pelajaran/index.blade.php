@extends('layout.app')
@section('contents')
@livewire('konfigurasidata\pelajaran', ['kelas_id' => $id])
@endsection
