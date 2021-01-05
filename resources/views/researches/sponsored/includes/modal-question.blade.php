<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-question>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" action="post" enctype="multipart/form-data" data-form-question>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            Cadastro de Questão
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label col-md-2" for="description">
              Pergunta:
            </label>
            <div class="col-md-10">
              <input
                id="description"
                class="form-control"
                name="description"
                type="text"
                placeholder="Descrição da pergunta"
                value=""
                required
                data-description-question
              >
            </div>
          </div>
          <div class="form-group" id="group-config-option">          
            <div class="col-md-2 control-label">
              <label>Alternativa: </label>
            </div>
            <div class="col-md-9">
              <input type="text" id="option_description" name="option_description[]" class="input-sm form-control" placeholder="Descrição de Alternativa" data-option-description />
            </div>
            <div class="col-md-1">
                <a href="javacript:void(0);" class="btn btn-primary add-input-config" style="float: right; right: 47px; top: -2px"><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span></a>
            </div>            
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="one_answer">
                Resposta única:
            </label>
            <div class="col-md-5">               
                <select name="one_answer" id="one_answer" class="selectpicker form-control">
                <option value=""></option>
                <option value="1">Sim</option>
                <option value="0">Não</option>
                </select>
            </div>  
          </div> 
          <div class="form-group">
            <label class="control-label col-md-2" for="extra_information">
                Informação extra:
            </label>
            <div class="col-md-10">               
              <input type="text" id="extra_information" name="extra_information" class="input-sm form-control" placeholder="Informação Extra" data-extra-information />
            </div>  
          </div> 
          <div class="sr-only">
            {{ csrf_field() }}
            <input name="id" type="hidden" value="" data-input-id>
            <input name="action" type="hidden" value="create" data-input-action>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" data-btn-confirm>
            Cadastrar
          </button>
          <button class="btn btn-default" type="button" data-dismiss="modal" style="margin-top:-1%">
            Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
