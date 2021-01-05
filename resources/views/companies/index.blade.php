@extends('layouts.app')

@section('title', 'Empresas')

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
                                <button class="btn btn-success btn-new" type="button" id="new" name="new">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i> Adicionar Nova Empresa
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="ibox-content">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover companies-list" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>CNPJ</th>
                                        <th>Razão Social</th>
                                        <th>Ativo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>CNPJ</th>
                                        <th>Razão Social</th>
                                        <th>Ativo</th>
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

@include('companies.includes.form')

@endsection



@section('script')

<script src="{!! asset('assets/js/app/companies.js') !!}?{{time()}}"></script>

@endsection
