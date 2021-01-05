@extends('layouts.app')

@section('title', 'Troca de Pontos - Convencional')

@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>@yield('title')</h2>
    </div>
  </div>
  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h5>Lista</h5>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-table-exchanges>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ID Customer</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Produto</th>
                    <th>Pontos</th>
                    <th>Solicitado Em</th>
                    <th>Última Atualização</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- JavaScript content --}}
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>ID Customer</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Produto</th>
                    <th>Pontos</th>
                    <th>Solicitado Em</th>
                    <th>Última Atualização</th>
                    <th>Ações</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script src="{{ asset('assets/js/app/screen-exchanges.js') }}"></script>
@endsection