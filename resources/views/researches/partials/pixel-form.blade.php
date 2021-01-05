<div class="modal inmodal" tabindex="-1" role="dialog" data-modal-pixels>
  <div class="modal-dialog-pixel" role="document">
    <div class="modal-content animated bounceInRight">
      <form class="form-horizontal" data-form-pixel>
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                &times;
              </span>
          </button>        
            <h5 class="modal-title" data-pixel-title>
              
            </h5>
            <h3 clss="modal-subtitle" data-pixel-subtitle>

            </h3>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-2" for="affiliate_id">
                Affiliate:
              </label>
              <div class="col-md-10">
                <input
                  id="affiliate_id"
                  class="form-control"
                  name="affiliate_id"
                  type="number"
                  placeholder="Affiliate ID"                
                  value=""
                  required
                  data-input-affiliate
                >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2" for="pixel_type">
                Type:
              </label>
              <div class="col-md-10">
                <select class="form-control" id="pixel_type" data-input-type>
                  <option selected required>Tipo de pixel</option>
                    <option value="1" data-input-type-completed>
                      Completa
                    </option>
                    <option value="2" data-input-type-quotafull>
                      Quota Full
                    </option>
                    <option value="3" data-input-type-filtered>
                      Filtrada
                    </option>
                </select>                         
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2" for="goal_id">
                Goal ID:
              </label>
              <div class="col-md-10">
                <input
                  id="goal_id"
                  class="form-control"
                  name="goal_id"
                  type="number"
                  placeholder="Goal ID"
                  value=""
                  data-input-goal
                >
              </div>           
            </div>

            <div class="form-group">
              <label class="control-label col-md-2" for="has_redirect">
                Redirect:
              </label>
              <div class="col-md-1">
                <input
                  id="has_redirect"
                  class="form-control"
                  name="has_redirect"
                  value=0
                  type="checkbox"
                  data-input-redirect
                >
              </div>           
            </div>

            <div class="form-group">
              <label class="control-label col-md-2" for="redirect_link">
                Link:
              </label>
              <div class="col-md-10">
                <input
                  id="redirect_link"
                  class="form-control"
                  name="redirect_link"
                  type="text"
                  placeholder="Redirect Link"
                  value="" 
                  disabled = "true"               
                  data-input-link
                >
              </div>           
            </div> 
            
            @include('researches.partials.pixels-list')

            <div class="sr-only">
              {{ csrf_field() }}
              <input name="id" type="hidden" value="" data-input-id>
              <input name="action" type="hidden" value="create" data-input-action>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-primary" type="submit" data-btn-register>
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
