@extends('layouts.app')

@section('title', 'Cadastro de Campanhas ')

@section('style')

    @include('campaigns.includes.style')

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
                        <h5>Edição de Campanhas</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="ibox float-e-margins">
                            <form class="form-horizontal" action="{{ route('update.campaigns') }}" method="post" id="form-create-campaign" enctype="multipart/form-data">
                                @include('campaigns.includes.form')
                                <div class="form-group">
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" type="submit" id="update-campaign" name="create-campaign">Atualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@include('campaigns.includes.scripts')

