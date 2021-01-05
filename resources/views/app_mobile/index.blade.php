@extends('layouts.app')

@section('title', 'Notificações do App Mobile')

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
                <div class="col-md-11">
                  <a class="btn btn-success btn-create-notification" href="/app-notification/create">  
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    Criar Nova Notificação
                  </a>
                </div>

                <div class="col-md-1">
                  <a class="btn btn-success btn-refresh-table" data-refresh-table>  
                    <i class="fas fa-sync-alt" aria-hidden="true"></i>
                    Atualizar
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="ibox-content">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" id="table-notification" data-table-app-notification>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Total</th>
                    <th>Enviados</th>
                    <th>Status</th>
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
                    <th>Total</th>
                    <th>Enviados</th>
                    <th>Status</th>
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
  <input type="hidden" value="{{env('APP_ENV')}}" data-type-env>
  
@endsection

@section('script')
  <script src="{{ asset('assets/js/app/app-notification.js') }}"></script>
@endsection