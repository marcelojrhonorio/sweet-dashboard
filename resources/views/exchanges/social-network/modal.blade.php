<div class="modal fade" tabindex="-1" role="dialog" data-social-network-modal>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form class="form-horizontal" enctype="multipart/form-data" data-form-exchages-sm>
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h2 class="modal-title">Troca de Pontos - Redes Sociais</h2>
      </div>
          <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
          <input type="hidden" id="action" name="action" value="">
          <div class="modal-body">
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="nome" class="col-md-2 control-label">ID:</label>
                      <div class="col-md-10">
                          <input id="id" name="id" type="text" class="form-control" disabled="disabled" data-exchange-id>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="nome" class="col-md-2 control-label">ID Customer:</label>
                      <div class="col-md-10">
                          <input id="customers_id" name="customers_id" type="text" class="form-control" disabled="disabled" autofocus="autofocus" maxlength="80" data-customers-id>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="nome" class="col-md-2 control-label">Nome:</label>
                      <div class="col-md-10">
                          <input id="fullname" name="fullname" type="text" class="form-control" autofocus="autofocus" disabled="disabled" maxlength="80" data-fullname>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="email" class="col-md-2 control-label">Email:</label>
                      <div class="col-md-10">
                          <input id="email" name="email" type="text" class="form-control" autofocus="autofocus" disabled="disabled" maxlength="80" data-email>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="subject" class="col-md-2 control-label">Assunto:</label>
                      <div class="col-md-10">
                          <input id="subject" name="subject" type="text" class="form-control" autofocus="autofocus" disabled="disabled" maxlength="80" data-subject>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="profile_link" class="col-md-2 control-label">Link do Perfil:</label>
                      <div class="col-md-7">
                          <input id="profile_link" name="profile_link" type="text" class="form-control" autofocus="autofocus" disabled="disabled" maxlength="80" data-profile-link>
                      </div>
                      <div class="col-md-3">
                          <a class="btn btn-primary" target="_blank" btn-view-profile>Ver perfil</a>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="profile_picture" class="col-md-2 control-label">Imagem do Perfil:</label>
                      <div class="col-md-10">
                          <img id="profile_picture" name="profile_picture" data-profile-picture>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-md-12">
                      <label for="status" class="col-md-2 control-label">Status:</label>
                      <div class="col-md-10">
                      <select title="Selecione o status..." name="status" id="status" class="selectpicker form-control" data-size="10" data-status>
                            <option value="pending"> Pendente</option>
                            <option value="approved"> Aprovado</option>
                            <option value="disapproved"> Reprovado</option>
                      </select>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button class="ladda-button btn btn-primary"  data-style="zoom-in" type="button" id="action-button" btn-submit-status>Salvar</button>
              <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
          </div>
      </form>
    </div>
   </div>
</div>
