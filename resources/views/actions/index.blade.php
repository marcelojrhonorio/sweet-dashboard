@extends('layouts.app')

@section('title', 'Ações')

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
            <div class="ibox float-e-margins">
              <div class="">
                <div class="col-md-8">
                  <button id="new" class="btn btn-success btn-new" type="button" name="new" data-btn-new>
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    Adicionar Nova Ação
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="ibox-content">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-table-actions>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Foto</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th>Nº de Pontos</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- JavaScript content --}}
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Foto</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th>Nº de Pontos</th>
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

  @include('actions.partials.form')
@endsection

@section('script')
  <script src="{{ asset('assets/js/app/screen-actions.js') }}"></script>
@endsection
