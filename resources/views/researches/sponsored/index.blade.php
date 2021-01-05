@extends('layouts.app')

@section('title', 'Pesquisas Patrocinadas')

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
                  <a class="btn btn-success btn-new" href="/researches/sponsored/create">  
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    Adicionar Nova Pesquisa 
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="ibox-content">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-table-researches-sponsored>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Descrição</th>
                    <th>Pontos</th>
                    <th>Url Final</th>
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
                    <th>Subtítulo</th>
                    <th>Descrição</th>
                    <th>Pontos</th>
                    <th>Url Final</th>
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

  
  
@endsection

@section('script')
  <script src="{{ asset('assets/js/app/sponsored_researches/researches.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/questions.js') }}"></script>
@endsection