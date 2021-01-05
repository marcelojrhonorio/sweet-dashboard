<div class="form-group">
  <div class="col-md-12">
    <strong>Capturas:</strong>
  </div>

  <div class="col-md-12 text-right">
    <button class="btn btn-primary" type="button" data-catch-inputs-btn-add>
      <i class="fa fa-plus-circle" aria-hidden="true"></i>
      Adicionar
    </button>
  </div>

  <div class="col-md-12">
    <table class="table table-striped table-bordered table-hover table-fields" data-fields-table>
      <thead>
        <th>#</th>
        <th>Tipo</th>
        <th>Label</th>
        <th class="tabledit-toolbar-column">Ações</th>
      </thead>
      <tbody data-catch-inputs-list>
        @unless (empty($fields))
          @foreach ($fields as $field)
            <tr>
              <td>
                {{ $field['id'] }}
              </td>
              <td>
                {{ $field['type']['name'] }}
              </td>
              <td>
                {{ $field['label'] }}
              </td>
            </tr>
          @endforeach
        @endunless
      </tbody>
    </table>
  </div>

  <div class="col-md-12 text-right sr-only" data-save-all-container>
    <button class="btn btn-warning" type="button" data-catch-inputs-btn-save-all>
      <span class="fa fa-save" aria-hidden="true"></span>
      Salvar todos
    </button>
  </div>
</div>
