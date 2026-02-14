@extends('frontend.layouts.app')

@section('content')

  @include('frontend.landing.hero')
  @include('frontend.landing.trusted-by')
  @include('frontend.landing.features')
  @include('frontend.landing.architecture')
  @include('frontend.landing.security')
  @include('frontend.landing.cta')

@endsection