@extends('layouts.app')

@section('content') 
         
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
      @if(isset($indications))
        @foreach($indications as $indication)
            @php
                $bday = explode("-", $indication->birthdate);
                $birthdate = $bday[2] . '/' . $bday[1] . '/' . $bday[0];
            @endphp
            <tr>
                <td> {{ $indication->id }} </td>
                <td> {{ $indication->ip_address }} </td>
                <td> {{ $indication->fullname }} </td>
                <td> {{ $indication->email }} </td>
                <td> {{ $indication->cep }} </td>
                <td> {{ $indication->cpf }} </td>
                <td> {{ $indication->ddd }} </td>
                <td> {{ $indication->phone_number }} </td>
                <td> {{ $birthdate }} </td>
                <td> 
                    <select name="status" id="status" class="selectpicker form-control" data-id="{{$indication->id}}" data-status-change>   
                        @foreach(['1' => "&#9203;", '2' => "&#128309;", '3' => "&#128308;"] as $key => $value)
                            <option value="{{ $key }}"  @if (!empty($indication->status_indication) && $indication->status_indication == $key) {{ ' selected="selected" ' }} @endif  > {{ $value }}</option>
                        @endforeach  
                    </select>  
                </td>
            </tr>
        @endforeach
      @endif
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

@endsection

@section('script')
<script src="{{ asset('assets/js/app/customers.js') }}"></script>
@endsection