(function($) {
  const ScreenRelationshipRule = {
  
    DataTable: null,

    start: function() {

      this.$btnNew = $('[data-btn-new]');
      this.$table  = $('[data-table-relationship]');

      this.$modal        = $('[data-modal-relationship]');
      this.$modalTitle   = this.$modal.find('[data-modal-title]');
      this.$wrapPreview  = this.$modal.find('[data-wrap-preview]');
      this.$btnConfirm   = this.$modal.find('[data-btn-confirm]');
      this.$wrapUpload   = this.$modal.find('[data-wrap-upload]');
      
      this.$form       = $('[data-form-relationship]');
      this.$subject    = this.$form.find('[data-input-subject]');
      this.$html       = this.$form.find('[data-input-html]');
      this.$order      = this.$form.find('[data-input-order]'); 
      this.$enabled    = this.$form.find('[data-input-enabled]');
      this.$progress   = this.$form.find('[data-upload-progress]');
      this.$fileName   = this.$modal.find('[data-file-name]');
      
      this.$action = this.$form.find('[data-input-action]');
      this.$id     = this.$form.find('[data-input-id]');
      this.$path   = this.$form.find('[data-input-path]');


      this.bind();
      this.dataTable();
    },
    
    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
      this.$btnNew.on('click', $.proxy(this.onBtnNewClick, this));
      this.$html.on('change', $.proxy(this.onChangeHtml, this));
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
          url: '/relationship-rule/search',
        },
        columns: [
          {
            data: 'id',
          },
          {
            data: 'subject',
          },
          {
            data: 'html_message',
            render: function(data, type, row, meta) {
              if(type === 'display') {
                data = '<a href="/relationship-rule/download/' + data + '">' + data + '</a>';
              }
              return data;
            }
          },
          {
            data: 'order',
          },
          {
            data: 'enabled',
            render: function(data, type, row, meta) {
              if(data == 1) {
                data = 'Sim';
              } else {
                data = 'Não';
              }
              return data;
            }            
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
                  data-subject="${data.subject}"
                  data-html_message="${data.html_message}"
                  data-order="${data.order}"
                  data-enabled="${data.enabled}"
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

      this.$modalTitle.text('Cadastrar Régua de Relacionamento');
      this.$btnConfirm.text('Cadastrar');

      this.$action.val('create');

      this.$wrapUpload.removeClass('sr-only');
      this.$wrapPreview.addClass('sr-only');

      this.$modal.modal('show');
    },

    onChangeHtml: function(event){
      event.preventDefault();
      
      if ('' === event.target.value) {
        console.log('não vai upar');
        return;
      }

      this.$progress.removeClass('hidden');

      const token = $('meta[name="csrf-token"]').attr('content');

      const headers = {
        'X-CSRF-TOKEN': token,
      };

      const data = new FormData(this.$form[0]);

      const handleProgress = function() {
        const xhr = $.ajaxSettings.xhr();

        if (xhr.upload) {
          xhr.upload.addEventListener('progress', function(event) {
            if (event.lengthComputable) {
              const percentage = Math.round((event.loaded * 100) / event.total);

              $('.progress-bar').attr({
                'aria-valuenow': percentage,
                'style'        : `width: ${percentage}%`,
              });
            }
          }, false);

          xhr.upload.addEventListener('load', function(e) {
            $('.progress-bar').attr({
              'aria-valuenow': '100',
              'style'        : 'width: 100%',
            });
          }, false);

          xhr.upload.addEventListener('loadend', function(e) {
            $('.progress-bar').attr({
              'aria-valuenow': '100',
              'style'        : 'width: 100%',
            });

            $('.progress').fadeOut(1000);
          }, false);
        }

        return xhr;        
      };

      const uploading = $.ajax({
        cache      : false,
        dataType   : 'json',
        contentType: false,
        processData: false,
        method     : 'POST',
        url        : '/relationship-rule/upload',
        headers    : headers,
        data       : data,
        xhr        : handleProgress,
      });      
      
      uploading.done($.proxy(this.onFileUploadSuccess, this));

      uploading.fail($.proxy(this.onFileUploadFail, this));

    },

    onFileUploadSuccess: function(data) {
      this.$progress.addClass('hidden');
      this.$fileName.val(data.data);
    },

    onFileUploadFail: function(error) {
      console.log(error);
    },

    clearInputs: function() {
      this.$subject.val('');
      this.$html.val('');
      this.$fileName.text('');
      this.$order.val(0);
      this.$enabled.prop('checked', true);

      this.$action.val('');
      this.$id.val('');
      this.$path.val('');

      return this;
    },

    onHideModal: function() {
      this.clearInputs();
    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if (
        '' === $.trim(this.$subject.val()) ||
        ('' === $.trim(this.$html.val()) && '' === $.trim(this.$fileName.text()))
      ) {
        sweet.common.message('error', 'Imagem e assunto são campos obrigatórios');
        return;        
      }

      if ('create' === this.$action.val()) {
        this.onCreateSubmit();
      } else {
        this.onUpdateSubmit();
      }     

    },

    onCreateSubmit: function() {
      var enabledCheck = 0;
      if (this.$enabled.prop('checked')){
        enabledCheck = 1;
      } else {
        enabledCheck = 0;
      }

      console.log(enabledCheck);

      const params = {
        subject         : this.$subject.val(),
        html_message    : this.$fileName.val(),
        order           : this.$order.val(),
        enabled         : enabledCheck,
      }

      console.log(params);

      const saving = sweet.common.crud.save({
        params   : params,
        endpoint : '/relationship-rule',
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
      console.log(error);
    },

    onBtnEditClick: function(event) {
      event.preventDefault();

      this.$modalTitle.text('Editar Régua de Relacionamento');
      this.$btnConfirm.text('editar');
      this.$action.val('update');

      const $btn          = $(event.currentTarget);
      const id            = $.trim($btn.data('id'));
      const subject       = $.trim($btn.data('subject'));
      const html_message  = $.trim($btn.data('html_message'));
      const order         = $.trim($btn.data('order'));
      const enabled       = $.trim($btn.data('enabled'));

      this.$id.val(id);
      this.$subject.val(subject);
      this.$path.val(html_message);
      this.$order.val(order);
      
      if(1 == enabled) {
        this.$enabled.prop('checked', true);
      } else {
        this.$enabled.prop('checked', false);
      }
      
      this.$fileName.text(html_message);
      this.$fileName.val(html_message);

      this.$modal.modal('show');
    },

    onUpdateSubmit: function() {
      var enabledCheck = 0;
      if (this.$enabled.prop('checked')){
        enabledCheck = 1;
      } else {
        enabledCheck = 0;
      }
      const params = {
        _method       : 'put',
        _token        : $('meta[name="csrf-token"]').attr('content'),
        id            : this.$id.val(),
        subject       : this.$subject.val(),
        html_message  : this.$fileName.val(),
        order         : this.$order.val(),
        enabled       : enabledCheck,        
      };

      console.log(params);

      const saving = sweet.common.crud.save({
        params    : params,
        endpoint  : `/relationship-rule/${params.id}`,
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
      console.log(error);
    },

    onBtnDestroyClick: function(event) {
      event.preventDefault();

      const id    = $(event.currentTarget).data('id');
      const token = $('meta[name="csrf-token"]').attr('content');

      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/relationship-rule/${id}`,
        data   : {
          _method: 'delete',
          _token : token,
        }
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
    
  };

  $(function() {
    ScreenRelationshipRule.start();
  })
})(jQuery);