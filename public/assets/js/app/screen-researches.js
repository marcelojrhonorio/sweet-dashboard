(function($) {
  const ScreenResearches = {
    inputs: {},

    DataTable: null,

    start: function() {
      this.$btnNew = $('[data-btn-new]');

      this.$table = $('[data-table-researches]');

      this.$modal      = $('[data-modal-researches]');
      this.$modalTitle = this.$modal.find('[data-modal-title]');
      this.$form       = this.$modal.find('[data-form-researches]');
      this.$btnConfirm = this.$modal.find('[data-btn-confirm]');

      this.inputs.$id        = this.$form.find('[data-input-id]');
      this.inputs.$action    = this.$form.find('[data-input-action]');
      this.inputs.$title     = this.$form.find('[data-input-title]');
      this.inputs.$hasoffers = this.$form.find('[data-input-hasoffers]');
      this.inputs.$points    = this.$form.find('[data-input-points]');

      this.bind();
      this.dataTable();
    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$btnNew.on('click', $.proxy(this.onBtnNewClick, this));
      this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
      this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this));
      this.$table.on('click', '[data-btn-destroy]', $.proxy(this.onBtnDestroyClick, this));
    },

    dataTable: function() {
      this.DataTable = this.$table.DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        searching: true,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
          {
            extend: 'excel',
            title: 'empresas',
            text: '<span class="fa fa-file-excel-o"></span> Excel ',
          },
          {
            extend: 'pdf',
            title: 'empresas',
            text: '<span class="fa fa-file-pdf-o"></span> PDF ',
          },
          {
            extend: 'print',
            text: '<span class="fa fa-print"></span> Imprimir ',
            customize: function (win) {
              const $body = $(win.document.body);

              $body.addClass('white-bg').css('font-size', '10px');

              $body.find('table').addClass('compact').css('font-size', 'inherit');
            },
          },
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json',
        },
        ajax: {
          url: '/researches/search',
        },
        columns: [
          {
            data : 'id',
          },
          {
            data : 'title',
          },
          {
            data : 'hasoffers_id',
          },
          {
            data : 'points',
          },
          {
            data : null,
            width: '3%',
            render: function(data, type, full, meta) {
              const btnEdit = `
                <button
                  class="btn btn-xs btn-primary"
                  title="Editar"
                  type="button"
                  data-btn-edit
                  data-id="${data.id}"
                  data-title="${data.title}"
                  data-hasoffers="${data.hasoffers_id}"
                  data-points="${data.points}"
                >
                  <span class="sr-only">Editar</span>
                  <i class="fas fa-pen" aria-hidden="true"></i>
                </button>
              `;

              const btnDestroy = `
                <button
                  class="btn btn-xs btn-danger"
                  title="Excluir"
                  type="button"
                  data-btn-destroy
                  data-id="${data.id}"
                >
                  <span class="sr-only">Excluir</span>
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
              `;

              const btnPixel = `
                <button
                  class="btn btn-xs btn-success"
                  title="addPixel"
                  type="button"
                  data-btn-pixel
                  data-id="${data.id}"  
                  data-title="${data.title}" 
                  data-hasoffers="${data.hasoffers_id}"               
                >
                  <span class="sr-only">Novo Pixel</span>
                  <i class="glyphicon glyphicon-flash" aria-hidden="true"></i>
                </button>
              `;              

              const buttons = `${btnEdit} ${btnDestroy} ${btnPixel}`;

              return buttons;
            },
          },
        ],
      });
    },

    onFormSubmit: function (event) {
      event.preventDefault();

      if (
        '' === $.trim(this.inputs.$title.val())     ||
        '' === $.trim(this.inputs.$hasoffers.val()) ||
        '' === $.trim(this.inputs.$points.val())
      ) {
        sweet.common.message('error', 'Todos os campos são obrigatórios');
        return;
      }

      const formAction = $.trim(this.inputs.$action.val());

      switch (formAction) {
        case 'create':

          this.onCreateSubmit();

          break;

        case 'update':

          this.onUpdateSubmit();

          break;

        default:

          console.log('Invalid form action');
      }
    },

    onCreateSubmit: function() {
      const params = {
        title       : this.inputs.$title.val(),
        hasoffers_id: this.inputs.$hasoffers.val(),
        points      : this.inputs.$points.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: '/researches',
      });

      saving.done($.proxy(this.onCreateSuccess, this));

      saving.fail($.proxy(this.onCreateFail, this));
    },

    onCreateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.DataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados cadastrados com sucesso!');
      }
    },

    onCreateFail: function(error) {
      console.log('Failed to CREATE research: ', error);
    },

    onBtnNewClick: function(event) {
      event.preventDefault();

      this.$modalTitle.text('Cadastrar Pesquisa');
      this.$btnConfirm.text('Cadastrar');
      this.inputs.$action.val('create');

      $('[data-pixels-list]').hide();

      this.$modal.modal('show');
    },

    onBtnEditClick: function (event) {
      event.preventDefault();

      this.$modalTitle.text('Editar Pesquisa');
      this.$btnConfirm.text('Editar');
      this.inputs.$action.val('update');

      const $btn      = $(event.currentTarget);
      const id        = $.trim($btn.data('id'));
      const title     = $.trim($btn.data('title'));
      const hasoffers = $.trim($btn.data('hasoffers'));
      const points    = $.trim($btn.data('points'));

      this.inputs.$id.val(id);
      this.inputs.$title.val(title);
      this.inputs.$hasoffers.val(hasoffers);
      this.inputs.$points.val(points);

      this.$modal.modal('show');
    },

    onUpdateSubmit: function() {
      const params = {
        _method     : 'put',
        _token      : $('meta[name="csrf-token"]').attr('content'),
        id          : this.inputs.$id.val(),
        title       : this.inputs.$title.val(),
        hasoffers_id: this.inputs.$hasoffers.val(),
        points      : this.inputs.$points.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: `/researches/${params.id}`,
      });

      saving.done($.proxy(this.onUpdateSuccess, this));

      saving.fail($.proxy(this.onUpdateFail, this));
    },

    onUpdateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.DataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados atualizados com sucesso!');
      }
    },

    onUpdateFail: function(error) {
      console.log('Failed to UPDATE research: ', error);
    },

    onBtnDestroyClick: function (event) {
      event.preventDefault();

      const id    = $(event.currentTarget).data('id');
      const token = $('meta[name="csrf-token"]').attr('content');

      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/researches/${id}`,
        data   : {
          _method: 'delete',
          _token : token,
        },
      });

      destroying.done($.proxy(this.onDestroySuccess, this));

      destroying.fail($.proxy(this.onDestroyFail, this));
    },

    onDestroySuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.DataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados excluídos com sucesso!');
      }
    },

    onDestroyFail: function(error) {
      console.log('Failed to DESTROY research: ', error);
    },

    onHideModal: function() {
      this.$form[0].reset();
    },
  };

  $(function() {
    ScreenResearches.start();
  });
})(jQuery);
