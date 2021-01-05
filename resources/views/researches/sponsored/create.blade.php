@extends('layouts.app')

@section('title', 'Pesquisas Patrocinadas')

@section('style')

@endsection

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>@yield('title')</h2>
        </div>
    </div>
    <input name="val-order-edit" type="hidden" value="" val-order-edit>
    <input name="research" type="hidden" value="" data-input-research>
    <input name="type_action" type="hidden" value="" data-type-action>
    <input name="edit-order" type="hidden" value="0" data-edit-order>
    <input name="research-question" type="hidden" value="" data-research-question>
    <input name="sweetmedia" type="hidden" value="{{ env('APP_URL') }}" data-input-sweetmedia>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">


                    <div class="ibox-title">
                        <h5>Pesquisa</h5>
                        @if($action == 'edit')                            
                            <a class="btn btn-primary btn-edit-reseach-data" style="float:right;margin-top:-1%;" type="submit" btn-research-edit> Editar </a> 
                        @endif
                    </div>
                    @if($action == 'create')
                        <div class="ibox-content" id="data-box-research">
                    @else
                        <div class="ibox-content" style="display:none" id="data-box-research">                                         
                    @endif
                    
                        <div class="ibox float-e-margins">
                            <form class="form-horizontal" data-form-sponsored>
                                @include('researches.sponsored.includes.form-research')
                                <div class="form-group">                               
                                    <div class="col-md-12 text-right">
                                    @if($action == 'edit')
                                        <a class="btn btn-default btn-cancel-research-edit" style="margin-top:3%" type="button" cancel-research-edit> Cancelar </a>
                                    @endif                                    
                                    <button class="btn btn-primary" type="submit" style="margin-top:3%" data-btn-research>
                                        @if($action == 'create')
                                            Próxima etapa
                                        @else 
                                           Atualizar 
                                        @endif
                                    </button>
                                    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($action == 'edit')       
                        <div id="box-question">
                    @else 
                        <div class="sr-only" id="box-question">
                    @endif
                        <div class="ibox-title">
                            <h5>Questões</h5> 
                            @if($action == 'edit')                            
                                <a class="btn btn-primary btn-edit-reseach-data" style="float:right;margin-top:-1%;" type="submit" btn-question-edit> Editar </a> 
                            @endif                            
                        </div>
                            @if($action == 'create')
                                <div class="ibox-content" id="data-box-question">
                            @else
                                <div class="ibox-content" style="display:none" id="data-box-question">                                         
                            @endif                      
                            @include('researches.sponsored.includes.question')
                            <div class="form-group" style="margin-bottom:47px">
                                <div class="col-md-12 text-right">
                                    @if($action == 'edit')
                                        <a class="btn btn-default edit-question" style="margin-top:2%" type="button" cancel-question-edit> Cancelar </a>
                                    @endif                                    
                                    <button class="btn btn-primary" id="btn-finish-questions" type="submit" btn-finalizar-question>
                                        @if($action == 'create')
                                            Próxima etapa
                                        @else 
                                            Atualizar 
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>                    
                    </div>
                    

                    @if($action == 'edit')       
                        <div id="box-middle-page">
                    @else 
                        <div class="sr-only" id="box-middle-page">
                    @endif                    
                        <div class="ibox-title">
                            <h5>Página Intermediária</h5>
                            @if($action == 'edit')                            
                                <a class="btn btn-primary btn-edit-reseach-data" style="float:right;margin-top:-1%;" type="submit" btn-middlepage-edit> Editar </a> 
                            @endif
                        </div>
                            @if($action == 'create')
                                <div class="ibox-content" id="data-box-middle-page">
                            @else
                                <div class="ibox-content" style="display:none" id="data-box-middle-page">                                         
                            @endif                        
                            
                            @include('researches.sponsored.includes.middle-page')
                            <div class="form-group">
                                <div class="col-md-12 text-right">
                                    @if($action == 'edit')
                                        <a class="btn btn-default cancel-edit-question" type="button" cancel-middlepage-edit> Cancelar Edição </a>
                                        <a class="btn btn-primary finish-edit-middle" type="button" finish-middlepage-edit> Finalizar Edição </a>
                                    @endif  
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
  <script src="{{ asset('assets/js/app/sponsored_researches/researches.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/middlepages.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/questions.js') }}"></script>
@endsection
