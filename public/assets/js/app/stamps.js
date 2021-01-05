(function($) {
  const ScreenStamps = {

    start: function() {

      this.$btnNew  = $('[data-btn-new]');
      this.$table   = $('[data-table-stamps]');

      this.$modal       = $('[data-modal-stamps]');
      this.$modalTitle  = this.$modal.find('[data-modal-title]');
      this.$wrapPreview = this.$modal.find('[data-wrap-preview]');
      this.$btnConfirm  = this.$modal.find('[data-btn-confirm]');
      this.$wrapUpload  = this.$modal.find('[data-wrap-upload]');
      this.$wrapFile    = this.$modal.find('[data-wrap-file]');
      this.$progress    = this.$modal.find('[data-upload-progress]');

      this.$form        = $('[data-form-stamps]');
      this.$title       = this.$form.find('[data-input-title]');
      this.$description = this.$form.find('[data-input-description]');
      this.$icon        = this.$form.find('[data-input-icon]');
      this.$quantity    = this.$form.find('[data-input-quantity]');
      this.$type        = $('#type');
      
      this.$path    = this.$form.find('[data-input-path]');
      this.$id      = this.$form.find('[data-input-id]');
      this.$action  = this.$form.find('[data-input-action]');

      this.$datatable = null;

      this.bind();
      this.dataTable();
    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$btnNew.on('click', $.proxy(this.onBtnNewClick, this));
      this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
      this.$icon.on('change', $.proxy(this.onIconChange, this));
      this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this));
      this.$table.on('click', '[data-btn-destroy]', $.proxy(this.onBtnDestroyClick, this));
      this.$form.on('click', '[data-destroy-icon]', $.proxy(this.onDestroyImageClick, this));
    },

    dataTable: function() {
      this.$datatable = this.$table.DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        searching: true,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
          {
            extend: 'excel',
            title: 'exportar para excel',
            text: '<span class="fa fa-file-excel-o"></span> Excel ',            
          },
          {
            extend: 'pdf',
            title: 'exportar para pdf',
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
          url: '/stamps/search',
        },
        columns: [
          {
            data: 'id',
          },
          {
            data: 'title',
          },
          {
            data: 'description',
          },
          {
            data: 'icon',
            fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
              $(nTd).html(`<img class="stamp-preview" src="storage/${sData}">`);
            }
          },
          {
            data: 'type',
            render: function(data, type, row, meta) {
              if(data === '1') {
                data = 'Ação';
              }
              
              if(data === '2') {
                data = 'E-mail';
              }
              
              if(data === '3') {
                data = 'E-mail Incentivado';
              }

              if(data === '4') {
                data = 'Member Get Member';
              }

              if(data === '5') {
                data = 'Profile';
              }

              return data;
            }            
          },
          {
            data: 'required_amount',
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
                  data-title="${data.title}"
                  data-description="${data.description}"
                  data-icon="${data.icon}"
                  data-type="${data.type}"
                  data-quantity="${data.required_amount}"
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

      this.$modalTitle.text('Cadastrar Selo de Pontuação');
      this.$action.val('create');
      this.$btnConfirm.text('cadastrar');
      
      this.$modal.modal('show');
    },

    clearInputs: function() {
      this.$action.val('');
      this.$id.val('');
      this.$title.val('');
      this.$description.val('');
      this.$path.val('');
      this.$icon.val('');
      this.$type.selectpicker('val', '');
      this.$quantity.val('');
      $('.fileinput-filename').text('');
      this.$wrapPreview.addClass('sr-only');
      this.$wrapUpload.removeClass('sr-only');
      location.reload();

      return this;
    },

    onHideModal: function() {
      this.clearInputs();
    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if ('' == this.$title.val()        ||
          '' == this.$description.val()  ||
          '' == this.$quantity.val()     ||
          '' == this.$type.val()         ||
          '' == (this.$icon.val() || this.$path.val())
      ) {
        sweet.common.message('error', 'Todos os campos são obrigatórios');
        return;
      }

      if ('create' === this.$action.val()) {
        this.onCreateSubmit();
      } else {
        this.onUpdateSubmit();
      }
      
      this.$wrapFile.fileinput('clear');

    },

    onCreateSubmit: function() {
      const params = {
        title              : this.$title.val(),
        description        : this.$description.val(),
        icon               : this.$icon.val(),
        type               : this.$type.val(),
        required_amount    : this.$quantity.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: '/stamps',
      });      

      saving.done($.proxy(this.onCreateSuccess, this));

      saving.fail($.proxy(this.onCreateFail, this));

    },

    onCreateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.$datatable.ajax.reload().desc;
        sweet.common.message('success', 'Dados cadastrados com sucesso!');
      }
    },

    onCreateFail: function(error) {
      console.log(error);
    },

    onBtnEditClick: function(event) {
      event.preventDefault();

      this.$modalTitle.text('Editar Selo de Pontuação');
      this.$btnConfirm.text('editar');
      this.$action.val('update');
      
      var types = ["action", "email", "incentive_email", "member_get_member"];

      const $btn          =   $(event.currentTarget);
      const id            =   $.trim($btn.data('id'));
      const title         =   $.trim($btn.data('title'));
      const description   =   $.trim($btn.data('description'));
      const icon          =   $.trim($btn.data('icon'));
      const type          =   types.indexOf($.trim($btn.data('type')))+1
      const quantity      =   $.trim($btn.data('quantity'));

      this.$id.val(id);
      this.$title.val(title);
      this.$description.val(description);
      this.$path.val(icon);
      $('#type').selectpicker('refresh');
      $('#type').selectpicker('val', type);
      this.$quantity.val(quantity);

      this.$wrapUpload.addClass('sr-only');
      
      this.$wrapPreview
        .html(`
          <div class="col-md-3">
            <img class="stamp-preview" src="storage/${icon}" alt="">
          </div>
          <div class="col-md-9">
            <button class="btn btn-danger" type="button" data-path="${icon}" data-destroy-icon>
              Excluir
            </button>
          </div>
        `)
        .removeClass('sr-only');

        this.$modal.modal('show');
    },

    onUpdateSubmit: function(event) {

      const params = {
        _method         : 'put',
        _token          : $('meta[name="csrf-token"]').attr('content'),
        id              : this.$id.val(),
        title           : this.$title.val(),
        description     : this.$description.val(),
        icon            : this.$path.val(),
        type            : this.$type.val(),
        required_amount : this.$quantity.val()                  
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: `/stamps/${params.id}`,
      });

      saving.done($.proxy(this.onUpdateSuccess, this));

      saving.fail($.proxy(this.onUpdateFail, this));      

    },

    onUpdateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.$datatable.ajax.reload().desc;
        sweet.common.message('success', 'Dados atualizados com sucesso!');
      }
    },

    onUpdateFail: function(error) {
      console.log(error);
    },

    onIconChange: function(event) {
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
        url        : '/stamps/upload',
        headers    : headers,
        data       : data,
        xhr        : handleProgress,
      });

      uploading.done($.proxy(this.onImageUploadSuccess, this));

      uploading.fail($.proxy(this.onImageUploadFail, this));
    },

    onImageUploadSuccess: function(data) {
      this.$path.val(data.data.path + data.data.name);
      this.$progress.addClass('hidden');
    },

    onImageUploadFail: function(error) {
      console.log(error);
    },

    onDestroyImageClick: function(event) {
      event.preventDefault();

      this.$icon.val('');
      this.$path.val('');
      this.$wrapPreview.addClass('sr-only');
      this.$wrapUpload.removeClass('sr-only');

    },

    onBtnDestroyClick: function(event){
      event.preventDefault();

      
      const id    = $(event.currentTarget).data('id');
      const token = $('meta[name="csrf-token"]').attr('content');
      
      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/stamps/${id}`,
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
        sweet.common.message('success', 'Selo excluido com sucesso!');
        this.$datatable.ajax.reload().desc;
      }
    },

    onDestroyFail: function(error) {
      console.log(error);
    },


  };

  $(function() {
    ScreenStamps.start();
  })
})(jQuery);