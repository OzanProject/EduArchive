@extends('frontend.layouts.app')

@section('content')

  {{-- Tambahkan margin-top agar tidak tertutup navbar yang sticky --}}
  <div class="pt-8">
    @include('frontend.landing.progress')
  </div>

@endsection
