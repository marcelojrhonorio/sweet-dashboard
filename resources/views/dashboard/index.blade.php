@extends('layouts.app')

@section('title', 'Dashboard')

@section('style')
  <style type="text/css">
    #primary-key { display: none; }
  </style>
@endsection

@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>@yield('title')</h2>
      <ol class="breadcrumb">
        <li>
          <a href="/">
            Dashboard
          </a>
        </li>
        <li class="active">
          <b>
            Lista
          </b>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrapper wrapper-content animated fadeInRight"></div>
@endsection



@section('script')
  <script src="{!! asset('assets/js/app/campaigns.js') !!}?{{date('hisdmY')}}"></script>
@endsection




