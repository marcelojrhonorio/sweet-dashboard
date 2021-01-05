<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-stamps>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" action="post" enctype="multipart/form-data" data-form-stamps>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            Selos de pontuação
          </h4>
        </div>
        <div class="modal-body">
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
                required
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
                required
                data-input-description
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="icon">
              Ícone:
            </label>
            <div class="col-md-10" data-wrap-upload>
              <div class="fileinput fileinput-new" data-provides="fileinput" data-wrap-file>
                  <span class="btn btn-default btn-file">
                    <span class="fileinput-new">
                      Selecione uma ícone
                    </span>
                    <span class="fileinput-exists">
                      Alterar ícone
                    </span>
                    <input
                      id="icon"
                      name="icon"
                      type="file"
                      accept="image/*"
                      value=""
                      data-input-icon
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
            <div class="col-md-10 img-preview" data-wrap-preview></div>
          </div>
          <div class="sr-only">
            {{ csrf_field() }}
            <input name="action" type="hidden" value="" data-input-action>
            <input name="id" type="hidden" value="" data-input-id>
            <input name="path" type="hidden" value="some" data-input-path>
          </div>
          <div class="form-group">
            <label for="nome" class="control-label col-md-2" for="title">Tipo:</label>
            <div class="col-md-10">
              <select title="Selecione o tipo..." name="type" id="type" class="selectpicker form-control" data-live-search="true" data-size="5" data-input-type>
                <option value="1">Ação</option>
                <option value="2">E-mail</option>
                <option value="3">E-mail Incentivado</option>
                <option value="4">Member Get Member</option>
                <option value="5">Profile</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="quantity">
              Quantidade:
            </label>
            <div class="col-md-10">
              <input
                id="quantity"
                class="form-control"
                name="quantity"
                type="number"
                value=""
                data-input-quantity
                required
              >
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
          <button class="btn btn-default" type="button" data-dismiss="modal">
            Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
