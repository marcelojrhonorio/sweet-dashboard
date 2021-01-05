<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-relationship>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" action="post" enctype="multipart/form-data" data-form-relationship>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            Régua de Relacionamento
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label col-md-2" for="subject">
              Assunto:
            </label>
            <div class="col-md-10">
              <input
                id="subject"
                class="form-control"
                name="subject"
                type="text"
                placeholder="Assunto"
                value=""
                required
                data-input-subject
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="html">
              HTML:
            </label>
            <div class="col-md-10" data-wrap-upload>
              <div class="fileinput fileinput-new" data-provides="fileinput" data-wrap-file>
                  <span class="btn btn-default btn-file">
                    <span class="fileinput-new">
                      Selecione um arquivo
                    </span>
                    <span class="fileinput-exists">
                      Alterar arquivo
                    </span>
                    <input
                      id="html"
                      name="html"
                      type="file"
                      accept=".html"
                      value=""
                      data-input-html
                    >
                  </span>
                  <span class="fileinput-filename" data-file-name></span>
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
          <div class="sr-only">
            {{ csrf_field() }}
            <input name="action" type="hidden" value="" data-input-action>
            <input name="id" type="hidden" value="" data-input-id>
            <input name="path" type="hidden" value="some" data-input-path>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="order">
              Ordem:
            </label>
            <div class="col-md-10">
              <input
                id="order"
                class="form-control"
                name="order"
                type="number"
                value=0
                data-input-order
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="enabled">
              Habilitado:
            </label>
            <div class="col-md-1">
              <input
                id="enabled"
                class="form-control"
                name="enabled"
                type="checkbox"
                checked
                data-input-enabled
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
