const CampaignFieldsType = () => {
  const CatchInputsCreate = {
    ui: {
      $btnAdd: null,
      $listing: null,
      $saveAll: null,
      $btnSaveAll: null,
    },

    state: {
      counter: 0,
    },

    fieldTypes: [],

    start: function start() {
      this.ui.$btnAdd     = $('[data-catch-inputs-btn-add]');
      this.ui.$listing    = $('[data-catch-inputs-list]');
      this.ui.$saveAll    = $('[data-save-all-container]');
      this.ui.$btnSaveAll = $('[data-catch-inputs-btn-save-all]');

      this.parseFieldTypes();
      this.bind();
    },

    parseFieldTypes: function parseFieldTypes() {
      const placeholder = `
        <option value="-1" disabled>
          Selecione o tipo
        </option>
      `;

      this.fieldTypes = sweet.fieldTypes.reduce(function(previous, current) {
        const option = `
          <option value="${current.id}">
            ${current.name}
          </option>
        `;

        return `${previous}${option}`;
      }, placeholder);
    },

    bind: function bind() {
      this.ui.$btnAdd.on('click', this.onClickBtnAdd.bind(this));
      this.ui.$listing.on('click', '[data-catch-inputs-remove]', this.onClickBtnRemove.bind(this));
      this.ui.$listing.on('click', '[data-catch-inputs-save]', this.onClickBtnSave.bind(this));
      this.ui.$btnSaveAll.on('click', this.onClickBtnSaveAll.bind(this));
    },

    template: function template() {
      const html = `
        <tr data-catch-inputs-row-new>
          <td>
            <select
              id="catch-input[${this.state.counter}][type]"
              class="form-control"
              name="catch-input[${this.state.counter}][type]"
              data-catch-input-type
            ></select>
          </td>
          <td>
            <input
              id="catch-input[${this.state.counter}][label]"
              class="form-control"
              name="catch-input[${this.state.counter}][label]"
              placeholder="Informe o label"
              data-catch-input-label
            >
          </td>
          <td>
            <div class="btn-group btn-group-sm">
              <button
                class="btn btn-sm btn-danger"
                type="button"
                title="Remover"
                data-catch-inputs-remove
              >
                <span class="sr-only">Remover</span>
                <i aria-hidden="true" class="fa fa-minus-circle"></i>
              </button>
              <button
                class="btn btn-sm btn-success"
                type="button"
                title="Salvar"
                data-catch-inputs-save
              >
                <span class="sr-only">Salvar</span>
                <i aria-hidden="true" class="fa fa-save"></i>
              </button>
            </div>
          </td>
        </tr>
      `;

      return $(html);
    },

    templateListItem: function templateListItem(item) {
      const html = `
        <tr id="${item.id}">
          <td style="display: none;">
            <span class="tabledit-span tabledit-identifier">
              ${item.id}
            </span>
            <input
              class="tabledit-input tabledit-identifier"
              name="id"
              value="${item.id}"
              disabled
              type="hidden"
            >
          </td>
          <td class="tabledit-view-mode">
            <span class="tabledit-span">
              ${item.type.name}
            </span>
            <select
              class="tabledit-input form-control input-sm"
              name="type"
              style="display: none;"
              disabled
            ></select>
          </td>
          <td class="tabledit-view-mode">
            <span class="tabledit-span">
              ${item.label}
            </span>
            <input
              class="tabledit-input form-control input-sm"
              name="label"
              value="${item.label}"
              style="display: none;"
              disabled
              type="text"
            >
          </td>
          <td style="white-space: nowrap; width: 1%;">
            <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
              <div class="btn-group btn-group-sm" style="float: none;">
                <button
                  class="tabledit-edit-button btn btn-sm btn-default"
                  type="button"
                  style="float: none;"
                >
                  <span class="glyphicon glyphicon-pencil"></span>
                </button>
                <button
                  class="tabledit-delete-button btn btn-sm btn-default"
                  type="button"
                  style="float: none;"
                >
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </div>
              <button
                class="tabledit-save-button btn btn-sm btn-success"
                type="button"
                style="display: none; float: none;"
              >
                Salvar
              </button>
              <button
                class="tabledit-confirm-button btn btn-sm btn-danger"
                type="button"
                style="display: none; float: none;"
              >
                Confirma?
              </button>
              <button
                class="tabledit-restore-button btn btn-sm btn-warning"
                type="button"
                style="display: none; float: none;"
              >
                Restore
              </button>
            </div>
          </td>
        </tr>
      `;

      return $(html);
    },

    toggleSaveAll: function toggleSaveAll() {
      const addOrRemove = this.state.counter < 2 ? 'addClass' : 'removeClass';

      this.ui.$saveAll[addOrRemove]('sr-only');

      return this;
    },

    refreshTable: function refreshTable(data) {
      const list = data.map(function(item) {
        const $html    = this.templateListItem(item);
        const $options = $(this.fieldTypes);

        $html.find('select')
          .append($options)
          .find(`option[value="${item.type.id}"]`)
            .prop('selected', true);

        return $html;
      }, this);

      this.ui.$listing.empty().append(list);
    },

    onClickBtnAdd: function onClickBtnAdd(event) {
      event.preventDefault();

      this.state.counter += 1;

      const $html    = this.template();
      const $options = $(this.fieldTypes);

      $options.appendTo($html.find('select'));
      $html.appendTo(this.ui.$listing).hide().fadeIn(600);

      this.toggleSaveAll();
    },

    onClickBtnRemove: function onClickBtnRemove(event) {
      event.preventDefault();

      this.state.counter -= 1;

      $(event.target).closest('tr').fadeOut(600, function () {
        this.remove();
      });

      this.toggleSaveAll();
    },

    onClickBtnSave: function onClickBtnSave(event) {
      event.preventDefault();

      const $target = $(event.target);
      const $row    = $target.closest('tr');

      const params = [{
        label                  : $.trim($row.find('[data-catch-input-label]').val()),
        campaign_field_type_id : $.trim($row.find('[data-catch-input-type]').val()),
        campaign_id            : $.trim($('#campaign_id').val()),
      }];

      const saving = sweet.common.crud.save({
        endpoint: '/campaigns/fields/save',
        params: params,
      });

      saving.done(function (response) {
        if (false === response.success) {
          console.log('Erro ao salvar dados...');
        }

        const data = JSON.parse(response.data);

        this.state.counter = 0;

        this.refreshTable(data);

        this.toggleSaveAll();
      }.bind(this));

      saving.fail(function (error) {
        console.log(error.responseText);
      });
    },

    onClickBtnSaveAll: function onClickBtnSaveAll(event) {
      event.preventDefault();

      const campaignsId = $.trim($('#campaign_id').val());

      const $rows = $('[data-catch-inputs-row-new]');

      const params = $rows.map(function() {
        const $this  = $(this);
        const $type  = $this.find('[data-catch-input-type]');
        const $label = $this.find('[data-catch-input-label]');

        return {
          label: $.trim($label.val()),
          campaign_field_type_id: $.trim($type.val()),
          campaign_id: campaignsId,
        };
      }).get();

      const saving = sweet.common.crud.save({
        endpoint: '/campaigns/fields/save',
        params: params,
      });

      saving.done(function(response) {
        if (false === response.success) {
          console.log('Erro ao salvar dados...');
        }

        const data = JSON.parse(response.data);

        this.state.counter = 0;

        this.refreshTable(data);

        this.toggleSaveAll();
      }.bind(this));

      saving.fail(function(error) {
        console.log(error.responseText);
      });
    },
  };

  /**
   * DOM Ready
   */
  $(function () {
    /**
     * Example #2
     * @see http://markcell.github.io/jquery-tabledit/#examples
     */
    const options = sweet.fieldTypes.reduce(function(previous, current, index) {
      previous[current.id] = current.name
      return previous;
    }, {});

    $('[data-fields-table]').Tabledit({
      url: '/campaigns/fields',
      hideIdentifier: true,
      columns: {
        identifier: [0, 'id'],
        editable: [
          [1, 'type', JSON.stringify(options)],
          [2, 'label'],
        ],
      },
      buttons: {
        save: {
          html: 'Salvar'
        },
        confirm: {
          html: 'Confirma?'
        }
      },
    });

    CatchInputsCreate.start();
  });
}
export default CampaignFieldsType;