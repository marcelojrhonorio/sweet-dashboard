<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-researches>
  <div class="modal-dialog" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" data-form-researches>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
          <h4 class="modal-title" data-modal-title>
            Pesquisas
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
            <label class="control-label col-md-2" for="hasoffers_id">
              HasOffers:
            </label>
            <div class="col-md-10">
              <input
                id="hasoffers_id"
                class="form-control"
                name="hasoffers_id"
                type="text"
                placeholder="ID HasOffers"
                value=""
                required
                data-input-hasoffers
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
