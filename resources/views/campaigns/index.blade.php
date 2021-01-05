@extends('layouts.app')

@section('title', 'Campanhas')

@section('style')

    <style type="text/css">
        #primary-key { display: none; }
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
                        <div class="ibox float-e-margins">
                            <div class="col-md-2">
                                <button class="btn btn-success btn-new-row" type="submit" id="search" name="search">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i> Adicionar Nova Campanha
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="ibox-content">
                        <div class="ibox float-e-margins">
                            <div class="">
                                <h5><strong>Filtros</strong></h5>
                            </div>
                            <form class="form-horizontal" action=" " method="post" name="form-search" id="form-search">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label for="nome">Nome</label>
                                        <input type="text" id="name" name="name" class="input-sm form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="id_has_offers">ID HO</label>
                                        <input type="text" id="id_has_offers" name="id_has_offers" class="input-sm form-control">
                                    </div>
                                    @if(!Session::has('userCompanies'))
                                    <div class="col-md-2">
                                        <label for="companies">Cliente</label>
                                        <select name="companies" id="companies" class="selectpicker form-control" data-live-search="true" data-size="8">
                                            <option value=""></option>
                                            @foreach($companies as $company)
                                                <option data-subtext="{{ $company->cnpj }}" data-tokens="{{ $company->nickname }} {{ $company->cnpj }}" value="{{ $company->id }}">{{ $company->nickname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-2">

                                        <label for="campaignsTypes">Tipo</label>
                                        <select name="campaignsTypes" id="campaignsTypes" class="selectpicker form-control">
                                            <option value=""></option>
                                            @foreach($campaignsType as $campaignType)
                                                <option value="{{ $campaignType->id }}">{{ $campaignType->type }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-1">
                                        <label for="status">Ativo</label>
                                        <select name="status" id="status" class="selectpicker form-control">
                                            <option value=""></option>
                                            <option value="1">Sim</option>
                                            <option value="2">Não</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 text-right">
                                        <label><br></label>
                                        <button class="btn btn-primary btn-block search" type="button" title="Pesquisar">
                                            <i class="fa fa-search" aria-hidden="true"></i> Pesquisar
                                        </button>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <label><br></label>
                                        <button class="btn btn-warning btn-block clear" type="button" title="Limpar campos de pesquisa">
                                            <i class="fa fa-eraser" aria-hidden="true"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="ibox-content" table-campaigns>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover campaigns-list" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Título</th>
                                        <th>Pergunta</th>
                                        <th>Imagem</th>
                                        <th>Ordem</th>
                                        <th>Ativo</th>
                                        <th>Desktop</th>
                                        <th>Mobile</th>
                                        <th>Ações</th>
                                        <th>Url Postback</th>
                                        <th>Configurar Página</th>
                                        <th>Configurar Email</th>
                                        <th>Visualizações</th>
                                        <th>Cliques</th>
                                        <th>ID HO</th>
                                        <th>Tipo</th>
                                        <th>Cliente</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Título</th>
                                        <th>Pergunta</th>
                                        <th>Imagem</th>
                                        <th>Ordem</th>
                                        <th>Ativo</th>
                                        <th>Desktop</th>
                                        <th>Mobile</th>
                                        <th>Ações</th>
                                        <th>Url Postback</th>
                                        <th>Configurar Página</th>
                                        <th>Configurar Email</th>
                                        <th>Visualizações</th>
                                        <th>Cliques</th>
                                        <th>ID HO</th>
                                        <th>Tipo</th>
                                        <th>Cliente</th>
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

    {{--@include('campaigns.includes.form')--}}

@endsection



@section('script')

<script src="{!! asset('assets/js/app/campaigns.js') !!}?{{date('hisdmY')}}"></script>

@endsection




