<div class="modal inmodal" tabindex="-1" role="dialog" data-modal>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" action="post" enctype="multipart/form-data" data-form-actions>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            Ações
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label col-md-2" for="category">
              Categoria:
            </label>
            <div class="col-md-10">
              <select
                id="category"
                class="form-control selectpicker"
                name="category"
                title="Selecione uma categoria..."
                data-input-category
                data-live-search="true"
                data-size="10"
              >
                @forelse ($categories as $category)
                  <option value="{{ $category->id }}">
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
            <label class="control-label col-md-2" for="type">
              Tipo:
            </label>
            <div class="col-md-10">
              <select
                id="type"
                class="form-control selectpicker"
                name="type"
                title="Selecione um tipo..."
                data-live-search="true"
                data-size="10"
                data-input-type
              >
                @forelse ($types as $type)
                  <option value="{{ $type->id }}">
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
          <div class="form-group sr-only" data-wrap-type-url>
            <label class="control-label col-md-2" for="type-url">
              URL:
            </label>
            <div class="col-md-10">
              <input
                id="type-url"
                class="form-control"
                name="type-url"
                type="text"
                value=""
                data-input-type-url
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="title">
              Título:
            </label>
            <div class="col-md-10">
              <input
                id="title"
                class="form-control"
                name="title"
                type="text"
                placeholder="Título"
                value=""
                data-input-title
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="description">
              Descrição:
            </label>
            <div class="col-md-10">
              <input
                id="description"
                class="form-control"
                name="description"
                type="text"
                placeholder="Descrição"
                value=""
                data-input-description
              >
            </div>
          </div>
          <div class="form-group" data-wrap-points>
            <label class="control-label col-md-2" for="grant_points">
              Pontos:
            </label>
            <div class="col-md-10">
              <input
                id="grant_points"
                class="form-control"
                name="grant_points"
                type="number"
                placeholder="Pontos"
                value=""
                data-input-points
              >
            </div>
          </div>
          <div class="form-group">
          <label class="control-label col-md-2" for="order">Ordem de exibição:</label>
            <div class="col-md-10">              
              <input
                id="order"
                class="form-control"
                name="order"
                type="number"
                value=""
                data-input-order
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="enabled">
              Habilitado:
            </label>
            <div class="col-md-10">  
              <select name="enabled" id="enabled" class="selectpicker form-control">                 
                <option value=""></option>
                <option value="1">Sim</option>
                <option value="0">Não</option>
              </select>       
            </div>  
          </div>  
          <div class="form-group">
            <label class="control-label col-md-2" for="image">
              Imagem:
            </label>
            <div class="col-md-10" data-wrap-upload>
              <div class="fileinput fileinput-new" data-provides="fileinput" data-wrap-file>
                  <span class="btn btn-default btn-file">
                    <span class="fileinput-new">
                      Selecione uma imagem
                    </span>
                    <span class="fileinput-exists">
                      Alterar imagem
                    </span>
                    <input
                      id="image"
                      name="image"
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
            <div class="col-md-10" data-wrap-preview></div>
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
              <div class="col-md-11">
                  <div class="col-md-11">
                      <span for="resume_filter">Com estes filtros definidos, esta ação estará disponível para </span>                
                      <label filter-users-text> 0 </label>
                      <span> usuário(s).</span>
                      <input type="hidden" value="" data-filter-users>
                  </div>
                  <div class="col-md-1" style="float:right">
                      <div class="col-md-12 text-left">                                                                      
                          <a class="btn btn-primary" title="Atualizar resultado do filtro" data-refresh-filter>
                          <i class="fas fa-sync-alt"></i>
                          </a>                                    
                      </div>
                  </div>                    
              </div>
      </div>
            
        </div>






          <div class="sr-only">
            {{ csrf_field() }}
            <input name="action" type="hidden" value="" data-input-action>
            <input name="id" type="hidden" value="" data-input-id>
            <input name="path" type="hidden" value="some" data-input-path>
            <input name="url_store" type="hidden" value="{{env('STORE_URL')}}" data-store-url>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" data-btn-confirm>
            Cadastrar
          </button>
          <button class="btn btn-default" type="button" data-dismiss="modal">
            Cancelar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
