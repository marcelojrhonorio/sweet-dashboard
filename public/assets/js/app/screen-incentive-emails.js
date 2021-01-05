(function($) {
  const ScreenIncentiveEmails = {
    inputs: {},

    DataTable: null,

    start: function() {
      this.$btnNew = $('[data-btn-new]');

      this.$table = $('[data-table-incentive]');

      this.$modal              = $('[data-modal-incentive]');
      this.$modalTitle         = this.$modal.find('[data-modal-title]');
      this.$form               = this.$modal.find('[data-form-incentive]');
      this.$btnConfirm         = this.$modal.find('[data-btn-confirm]');

      this.inputs.$id          = this.$form.find('[data-input-id]');
      this.inputs.$action      = this.$form.find('[data-input-action]');

      this.inputs.$title       = this.$form.find('[data-input-title]');
      this.inputs.$description = this.$form.find('[data-input-description]');
      this.inputs.$points      = this.$form.find('[data-input-points]');
      this.inputs.$link        = this.$form.find('[data-input-link]');
      this.inputs.$code        = this.$form.find('[data-input-code]');

      this.bind();
      this.dataTable();
    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$btnNew.on('click', $.proxy(this.onBtnNewClick, this));
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
          url: '/incentive-emails/search',
        },
        columns: [
          {
            data: 'id',
          },
          {
            data: 'code',
          },          
          {
            data: 'title',
          },
          {
            data: 'description',
          },
          {
            data: 'points',
          },
          {
            data: 'redirect_link',
          },
          {
            data: null,
            width: '3%',
            render: function(data, type, full, meta) {
              const btnEdit = `
                <button
                  class="btn btn-xs btn-primary"
                  title="Editar"
                  type="button"
                  data-btn-edit
                  data-id="${data.id}"
                  data-code="${data.code}"
                  data-title="${data.title}"
                  data-description="${data.description}"
                  data-points="${data.points}"
                  data-link="${data.redirect_link}"
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

              const buttons = `${btnEdit} ${btnDestroy}`;

              return buttons;
            },            
          },
        ],
      })
    },

    onBtnNewClick: function(event) {
      event.preventDefault();

      this.clearInputs();

      var m = m || 9; code = '', r = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      for (var i=0; i < m; i++) { code += r.charAt(Math.floor(Math.random()*r.length)); }

      this.inputs.$code.val(code);

      this.$modal.modal('show');
    },

    clearInputs: function() {
      this.inputs.$id.val('');
      this.inputs.$title.val('');
      this.inputs.$description.val('');
      this.inputs.$points.val('');
      this.inputs.$link.val('');
      this.inputs.$code.val('');
    },

    getValues: function() {
      return {
        title         : this.inputs.$title.val(),
        description   : this.inputs.$description.val(),
        points        : this.inputs.$points.val(),
        redirect_link : this.inputs.$link.val(),
        code          : this.inputs.$code.val(),
      };
    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if (
        '' === $.trim(this.inputs.$title.val())  ||
        '' === $.trim(this.inputs.$points.val()) ||
        '' === $.trim(this.inputs.$link.val())
      ) {
        sweet.common.message('error', 'Os campos Título, Pontos e Link são obrigatórios');
        return;
      }

      var redirect_address = $.trim(this.inputs.$link.val());
      if (!(redirect_address.indexOf("http://") == 0 || redirect_address.indexOf("https://") == 0)) {
        sweet.common.message('error', 'A URL deve começar com <strong>http://</strong> ou <strong>https://</strong>');
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
      event.preventDefault();
      
      const values = JSON.stringify(this.getValues());

      const headers = {
        'Accept'      : 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      };
      
      const saving = $.ajax({
        cache      : false,
        type       : 'post',
        dataType   : 'json',
        data       : values,
        headers    : headers,
        url        : '/incentive-emails',
        contentType: 'application/json; charset=utf-8',
      });

      saving.done($.proxy(this.onCreateSuccess, this));

      saving.fail($.proxy(this.onCreateFail, this));
    },

    onCreateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.DataTable.ajax.reload().desc;
        this.clearInputs();
        sweet.common.message('success', 'Dados cadastrados com sucesso!');
      }
    },
    
    onCreateFail: function(error) {
      console.log('Failed to CREATE incentive email: ', error);
    },

    onBtnEditClick: function(event) {
      event.preventDefault();

      this.$modalTitle.text('Editar Email Incentivado');
      this.$btnConfirm.text('Editar');
      this.inputs.$action.val('update');

      const $btn          = $(event.currentTarget);
      const id            = $.trim($btn.data('id'));
      const code          = $.trim($btn.data('code'));
      const title         = $.trim($btn.data('title'));
      const description   = $.trim($btn.data('description'));
      const points        = $.trim($btn.data('points'));
      const redirect_link = $.trim($btn.data('link'));

      this.inputs.$id.val(id);
      this.inputs.$code.val(code);
      this.inputs.$title.val(title);
      this.inputs.$description.val(description);
      this.inputs.$points.val(points);
      this.inputs.$link.val(redirect_link);

      this.$modal.modal('show');
    },

    onUpdateSubmit: function() {
      const params = {
        _method       : 'put',
        _token        : $('meta[name="csrf-token"]').attr('content'),
        id            : this.inputs.$id.val(),
        code          : this.inputs.$code.val(),
        title         : this.inputs.$title.val(),
        description   : this.inputs.$description.val(),
        points        : this.inputs.$points.val(),
        redirect_link : this.inputs.$link.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: `/incentive-emails/${params.id}`,
      });

      saving.done($.proxy(this.onUpdateSuccess, this));

      saving.fail($.proxy(this.onUpdateFail, this));
    },

    onUpdateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.DataTable.ajax.reload().desc;

        this.clearInputs();
        
        sweet.common.message('success', 'Dados atualizados com sucesso!');
      }
    },

    onUpdateFail: function(error) {
      console.log('Failed to UPDATE research: ', error);
    },    

    onBtnDestroyClick: function(event) {
      event.preventDefault();

      const id      = $(event.currentTarget).data('id');
      const token   = $('meta[name="csrf-token"]').attr('content');

      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/incentive-emails/${id}`,
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
    ScreenIncentiveEmails.start();
  });
})(jQuery);