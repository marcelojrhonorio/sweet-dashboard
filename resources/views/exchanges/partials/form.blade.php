{{ csrf_field() }}

@php
  $year   = substr($customer_exchanged_point->created_at, 0, 4);
  $month  = substr($customer_exchanged_point->created_at, 5, 2);
  $day    = substr($customer_exchanged_point->created_at, 8, 2);
  $hour   = substr($customer_exchanged_point->created_at, 11, 2);
  $minute = substr($customer_exchanged_point->created_at, 14, 2);
  $full_created_at = $day . '/' . $month . '/' . $year . ' às ' . $hour . ':' . $minute;
@endphp
  
<input
  id="exchange_id"
  name="exchange_id"
  type="hidden"
  value="{{ $customer_exchanged_point->id }}"
  data-exchange-id
>

<input
  id="customer_points"
  name="customer_points"
  type="hidden"
  value="{{ $customer_exchanged_point->customer->points }}"
  data-customer-points
>

<input
  id="product_id"
  name="product_id"
  type="hidden"
  value="{{ $customer_exchanged_point->product_service->id }}"
  data-product-id
>

<input
  id="product_points"
  name="product_points"
  type="hidden"
  value="{{ $customer_exchanged_point->points }}"
  data-product-points
>

<div class="form-group">
  <div class="col-md-10">
    <strong>Dados da troca</strong>
  </div>
</div>

<div class="form-group">
  <input type="hidden" 
    value="{{ $customer_exchanged_point->created_at }}" 
    data-exchange-requested-at
  >
  <div class="col-md-2">
    <label>Solicitado em</label>
    <input
      id="exchange_requested_at"
      class="input-sm form-control"
      name="exchange_requested_at"
      type="text"
      value="{{ $full_created_at }}"
      disabled
    >
  </div>
  <div class="product-group" data-product-group>
    <div class="col-md-2">
      <label data-points-label>Pontos</label>
      <input
        id="exchange_points"
        class="input-sm form-control"
        name="exchange_points"
        type="number"
        value="{{ $customer_exchanged_point->points }}"
        disabled
        data-exchange-points
      >
    </div>  
    <div class="col-md-8">
      <label for="exchange_product" data-product-label>Produto</label>
      <select title="Selecione o produto..."
        name="exchange_product" 
        id="exchange_product" 
        class="selectpicker form-control" 
        data-live-search="true" 
        data-size="10"
        data-exchange-product>
        @foreach ($products_services as $product_service)
          @if ($customer_exchanged_point->product_service->id == $product_service->id)
            <option value="{{ $product_service->id }}" data-points="{{ $product_service->points }}" selected> {{ $product_service->title }} - {{ $product_service->points }} pts.</option>
          @else
            <option value="{{ $product_service->id }}" data-points="{{ $product_service->points }}"> {{ $product_service->title }} - {{ $product_service->points }} pts.</option>
          @endif
        @endforeach
      </select>
    </div>
  </div>
</div>

<br>
<hr>

<div class="form-group">
  <div class="col-md-10">
    <strong>Dados da entrega</strong>
  </div>
</div>

<div class="form-group">
  <div class="col-md-3">
    <label for="delivery_status">Status</label>
    <select title="Selecione o Status..." 
    name="delivery_status" 
    id="delivery_status" 
    class="selectpicker form-control" 
    data-live-search="true" 
    data-size="10"
    data-delivery-status>
    @foreach ($exchanged_points_status as $exchanged_point_status)
      @if ($exchanged_point_status->id == $customer_exchanged_point->status_id)
        <option value="{{ $exchanged_point_status->id }}" selected> {{ $exchanged_point_status->title }} </option>
      @else
        <option value="{{ $exchanged_point_status->id }}"> {{ $exchanged_point_status->title }} </option>
      @endif
    @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="sr-only" data-delivery-tracking-label>Código de rastreio</label>
    <input
      id="delivery_tracking_code"
      class="input-sm form-control sr-only"
      name="delivery_tracking_code"
      type="text"
      value="{{ $customer_exchanged_point->tracking_code }}"
      data-delivery-tracking
    >
  </div>
  <div class="col-md-3">
  <label class="sr-only" data-delivery-forecast-label>Previsão de entrega</label>
    <input
      id="delivery_forecast"
      class="input-sm form-control sr-only"
      name="delivery_forecast"
      type="text"
      value="{{ $customer_exchanged_point->delivery_forecast }}"
      data-delivery-forecast
    >  
  </div>
</div>

<div class="form-group">
  <div class="col-md-6">
    <label>Endereço</label>
    <input
      id="delivery_address"
      class="input-sm form-control"
      name="delivery_address"
      type="text"
      value="{{ $customer_exchanged_point->address }}"
      data-delivery-address
    >
  </div>
  <div class="col-md-2">
    <label>Número</label>
    <input
      id="delivery_number"
      class="input-sm form-control"
      name="delivery_number"
      type="text"
      value="{{ $customer_exchanged_point->number }}"
      data-delivery-number
    >
  </div>
  <div class="col-md-4">
    <label>Ponto de referência</label>
    <input
      id="delivery_reference_point"
      class="input-sm form-control"
      name="delivery_reference_point"
      type="text"
      value="{{ $customer_exchanged_point->reference_point }}"
      data-delivery-reference-point
    >
  </div>    
</div>

