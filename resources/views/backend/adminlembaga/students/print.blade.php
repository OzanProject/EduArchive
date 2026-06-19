@extends('backend.adminlembaga.students._print_layout')

@section('title', 'Data Siswa - ' . $student->nama)

@section('content')
  @include('backend.adminlembaga.students._print_content', ['student' => $student, 'isBulk' => false])
@endsection