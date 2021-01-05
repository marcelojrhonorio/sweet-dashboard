@extends('layouts.app')

@section('title', 'Notificações do App Mobile')

@section('style')

@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>@yield('title')</h2>
        </div>
    </div>
    <input name="sweetmedia" type="hidden" value="{{ env('APP_URL') }}" data-input-sweetmedia>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Cadastro de Notificação do App</h5>                        
                    </div>                    
                    <div class="ibox-content" id="data-box-notification">                    
                        <div class="ibox float-e-margins">
                            <form class="form-horizontal" action="/app-notification/store" method="post" enctype="multipart/form-data">
                                @include('app_mobile.includes.form-notification')                                
                            </form>
                            <div class="form-group">   
                                <div class="col-md-12 text-right">    
                                    <button class="btn btn-danger" title="Cancelar" btn-cancel-notification>
                                        Cancelar
                                    </button>       
                                    <button title="Enviar Teste" class="btn btn-warning" data-btn-send-test>
                                        <i class="fas fa-flask"></i>                                   
                                    </button>                                                                
                                    <button class="btn btn-success" title="Enviar Push" data-btn-app-send>
                                        <i class="fas fa-paper-plane"></i>
                                    </button>   
                                    <button class="btn btn-primary" title="Salvar" data-btn-app-save>
                                        <i class="fas fa-save"></i>
                                    </button>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/app/app-notification.js') }}"></script>
@endsection