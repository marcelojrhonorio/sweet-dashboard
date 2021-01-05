@extends('layouts.app')

@section('title', 'Troca de Pontos')

@section('style')

    @include('exchanges.partials.styles')

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
          <h5>Editar Troca de Pontos</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="ibox float-e-margins">
            <form class="form-horizontal" action="#" method="post" id="form-create-campaign" enctype="multipart/form-data" data-exchange-edit-form>
                @include('exchanges.partials.form')
              <div class="form-group">
                <div class="col-md-12 text-right">
                  <button class="btn btn-primary" type="submit" id="update-campaign" name="create-campaign" data-btn-submit>Atualizar</button>
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

@section('script')
  <script src="{{ asset('assets/js/app/screen-exchanges-edit.js') }}"></script>
@endsection