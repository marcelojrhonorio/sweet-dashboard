<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-1">
            <label class="control-label" for="title">
                Título
            </label>
        </div>       
        <div class="col-md-5">  
            <select title="Selecione o Tipo..." name="title" id="title" class="selectpicker form-control" data-live-search="true" data-size="10" data-title>
                @foreach($types as $type)
                    <option   
                    @if(isset($data['title']) && (int)$type->id == $data['title'])
                        selected="selected"
                    @endif
                        value="{{ $type->id }}"
                        >
                        {{ $type->title }}
                    </option>
                @endforeach
            </select>       
        </div>         
        <div class="col-md-3">
            <input type="radio" class="radio-inline" name="codetype" id="incentive-email-type" value="1" @if(isset($data['codetype']) && ('1' == $data['codetype'])) checked="checked" @endif > <label for="incentive-email-type"> &nbsp; Usar Email Incentivado</label>                 
        </div>
        <div class="col-md-2">
            <input type="radio" class="radio-inline" name="codetype" id="research-type" value="2" @if(isset($data['codetype']) && ('2' == $data['codetype'])) checked="checked" @endif ><label for="research-type"> &nbsp; Usar Pesquisa</label> 
        </div> 
    </div>
</div>  
<br>

@if(isset($data['codetype']) && (1 == $data['codetype']))
    <div box-incentive-email>
@else
    <div class="sr-only" box-incentive-email>
@endif
    <div class="col-md-2">
        <label class="control-label" for="code_incentive_email">
            Email Incentivado
        </label>
    </div>
    <div class="col-md-4">  
        <select title="Selecione o email incentivado..." name="code_incentive_email" id="code_incentive_email" class="selectpicker form-control" data-live-search="true" data-size="10" code-incentive-email>
        @foreach($incentive_email as $incentive)
            <option value="{{ $incentive->code }}"
                @if(isset($data['code_incentive_email']) && $incentive->code == $data['code_incentive_email'])
                    selected="selected"
                @endif
                >
                {{ $incentive->title }}
            </option>
        @endforeach
        
        </select>       
    </div> 
</div>

@if(isset($data['codetype']) && (2 == $data['codetype']))
    <div box-research-type>
@else
    <div class="sr-only" box-research-type>
@endif
<div class="col-md-1">
    <label class="control-label" for="research_selected">
        Pesquisa
    </label>
    </div>
    <div class="col-md-5">  
        <select title="Selecione a pesquisa..." name="research_selected" id="research_selected" class="selectpicker form-control" data-live-search="true" data-size="10" data-research-selected>
        @foreach($researches as $research)
            <option value="{{ $research->id }}"
                @if(isset($data['research_selected']) && (int)$research->id == $data['research_selected'])
                    selected="selected"
                @endif
                >
              {{ $research->description }}
            </option>
        @endforeach
        
        </select>       
    </div> 
</div>
<div style="margin-top:3%"></div>
<br>
<div class="form-group ">
    <div class="col-md-12">
        <strong>Filtros</strong>
    </div>
</div>
<div class="form-group tooltip-filter">
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-1">
                <label for="filter_gender">Sexo</label>                
            </div>
            <div class="col-md-5">
            <select title="Selecione o sexo..." name="filter_gender" id="filter_gender" class="selectpicker form-control" data-size="10" filter_gender>
                @foreach(['A' => 'Todos', 'F' => 'Feminino', 'M' => 'Masculino'] as $key => $value)
                    <option value="{{ $key }}"  @if (!empty($data['filter_gender']) && $data['filter_gender'] == $key) {{ ' selected="selected" ' }} @endif  > {{ $value }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-4">
                <label for="filter_operation_begin">Operacao Inicial</label>
                <select title="Selecione..." name="filter_operation_begin" id="filter_operation_begin" class="selectpicker form-control" data-live-search="true" data-size="10" filter_operation_begin>
                    @foreach($operations as $key => $value)
                        <option value="{{ $key }}" @if (!empty($data['filter_operation_begin']) && $data['filter_operation_begin'] == $key) {{ ' selected="selected" ' }} @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter_age_begin">Idade Inicial</label>
                <input type="number" maxlength="2" min="0" max="99" id="filter_age_begin" name="filter_age_begin" class="input-sm form-control" value="{{ $data['filter_age_begin'] ?? null }}" filter_age_begin>
            </div> 
            <div class="block-end">
                <div class="col-md-4">
                    <label for="filter_operation_end">Operacao Final</label>
                    <select title="Selecione..." name="filter_operation_end" id="filter_operation_end" class="selectpicker form-control" data-live-search="true" data-size="10" filter_operation_end>
                        @foreach ($operations as $key => $value)
                            @if ($key == '<' || $key == '<=')
                            <option value="{{ $key }}" @if (!empty($data['filter_operation_end']) && $data['filter_operation_end'] == $key) {{ ' selected="selected" ' }} @endif >{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filter_age_end">Idade final</label>
                    <input type="number" maxlength="2" min="0" max="99" id="filter_age_end" name="filter_age_end" class="input-sm form-control" value="{{ $data['filter_age_end'] ?? null }}" filter_age_end>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-6">
                <label for="filter_ddd">DDD</label>
                <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador. Ex.: 11|15|21 "><i class="fa fa-question-circle"></i></span>
                <textarea id="filter_ddd" name="filter_ddd" class="input-sm form-control" filter_ddd>{{ $data['filter_ddd'] ?? null }}</textarea>
            </div>
            <div class="col-md-6">
                <label for="filter_cep">Cep</label>
                <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador. Ex.: 00.000-000|00.000-001|00.000-002 "><i class="fa fa-question-circle"></i></span>
                <textarea id="filter_cep" name="filter_cep" class="input-sm form-control" filter_cep>{{ $data['filter_cep'] ?? null }}</textarea>
            </div>
        </div>
     </div>
     <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label for="resume_filter">Total: </label>                
                <span> {{ $filter_users }} usuários. </span>
                <input type="hidden" value="{{ $filter_users }}" data-filter-users>
            </div>
            <div class="col-md-1">
                <div class="col-md-12 text-left">                                                                      
                    <button class="btn btn-primary" type="submit">
                    <i class="fas fa-sync-alt"></i>
                    </button>                                    
                </div>
            </div>            
        </div>
     </div>
</div>

<div class="form-group">
    <div class="col-md-2">
        <label class="control-label" for="scheduling">
            Enviar notificação:
        </label>
    </div>
    <div class="col-md-4">  
        <select title="Selecione o horário..." name="scheduling" id="scheduling" class="selectpicker form-control" data-size="10" data-scheduling>
            <option value="0" @if(isset($data['scheduling']) && ('0' == $data['scheduling'])) selected="selected" @endif > Imediatamente. </option>
            <option value="100" @if(isset($data['scheduling']) && ('100' == $data['scheduling'])) selected="selected" @endif > Daqui 10 minutos. </option>
            <option value="30" @if(isset($data['scheduling']) && ('30' == $data['scheduling'])) selected="selected" @endif > Daqui 30 minutos. </option>            
            @for($i = 1; $i <= 24; $i++)
                <option value="{{$i}}" @if(isset($data['scheduling']) && ($i == $data['scheduling'])) selected="selected" @endif > Daqui {{$i}} hora(s). </option>
            @endfor                      
        </select>       
    </div>  
</div>

<div class="sr-only">
  {{ csrf_field() }}  
  <input name="action" type="hidden" value="create" data-input-action>
  <input name="id" type="hidden" value="" data-input-id>
</div>
       


        
        
      