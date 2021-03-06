<div class="modal inmodal" id="resource_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>

            <form id="resource_form" class="form-horizontal">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                <input type="hidden" id="action" name="action" value="">
                <div class="modal-body">
                    <div class="form-group" id="primary-key">
                        <div class="col-md-12">
                            <label for="id" class="col-md-2 control-label">Nome:</label>
                            <div class="col-md-10">
                                <input id="id" name="id" type="text" class="form-control" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="name" class="col-md-2 control-label">Código:</label>
                            <div class="col-md-10">
                                <input id="name" name="name" type="text" class="form-control" required autofocus="autofocus" maxlength="30">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="link" class="col-md-2 control-label">Link:</label>
                            <div class="col-md-10">
                                <input id="link" name="link" type="text" placeholder="http://" class="form-control" required maxlength="255" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="ladda-button btn btn-primary"  data-style="zoom-in" type="button" name="action-button" id="action-button"></button>
                    <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>
