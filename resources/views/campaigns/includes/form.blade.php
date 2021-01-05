{{ csrf_field() }}

@if (isset($campaign->id))
  <input
    id="campaign_id"
    name="campaign_id"
    type="hidden"
    value="{{ $campaign->id }}"
  >
@endif

<div class="form-group">
  <div class="col-md-10">
    <strong>Dados da oferta</strong>
  </div>
  <div class="col-md-2">
    <label>Estado:</label>
    <label class="switch">
      @if (isset($edit) && $edit === true)
        <input
          id="campaign-active"
          type="checkbox"
          value="{{ $campaign->status ?? 1 }}"
          @if($campaign->status == 1)
            checked
          @endif
        >
      @else
        <input type="checkbox" checked>
      @endif
      <span class="slider round"></span>
    </label>
  </div>
</div>

<div class="form-group">
  <div class="col-md-2">
    <label>Ordem de exibição:</label>
    <input
      id="order"
      class="input-sm form-control"
      name="order"
      type="number"
      value="{{ $campaign->order ?? $order }}"
    >
  </div>
  <div class="col-md-2">
  @if(isset($campaign))
    <a href="{{env('SWEETBONUS_URL')}}/campaigns/from-dashboard/{{ $campaign->id ?? 0 }}" class="btn btn-primary customer-preview-campaign" target="_blank">Ver Preview</a>
  @else
    <a class="btn btn-primary customer-preview-campaign" disabled target="_blank">Ver Preview</a>
  @endif
  </div>
</div>

<div class="form-group">
  <div class="col-md-10">
    <label>Nome:</label>
    <input
      id="name"
      class="input-sm form-control"
      name="name"
      type="text"
      value="{{ $campaign->name ?? null }}"
    >
  </div>
  <div class="col-md-2">
    <label>ID Has Offers:</label>
    <input
      id="id_has_offers"
      class="input-sm form-control"
      name="id_has_offers"
      type="text"
      value="{{ $campaign->id_has_offers ?? null }}"
    >
  </div>
</div>

<div class="form-group">
  <div class="col-md-6">
    <label for="companies">Cliente</label>
    <select title="Selecione Cliente..." name="companies" id="companies" class="selectpicker form-control" data-live-search="true" data-size="10">
      @foreach($companies as $company)
        @if(Session::has('userCompanies') && in_array($company->id, Session::get('userCompanies')))
          <option
            @if(isset($campaign->companies_id) && (int)$campaign->companies_id == $company->id)
              selected="selected"
            @endif
            data-subtext="{{ $company->cnpj }}"
            data-tokens="{{ $company->nickname }} {{ $company->cnpj }}"
            value="{{ $company->id }}"
          >
            {{ $company->nickname }}
          </option>
        @else
          <option
            @if(isset($campaign->companies_id) && (int)$campaign->companies_id == $company->id)
              selected="selected"
            @endif
            data-subtext="{{ $company->cnpj }}"
            data-tokens="{{ $company->nickname }} {{ $company->cnpj }}"
            value="{{ $company->id }}"
          >
            {{ $company->nickname }}
          </option>
        @endif
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label for="clusters">Clusters</label>
    <select
      id="clusters"
      class="selectpicker form-control"
      name="clusters[]"
      title="Selecione Cluster(s)..."
      data-live-search="true"
      data-selected-text-format="count > 3"
      data-size="10"
      data-actions-box="true"
      multiple
    >
      @foreach($clusters as $cluster)
        <option
          value="{{ $cluster->id }}"
          @if (isset($clustersCheck) && in_array($cluster->id, $clustersCheck))
            selected="selected"
          @endif
        >
          {{ $cluster->cluster }}
        </option>
      @endforeach
    </select>
  </div>
</div>

<div class="form-group">
  <div class="col-md-12">
    <strong>Tipo de pergunta</strong>
  </div>
</div>
<div class="form-group">
  <div class="col-md-12 campaign-types">
    @if (isset($campaign->campaign_types_id) && $campaign->campaign_types_id)
      <input
        id="campaign_types_id"
        name="campaign_types_id"
        type="hidden"
        value="{{ $campaign->campaign_types_id }}"
      >
    @endif
    @foreach($types as $type)
      <input
        type="radio"
        class="radio-inline icheckbox"
        name="campaigntypes"
        id="campaigntypes-{{ $type->id }}"
        value="{{ $type->id }}"
        data-type="{{ $type->type }}"
        @if(isset($campaign->campaign_types_id) && (int)$campaign->campaign_types_id == $type->id)
          checked="checked"
        @endif
      >
      {{ $type->type }}
    @endforeach
  </div>
