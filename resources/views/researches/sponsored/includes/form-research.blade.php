<div class="form-group">
  <label class="control-label col-md-1" for="title">
    Título:
  </label>
    <div class="col-md-5">
    @if($action == 'create')
      <input
        id="title"
        class="form-control"
        name="title"
        type="text"
        placeholder="Título"
        value=""
        required
        data-input-title
      >
    @else
      <input
        type="hidden"
        value="{{ $research->id }}"
        data-research-id
      > 
      <input
        id="title"
        class="form-control"
        name="title"
        type="text"
        placeholder="Título"
        value="{{ $research->title }}"
        required
        data-input-title
      >
    @endif  
    </div>  
  <label class="control-label col-md-1" for="subtitle">
    Subtítulo:
  </label>
  <div class="col-md-5">
  @if($action == 'create')
    <input
      id="subtitle"
      class="form-control"
      name="subtitle"
      type="text"
      placeholder="Subtítulo"
      value=""
      required
      data-input-subtitle
    >
  @else
  <input
      id="subtitle"
      class="form-control"
      name="subtitle"
      type="text"
      placeholder="Subtítulo"
      value="{{ $research->subtitle }}"
      required
      data-input-subtitle
    >
  @endif    
</div>  
</div>
<div class="form-group">
  <label class="control-label col-md-1" for="description">
    Descrição:
  </label>
  <div class="col-md-11">
  @if($action == 'create')
    <input
      id="description"
      class="form-control"
      name="description"
      type="text"
      placeholder="Descrição"
      value=""
      required
      data-input-description
    >
  @else
  <input
      id="description"
      class="form-control"
      name="description"
      type="text"
      placeholder="Descrição"
      value="{{ $research->description }}"
      required
      data-input-description
    >
  @endif
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-1" for="enabled">
    Ativo:
  </label>
  <div class="col-md-3">  
    <select name="enabled" id="enabled" class="selectpicker form-control">     
    @if(isset($research->enabled) && (1 == $research->enabled))
      <option value=""></option>
      <option value="1" selected="selected">Sim</option>
      <option value="0">Não</option>
    @elseif(isset($research->enabled) && (0 == $research->enabled))
      <option value=""></option>
      <option value="1">Sim</option>
      <option value="0" selected="selected">Não</option>
    @else
      <option value=""></option>
      <option value="1">Sim</option>
      <option value="0">Não</option>
    @endif       
    </select>       
  </div>       
  <label class="control-label col-md-3" for="final_url">
    sweetbonus.com.br/research/
  </label>
  <div class="col-md-2">
  @if($action == 'create')
    <input
      id="final_url"
      class="form-control"
      name="final_url"
      type="text"
      placeholder=""
      value=""
      required
      data-input-finalurl
    >
  @else
    <input
      id="final_url"
      class="form-control"
      name="final_url"
      type="text"
      placeholder=""
      value="{{ $research->final_url }}"
      required
      data-input-finalurl
    >
  @endif
  </div>
    <label class="control-label col-md-1" for="points">
      Pontos
    </label>
    <div class="col-md-2">
    @if($action == 'create')
      <input
        id="points"
        class="form-control"
        name="points"
        type="number"
        placeholder=""
        value=""
        required
        data-input-points
      >
    @else
      <input
        id="points"
        class="form-control"
        name="points"
        type="number"
        placeholder=""
        value="{{ $research->points }}"
        required
        data-input-points
      >
    @endif
  </div>  
</div> 

<input name="img" type="hidden" value="storage/{{session('iconPS')}}" data-img>

<div class="sr-only">
  {{ csrf_field() }}
  
  @if($action == 'create')
    <input name="action" type="hidden" value="create" data-input-action>
    <input name="id" type="hidden" value="" data-input-id>
  @else
    <input name="action" type="hidden" value="update" data-input-action>
    <input name="id" type="hidden" value="{{ $research->id }}" data-input-id>
  @endif
</div>
       


        
        
      