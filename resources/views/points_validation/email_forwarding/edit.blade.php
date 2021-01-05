@extends('layouts.app')

@section('title', 'Encaminhamento de e-mail')

@section('content')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>@yield('title')</h2>
    </div>
  </div>
  <input type="hidden" value="{{env('STORE_URL')}}" data-store-url>
  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h5>Emails encaminhados por <strong> {{ $datas[0]['name'] }} </strong></h5>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-table-validation-email>
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Prints</th>
                    <th style="width:8%">Ações</th>
                  </tr>
                </thead>
                <tbody>
                    @if(isset($datas))
                        @foreach($datas as $data)
                            
                                @foreach($data['customersForwardingEmail'] as $cfe)
                                <tr role="row" class="odd">
                                   <td> {{ $cfe['name'] }} </td>
                                   <td> {{ $cfe['email'] }} </td>
                                   <td> 
                                      @if(!$cfe['status'])
                                        Em análise
                                      @else
                                        Verificado
                                      @endif
                                  </td>
                                 
                                  <td>
                                  @foreach($data['customersForwardingPrint'] as $cfp)                                   
                                    <a data-print="{{ $cfp['image'] }}" title="Clique aqui para ver o print" data-prints-forwarding> <i class="fas fa-eye"></i></a> 
                                  @endforeach  
                                  </td>

                                  <td>
                                    <button
                                      class="btn btn-xs not-selected"
                                      title="Validar encaminhamento"
                                      type="button"
                                      data-btn-forwarding-ok
                                      data-id="{{$cfe['id']}}"
                                      data-customer="{{ $datas[0]['customers_id'] }}"
                                    >
                                      <span class="sr-only">Validar</span>
                                      <i class="fas fa-user-check" aria-hidden="true"></i>
                                    </button>
                                    <button
                                      class="btn btn-xs ok-selected"
                                      title="Invalidar encaminhamento"
                                      type="button"
                                      data-btn-forwarding-not
                                      data-id="{{$cfe['id']}}"
                                      data-customer="{{ $datas[0]['customers_id'] }}"
                                    >
                                      <span class="sr-only">Invalidar</span>
                                      <i class="fas fa-user-times" aria-hidden="true"></i>
                                    </button>
                                  </td>
                                </tr> 
                                @endforeach                                                          
                        @endforeach                                  
                    @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Prints</th>
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

  @include('points_validation.email_forwarding.modal-prints')
  
@endsection

@section('script')
    <script src="{{ asset('assets/js/app/points-validation/email-forwarding.js') }}"></script>
@endsection