</div>

<div class="form-group">
  <div class="col-md-6">
    <label>Imagem da Oferta (Medium Rectangle 500x260 / Large Rectangle 536X290)</label>
    @set('display', 'block')
    @if (isset($campaign->path_image) && $campaign->path_image && file_exists(storage_path('app/public/' . $campaign->path_image)))
      @set('display', 'none')
      <div id="image-campaign" style="background-color: #efefef; max-height: 280px;">
        <img src="{{ env('APP_IMAGE_CAMPAIGN_URL') . '/' . $campaign->path_image}}?{{ time() }}">
        <div class="text-right " style="position: relative; right: 20px; top: -50px;">
          <button type="button" class="btn btn-danger delete-image" data-path="{{ $campaign->path_image }}">Excluir</button>
        </div>
      </div>
    @endif
    <div style="display: {{$display}}" class="fileUploader">Upload</div>
  </div>

  @php    
    $txt = "Negrito - <b> Seu texto </b> 
Itálico - <i>  Seu texto  </i>
Sublinhado - <u>  Seu texto  </u>
Tachado - <del>  Seu texto  </del>";
  @endphp

  <div class="col-md-6">
    <label>Título:</label>
    <span data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $txt }}"><i class="fa fa-question-circle"></i></span>
    <input type="text" id="title" name="title" class="input-sm form-control" value="{{ $campaign->title ?? null }}">
  </div>

  <div class="col-md-6">
    <label>Pergunta:</label>
    <span data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $txt }}"><i class="fa fa-question-circle"></i></span>
    <textarea id="question" name="question" class="input-sm form-control">{{ $campaign->question ?? null }}</textarea>
  </div>
</div>

<div class="type">
  <div class="form-group">
    <div class="col-md-12">
      <strong class="type-text"></strong>
    </div>
  </div>

  <hr>

