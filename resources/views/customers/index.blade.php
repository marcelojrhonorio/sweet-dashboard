@extends('layouts.app')

@section('title', 'Usuários')

@section('style')

  <style type="text/css">
    #primary-key {
      display: none;
    }
  </style>

@endsection

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
              <table class="table table-striped table-bordered table-hover customers-list" style="width: 100%" data-customers-list>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Pontos</th>
                    <th>Sexo</th>
                    <th>Data de Nascimento</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>DDD / Telefone</th>
                    <th>Duplo optin</th>
                    <th>Data de Cadastro</th>
                    <th>Ações</th>                    
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Pontos</th>
                    <th>Sexo</th>
                    <th>Data de Nascimento</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>DDD / Telefone</th>
                    <th>Duplo optin</th>
                    <th>Data de Cadastro</th>
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
  @include('customers.partials.customer-points')
@endsection

@section('script')

  <script src="{!! asset('assets/js/app/customers.js') !!}?{{time()}}"></script>

@endsection
