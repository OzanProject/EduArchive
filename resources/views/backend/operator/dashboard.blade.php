@extends('backend.layouts.app')

@section('title', 'Operator Dashboard')
@section('page_title', 'Dashboard Operator')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Selamat Datang, Operator</h3>
          </div>
          <div class="card-body">
            Panel Operator
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection