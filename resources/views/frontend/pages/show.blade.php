@extends('frontend.layouts.app')

@section('content')
  <div class="bg-primary pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-6 md:px-20">
      <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $page->title }}</h1>
      <div class="flex items-center gap-2 text-white/80 text-sm">
        <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
        <span>/</span>
        <span>{{ $page->title }}</span>
      </div>
    </div>
  </div>

  <div class="py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 prose prose-slate max-w-none">
        {!! $page->content !!}
      </div>
    </div>
  </div>
@endsection