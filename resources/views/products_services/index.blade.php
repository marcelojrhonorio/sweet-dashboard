@extends('layouts.app')

@section('title', 'Empresas')

@section('style')

{{--<link href="{!! asset('assets/css/plugins/uploadfile/uploadfile.css') !!}" type="text/css" rel="stylesheet" >--}}
<style type="text/css">
    #primary-key { display: none; }
    .progress, .bonus-image { display: none; }
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
                            <div class="">
                                <div class="col-md-8">
                                    <button class="btn btn-success btn-new" type="button" id="new" name="new">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Adicionar Novo Produto/Serviço
                                    </button>

                                    {{--<button class="btn btn-default btn-new-category" type="button" id="new" name="new">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Adicionar Nova Categoria
                                    </button>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ibox-content">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover content-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Título</th>
                                        <th>Descrição</th>
                                        <th>Pontução</th>
                                        <th>Foto</th>
                                        <th>Ativo</th>
                                        <th>Trocas Realizadas</th>
                                        <th>Usuários Diferentes</th>
                                        <th>Total de Pontos Trocados</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Título</th>
                                        <th>Descrição</th>
                                        <th>Pontução</th>
                                        <th>Foto</th>
                                        <th>Ativo</th>
                                        <th>Trocas Realizadas</th>
                                        <th>Usuários Diferentes</th>
                                        <th>Total de Pontos Trocados</th>
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

@include('products_services.includes.form')

@endsection



@section('script')

    <script type="text/javascript">
        /**
         * remove dreprecated xhr
         */
        $.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });
    </script>
    <script type="text/javascript" src="{!! asset('assets/js/app/images.sweet.js') !!}?{{time()}}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/plugins/uploadfile/uploadfile.js') !!}?{{time()}}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/app/products-services.js') !!}?{{time()}}"></script>

@endsection