@if (isset($edit) && $edit === true)
  <div class="form-group" id="group-config-coreg">
    {{-- @set('top', 35) --}}

    <div class="col-md-12 text-right">
      <button class="btn btn-primary new-answers" type="button">
        <span class="fa fa-plus-circle" aria-hidden="true"></span>
        Adicionar
      </button>
    </div>

    <div class="col-md-12">
      <table class="table table-striped table-bordered" id="table-clickout">
        <thead>
          <tr>
            <th>#</th>
            <th>Resposta</th>
            <th>Afirmativo</th>
            <th>Link</th>
            <th class="tabledit-toolbar-column"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clickout as $values)
            <tr>
              <td>{{ $values['id'] }}</td>
              <td>{{ $values['answer'] ?? null }}</td>
              <td>
                @if( (int) $values['affirmative'])
                  Sim
                @else
                  Não
                @endif
              </td>
              <td>
                {{ $values['link'] ?? null }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="col-md-12 text-right btn-save-all">
      <button class="btn btn-warning btn-save-news-answers" type="button" title="Salvar todos">
        <span class="fa fa-save" aria-hidden="true"></span>
        Salvar todos
      </button>
    </div>
@else
  <div class="form-group bg-muted p-h-md" id="group-config-coreg">
    <div>
      <div class="col-md-5">
          <label>Resposta</label>
          <input type="text" id="campaigns_clickout_answer" name="campaigns_clickout_answer[]" class="input-sm form-control" />
      </div>
      <div class="col-md-1" style="top: 25px;">
          <input type="checkbox" id="campaigns_clickout_affirmative" name="campaigns_clickout_affirmative[]" class="checkbox icheckbox" value="1" /><label>Sim?</label>
      </div>
      <div class="col-md-5">
          <label>Link</label>
          <input type="text" id="campaigns_clickout_link" name="campaigns_clickout_link[]" class="input-sm form-control" placeholder="http://" />
          <a href="javacript:void(0);" class="btn btn-primary add-inputs-config" style="position: absolute; float: right; right: -50px; top: 20px;"><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span></a>
      </div>
    </div>
@endif

  </div>

    <div class="form-group coreg-simples">
        <div class="col-md-5">
            <label for="postback_url">URL Postback:</label>
            <input type="text" id="postback_url" name="postback_url" class="input-sm form-control" value="{{ $campaign->postback_url ?? null }}">
        </div>
    </div>
</div>


{{-- <Fields metadata> --}}
<div class="fields-metadata sr-only" data-catch-inputs-container>
  @if (false === empty($edit))
    @include('campaigns.includes.catch-inputs-edit')
  @else
    @include('campaigns.includes.catch-inputs-create')
  @endif
</div>
{{-- </Fields metadata> --}}


<div class="form-group">
    <div class="col-md-12">
        <strong>Subdomínios:</strong>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
    <div class="col-md-8 scrollbox border-top-bottom border-left-right border-size-sm">

        @set('counterTd', 0)
        <table class="table table-striped">
            <tr>
                @foreach($domains as $domain)
                    @set('counterTd', $counterTd + 1)
                    <td><input type="checkbox" class="ckeckbox-inline icheckbox" name="domains[]" id="domains-{{ $domain->id }}" value="{{ $domain->id }}" data-domain="{{ $domain->link }}"  @if (isset($domainsCheck) && in_array( $domain->id, $domainsCheck)) {{ ' checked="checked" ' }} @endif /> <label for="domains-{{ $domain->id }}">{{ $domain->link }}</label></td>
                    @if ($counterTd == 3)
                    </tr><tr>
                    @set('counterTd', 0)
                    @endif
                @endforeach
            </tr>
        </table>
    </div>
    </div>
</div>

<hr />

<div class="form-group ">
    <div class="col-md-12">
        <strong>Filtros</strong>
    </div>
</div>
<div class="form-group tooltip-filter">
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label for="filter_gender">Sexo</label>
                <select title="Selecione o sexo..." name="filter_gender" id="filter_gender" class="selectpicker form-control" data-live-search="true" data-size="10">
                    @foreach(['A' => 'Todos', 'F' => 'Feminino', 'M' => 'Masculino'] as $key => $value)
                        <option value="{{ $key }}" @if (!empty($campaign->filter_gender) && $campaign->filter_gender == $key) {{ ' selected="selected" ' }} @endif  >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-2">
                <label for="filter_operation_begin">Operacao Inicial</label>
                <select title="Selecione..." name="filter_operation_begin" id="filter_operation_begin" class="selectpicker form-control" data-live-search="true" data-size="10">
                    @foreach($operations as $key => $value)
                        <option value="{{ $key }}" @if (!empty($campaign->filter_operation_begin) && $campaign->filter_operation_begin == $key) {{ ' selected="selected" ' }} @endif >{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label for="filter_age_begin">Idade Inicial</label>
                <input type="number" maxlength="2" min="0" max="99" id="filter_age_begin" name="filter_age_begin" class="input-sm form-control" value="{{ $campaign->filter_age_begin ?? null }}">
            </div>
            <div class="block-end">
                <div class="col-md-2">
                    <label for="filter_operation_end">Operacao Final</label>
                    <select title="Selecione..." name="filter_operation_end" id="filter_operation_end" class="selectpicker form-control" data-live-search="true" data-size="10">
                        @foreach ($operations as $key => $value)
                            @if ($key == '<' || $key == '<=')
                            <option value="{{ $key }}" @if (!empty($campaign->filter_operation_end) && $campaign->filter_operation_end == $key) {{ ' selected="selected" ' }} @endif  >{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="filter_age_end">Idade final</label>
                    <input type="number" maxlength="2" min="0" max="99" id="filter_age_end" name="filter_age_end" class="input-sm form-control" value="{{ $campaign->filter_age_end ?? null }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label for="filter_ddd">DDD</label>
                <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador.  <br /> Ex.: 11|15|21 "><i class="fa fa-question-circle"></i></span>
                <textarea id="filter_ddd" name="filter_ddd" class="input-sm form-control">{{ $campaign->filter_ddd ?? null }}</textarea>
            </div>
            <div class="col-md-3">
                <label for="filter_cep">Cep</label>
                <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador.  <br /> Ex.: 00.000-000|00.000-001|00.000-002 "><i class="fa fa-question-circle"></i></span>
                <textarea id="filter_cep" name="filter_cep" class="input-sm form-control">{{ $campaign->filter_cep ?? null }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-1">
        <strong>Mostrar em:</strong>
    </div>
    <div class="col-md-1">
        <input type="checkbox" class="icheckbox" name="desktop" id="desktop" value="1" @if(isset($campaign->desktop) && (bool)$campaign->desktop) {!! ' checked="checked" ' !!} @endif> Desktop
    </div>
    <div class="col-md-1">
        <input type="checkbox" class="icheckbox" name="mobile" id="mobile" value="1" @if(isset($campaign->mobile) && (bool)$campaign->mobile) {!! ' checked="checked" ' !!} @endif> Mobile
    </div>
    <div class="col-md-1">
        <input type="checkbox" class="icheckbox" name="actions" id="actions" value="1" @if(isset($campaign->actions) && (bool)$campaign->actions) {!! ' checked="checked" ' !!} @endif> Ações
    </div>    
</div>

<br>

<button type="button" class="btn btn-secondary" data-btn-actions-down><i class="fas fa-chevron-circle-down"></i></button>
<button type="button" class="btn btn-secondary sr-only" data-btn-actions-up><i class="fas fa-chevron-circle-up"></i></button>

<span>Dados de ações</span>
@if((isset($campaign->actions) && $campaign->actions) && $campaign->actions_id)
  <div class="form-actions-wrapper border-top-bottom border-left-right border-size-sm" style="padding:10px" data-actions-form-wrapper>
@else
  <div class="form-actions-wrapper sr-only border-top-bottom border-left-right border-size-sm" style="padding:10px" data-actions-form-wrapper>
@endif
  <div class="form-group">
    <label class="control-label col-md-2" for="link-to-action">Vincular à ação <span data-toggle="tooltip" data-placement="top" data-html="true" title="Selecione uma ação que deseja vincular."><i class="fa fa-question-circle"></i></span></label>
    <div class="col-md-9">
      <input type="checkbox" name="link-to-action" class="form-check-input" id="link-to-action" @if(isset($actionCampaign->id) && (bool)$campaign->actions) {!! ' checked="checked" ' !!} @endif>
    </div>
  </div>

  @php 
    $class = 'sr-only'; 
    if(isset($actionCampaign->id) && (bool)$campaign->actions) {
      $class = '';
    }
  @endphp
  
  <div class="form-group {{$class}}" data-select-action>
    <label class="control-label col-md-2" for="link-to-action">Ação: </label>
    <div class="col-md-9">
      <select id="link_action" class="form-control selectpicker" name="link_action" title="Selecione uma ação..." data-live-search="true" data-size="10" data-input-action>
      @foreach($actions as $action)
          <option value="{{ $action->id }}" @if (!empty($campaign->actions_id) && $campaign->actions_id == $action->id) {{ ' selected="selected" ' }} @endif >{{ $action->title }}</option>
      @endforeach
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-2" for="action-category">
      Categoria:
    </label>
    <div class="col-md-9">
      <select
        id="action-category"
        class="form-control selectpicker"
        name="action-category"
        title="Selecione uma categoria..."
        data-input-category
        data-live-search="true"
        data-size="10"
      >
        @forelse ($action_categories as $category)
          <option value="{{ $category->id }}"
          @if (!empty($actionCampaign->action_category_id) && $actionCampaign->action_category_id == $category->id) {{ ' selected="selected" ' }} @endif >
            {{ $category->name }}
          </option>
        @empty
          <option value="-1">
            Sem categorias
          </option>
        @endforelse
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-2" for="action-type">
      Tipo:
    </label>
    <div class="col-md-9">
      <select
        id="action-type"
        class="form-control selectpicker"
        name="action-type"
        title="Selecione um tipo..."
        data-live-search="true"
        data-size="10"
        data-input-type
      >
        @forelse ($action_types as $type)
          <option value="{{ $type->id }}"  @if (!empty($actionCampaign->action_type_id) && $actionCampaign->action_type_id == $type->id) {{ ' selected="selected" ' }} @endif >
            {{ $type->name }}
          </option>
        @empty
          <option value="-1">
            Sem tipos
          </option>
        @endforelse
      </select>
    </div>
  </div>
  <div class="form-group" data-wrap-type-url>
    <label class="control-label col-md-2" for="action-url">
      URL:
    </label>
    <div class="col-md-9">
    @if(!empty($actionTypeMeta->action_id)) 
      <input
        id="action-url"
        class="form-control"
        name="action-url"
        type="text"
        placeholder="URL"
        value="{{ $actionTypeMeta->value }}"
        data-input-type-url
      >
      @else
      <input
        id="action-url"
        class="form-control"
        name="action-url"
        type="text"
        placeholder="URL"
        value=""
        data-input-type-url
      >
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-2" for="action-title">
      Título:
    </label>
    <div class="col-md-9">
    @if(!empty($actionCampaign->title)) 
      <input
        id="action-title"
        class="form-control"
        name="action-title"
        type="text"
        placeholder="Título"
        value="{{ $actionCampaign->title }}"
        data-input-title
      >
    @else 
      <input
          id="action-title"
          class="form-control"
          name="action-title"
          type="text"
          placeholder="Título"
          value=""
          data-input-title
        >
    @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-2" for="action-description">
      Descrição:
    </label>
    <div class="col-md-9">
    @if(!empty($actionCampaign->title)) 
      <input
        id="action-description"
        class="form-control"
        name="action-description"
        type="text"
        placeholder="Descrição"
        value=" {{ $actionCampaign->description }}"
        data-input-description
      >
    @else 
      <input
        id="action-description"
        class="form-control"
        name="action-description"
        type="text"
        placeholder="Descrição"
        value=""
        data-input-description
      >
    @endif
    </div>
  </div>
 
  <div class="form-group" data-wrap-points>
    <label class="control-label col-md-2" for="action-points">
      Pontos:
    </label>
    <div class="col-md-9">
    @if(isset($actionCampaign->grant_points)) 
      <input
        id="action-points"
        class="form-control"
        name="action-points"
        type="number"
        placeholder="Pontos"
        value="{{ $actionCampaign->grant_points }}"
        data-input-points
      >
    @else 
      <input
        id="action-points"
        class="form-control"
        name="action-points"
        type="number"
        placeholder="Pontos"
        value=""
        data-input-points
      >
    @endif
    </div>
  </div>
  <div class="form-group">
  <label class="control-label col-md-2" for="action-order">Ordem de exibição:</label>
    <div class="col-md-9">    
    @if(!empty($actionCampaign->order))           
      <input
        id="action-order"
        class="form-control"
        name="action-order"
        type="number"
        value="{{ $actionCampaign->order }}"
        data-input-order
      >
    @else 
      <input
        id="action-order"
        class="form-control"
        name="action-order"
        type="number"
        value=""
        data-input-order
      >
    @endif
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-2" for="action-enabled">
      Habilitado:
    </label>
    <div class="col-md-9">  
      <select name="action-enabled" id="action-enabled" class="selectpicker form-control">                 
        <option value=""></option>
        <option value="1" @if (!empty($actionCampaign->enabled) && $actionCampaign->enabled == 1) {{ ' selected="selected" ' }} @endif>Sim</option>
        <option value="0" @if (isset($actionCampaign->enabled) && $actionCampaign->enabled == 0) {{ ' selected="selected" ' }} @endif>Não</option>
      </select>       
    </div>  
  </div>  
  <div class="form-group">
    <label class="control-label col-md-2" for="action-image">
      Imagem:
    </label>
    @php 
      $class = ''; 
      if(!empty($actionCampaign->path_image)) {
        $class = 'sr-only';
      }
    @endphp
    
    <div class="col-md-9 {{$class}}" data-wrap-upload>    
      <div class="fileinput fileinput-new" data-provides="fileinput" data-wrap-file>
          <span class="btn btn-default btn-file">
            <span class="fileinput-new">
              Selecione uma imagem
            </span>
            <span class="fileinput-exists">
              Alterar imagem
            </span>
            <input
              id="action-image"
              name="action-image"
              type="file"
              accept="image/*"
              value=""
              data-input-image
            >            
          </span>
          <span class="fileinput-filename"></span>
      </div>
      <div class="progress progress-bar-default hidden" data-upload-progress>
        <div
          class="progress-bar"
          style="width: 0%"
          role="progressbar"
          aria-valuemax="100"
          aria-valuemin="0"
          aria-valuenow="0"
        >
        </div>
      </div>
    </div>
    @if(!empty($actionCampaign->path_image))
      <div class="col-md-9 preview-img-action" data-wrap-preview>
        <div class="col-md-3">
          <img src="/storage/{{$actionCampaign->path_image}}" alt="">
        </div>
        <div class="col-md-9">
          <button class="btn btn-danger" type="button" data-path="{{$actionCampaign->path_image}}" data-destroy-image>
            Excluir
          </button>
        </div>
      </div>
    @else
      <div class="col-md-9" data-wrap-preview></div>
    @endif
  </div>
  <div class="form-group"> 
    <label class="control-label col-md-2" for="action_filter_gender">Sexo:</label>
          <div class="col-md-9">
          <select title="Selecione o sexo..." name="action_filter_gender" id="action_filter_gender" class="selectpicker form-control" data-size="10" action_filter_gender>
              @foreach(['A' => 'Todos', 'F' => 'Feminino', 'M' => 'Masculino'] as $key => $value)
                  <option value="{{ $key }}"  @if (!empty($actionCampaign->filter_gender) && $actionCampaign->filter_gender == $key) {{ ' selected="selected" ' }} @endif  > {{ $value }}</option>
              @endforeach
          </select>
          </div>               
  </div>
  <div class="form-group">
      <div class="col-md-12">
          <div class="col-md-1">
          </div>
          <div class="col-md-3">
              <label for="action_filter_operation_begin">Operacao Inicial</label>
              <select title="Selecione..." name="action_filter_operation_begin" id="action_filter_operation_begin" class="selectpicker form-control" data-live-search="true" data-size="10" action_filter_operation_begin>
                  @foreach($operations as $key => $value)
                      <option value="{{ $key }}" @if (!empty($actionCampaign->filter_operation_begin) && $actionCampaign->filter_operation_begin == $key) {{ ' selected="selected" ' }} @endif>{{ $value }}</option>
                  @endforeach
              </select>
  </div>
  <div class="col-md-2">
      <label for="action_filter_age_begin">Idade Inicial</label>
      <input type="number" maxlength="2" min="0" max="99" id="action_filter_age_begin" name="action_filter_age_begin" class="input-sm form-control" value="{{ $actionCampaign->filter_age_begin ?? null }}" action_filter_age_begin>
  </div> 
  <div class="action-block-end">
      <div class="col-md-3">
          <label for="action_filter_operation_end">Operacao Final</label>
          <select title="Selecione..." name="action_filter_operation_end" id="action_filter_operation_end" class="selectpicker form-control" data-live-search="true" data-size="10" action_filter_operation_end>
              @foreach ($operations as $key => $value)
                  @if ($key == '<' || $key == '<=')
                  <option value="{{ $key }}" @if (!empty($actionCampaign->filter_operation_end) && $actionCampaign->filter_operation_end == $key) {{ ' selected="selected" ' }} @endif >{{ $value }}</option>
                  @endif
              @endforeach
          </select>
      </div>
      <div class="col-md-2">
          <label for="action_filter_age_end">Idade final</label>
          <input type="number" maxlength="2" min="0" max="99" id="action_filter_age_end" name="action_filter_age_end" class="input-sm form-control" value="{{ $actionCampaign->filter_age_end ?? null }}" action_filter_age_end>
      </div>
  </div>
      </div>
  </div>
  <div class="form-group">
      <div class="col-md-12">
          <div class="col-md-1">
          </div>
          <div class="col-md-5">
              <label for="action_filter_ddd">DDD</label>
              <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador. Ex.: 11|15|21 "><i class="fa fa-question-circle"></i></span>
              <textarea id="action_filter_ddd" name="action_filter_ddd" class="input-sm form-control" action_filter_ddd>{{ $actionCampaign->filter_ddd ?? null }}</textarea>
          </div>
          <div class="col-md-5">
              <label for="action_filter_cep">Cep</label>
              <span data-toggle="tooltip" data-placement="top" data-html="true" title="Utilize pipe | como separador. Ex.: 00.000-000|00.000-001|00.000-002 "><i class="fa fa-question-circle"></i></span>
              <textarea id="action_filter_cep" name="action_filter_cep" class="input-sm form-control" action_filter_cep>{{ $actionCampaign->filter_cep ?? null }}</textarea>
          </div>
      </div>
  </div>

  <div class="sr-only">
    {{ csrf_field() }}
    <input name="action" type="hidden" value="" data-input-action>
    <input name="id" type="hidden" value="" data-input-id>
    @if(!empty($actionCampaign->path_image))
      <input id="image_path" name="image_path" type="hidden" value="{{$actionCampaign->path_image}}" data-input-path>  
    @else
      <input id="image_path" name="image_path" type="hidden" value="" data-input-path>  
    @endif
  </div>
</div>

<hr>

<script src="{!! asset('assets/js/app/unified-campaigns.js') !!}?{{date('hisdmY')}}"></script>