<div class="form-group">
  <div class="col-md-3">
    <label>Bairro</label>
    <input
      id="delivery_neighborhood"
      class="input-sm form-control"
      name="delivery_neighborhood"
      type="text"
      value="{{ $customer_exchanged_point->neighborhood }}"
      data-delivery-neighborhood
    >
  </div>
  <div class="col-md-3">
    <label>Complemento</label>
    <input 
      id="delivery_complement"
      class="input-sm form-control"
      name="delivery_complement"
      type="text"
      value="{{ $customer_exchanged_point->complement }}"
      data-delivery-complement
    >
  </div>  
  <div class="col-md-2">
    <label>Cidade</label>
    <input
      id="delivery_city"
      class="input-sm form-control"
      name="delivery_city"
      type="text"
      value="{{ $customer_exchanged_point->city }}"
      data-delivery-city
    >
  </div>
  <div class="col-md-2">
    <label for="delivery_state">UF</label>
    <select title="Selecione o Estado..." 
    name="delivery_state" 
    id="delivery_state" 
    class="selectpicker form-control" 
    data-live-search="true" 
    data-size="10"
    data-delivery-state>
    @foreach ($states as $state)
      @if ($customer_exchanged_point->state == $state)
        <option value="{{ $state }}" selected> {{ $state }} </option>
      @else
        <option value="{{ $state }}"> {{ $state }} </option>
      @endif
    @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <label>CEP</label>
    <input
      id="delivery_postal_code"
      class="input-sm form-control"
      name="delivery_postal_code"
      type="text"
      value="{{ $customer_exchanged_point->cep }}"
      data-delivery-postal-code
    >
  </div> 
</div>


<div class="form-group">
  <div class="col-md-12">
    <label>Informações adicionais</label>
    <textarea class="form-control" rows="5" id="delivery_additional_information" data-delivery-additional-infomation>
      {{ $customer_exchanged_point->additional_information }}
    </textarea>
  </div>
</div>

<br>
<br>

<div class="form-group">
  <ul class="nav nav-tabs">
    <li class="active" style="font-size: 15px;" data-customer-data><a href="">Dados do usuário</a></li>
    <li class="" style="font-size: 15px;" data-customer-history><a href="">Histórico de Pontos</a></li>
    <li class="" style="font-size: 15px;" data-customer-indications><a href="">Indicações de usuários</a></li>
  </ul>
</div>

<div class="customer-data" data-customer-data-group>
  <div class="form-group">
    <div class="col-md-2">
      <label>ID</label>
      <input
        id="customer_id"
        class="input-sm form-control"
        name="customer_id"
        type="text"
        value="{{ $customer_exchanged_point->customer->id }}"
        disabled
        data-customer-id
      >
    </div>
    <div class="col-md-5">
      <label>E-mail</label>
      <input
        id="customer_email"
        class="input-sm form-control"
        name="customer_email"
        type="email"
        value="{{ $customer_exchanged_point->customer->email }}"
        disabled
        data-customer-email
      >
    </div>
    <div class="col-md-5">
      <label>Nome completo</label>
      <input
        id="customer_fullname"
        class="input-sm form-control"
        name="customer_fullname"
        type="text"
        value="{{ $customer_exchanged_point->customer->fullname }}"
        data-customer-fullname
        disabled
      >
    </div>
  </div>

  <div class="form-group">
    <div class="col-md-3">
      <label>Nascimento</label>
      <input
        id="customer_birthdate"
        class="input-sm form-control"
        name="customer_birthdate"
        type="text"
        value="{{ $customer_exchanged_point->customer->birthdate }}"
        disabled
      >
    </div>
    <div class="col-md-3">
      <label>CPF</label>
      <input
        id="customer_cpf"
        class="input-sm form-control"
        name="customer_birthdate"
        type="text"
        value="{{ $customer_exchanged_point->customer->cpf }}"
        disabled
      >
    </div>
    <div class="col-md-2">
      <label>DDD</label>
      <input
        id="customer_phone_number_ddd"
        class="input-sm form-control"
        name="customer_phone_number_ddd"
        type="text"
        value="{{ $customer_exchanged_point->customer->ddd }}"
        data-customer-phone-number-ddd
      > 
    </div>
    <div class="col-md-4">
      <label>Celular</label>
      <input
        id="customer_phone_number"
        class="input-sm form-control"
        name="customer_phone_number"
        type="text"
        value="{{ $customer_exchanged_point->customer->phone_number }}"
        data-customer-phone-number
      >  
    </div>
  </div>
</div>

<div class="customer-history sr-only" data-customer-history-group>
  <div class="form-group">
    <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-points-list>
      <thead>
        <tr>
          <th>Descrição</th>
          <th>Quantidade de Ações</th>
          <th>Total de Pontos</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

<div class="sr-only" data-indications>
  <div class="form-group">
  <a class="btn btn-primary" style="margin-left:86.5%" href="/customers/indications/{{ $customer_exchanged_point->customer->id }}/1" target="_blank">Editar Indicações</a>
  <div class="table-responsive">
  <table class="table table-striped table-bordered table-hover content-table" style="width: 100%" data-table-indications>
    <thead>
      <tr>
        <th>#</th>
        <th>Endereço IP</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>CEP</th>
        <th>CPF</th>
        <th>DDD</th>
        <th>Telefone</th>
        <th>Aniversário</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
     
    </tbody>
    <tfoot>
      <tr>
        <th>#</th>
        <th>Endereço IP</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>CEP</th>
        <th>CPF</th>
        <th>DDD</th>
        <th>Telefone</th>
        <th>Aniversário</th>
        <th>Status</th>
      </tr>
    </tfoot>
  </table>
  </div>
  </div>
</div>