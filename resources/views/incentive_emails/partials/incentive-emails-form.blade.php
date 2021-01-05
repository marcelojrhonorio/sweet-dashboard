<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-incentive>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" data-form-incentive>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            E-mails incentivados
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
                data-input-description
              >
            </div>
          </div>          
          <div class="form-group">
            <label class="control-label col-md-2" for="points">
              Pontos:
            </label>
            <div class="col-md-10">
              <input
                id="points"
                class="form-control"
                name="points"
                type="number"
                placeholder="Pontos"
                value=""
                required
                data-input-points
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="link">
              Link redirect:
            </label>
            <div class="col-md-10">
              <input
                id="redirect_link"
                class="form-control"
                name="redirect_link"
                type="text"
                placeholder="Link"
                value=""
                required
                data-input-link
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="code">
              Código:
            </label>
            <div class="col-md-4">
              <input
                id="code"
                class="form-control"
                name="code"
                type="text"
                value=""
                style="pointer-events: none;"
                disabled
                required
                data-input-code
              >
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
