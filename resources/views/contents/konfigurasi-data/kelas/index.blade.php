@extends('layout.app')
@section('contents')
@livewire('konfigurasidata\kelas', ['tahun_ajars_id' => $id])
@endsection
