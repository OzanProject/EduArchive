@extends('backend.adminlembaga.students._print_layout')

@section('title', 'Cetak Data Siswa Masal')

@section('content')
  @foreach($students as $index => $student)
    @include('backend.adminlembaga.students._print_content', [
        'student' => $student, 
        'isBulk' => true, 
        'isLast' => $loop->last
    ])
  @endforeach
@endsection