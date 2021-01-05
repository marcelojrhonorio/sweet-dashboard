(function($) {
  const ScreenActions = {
    selectors: {
      modal      : '[data-modal]',
      btnNew     : '[data-btn-new]',
      btnConfirm : '[data-btn-confirm]',
      title      : '[data-modal-title]',
      form       : '[data-form-actions]',
      wrapTypeUrl: '[data-wrap-type-url]',
      wrapUpload : '[data-wrap-upload]',
      progress   : '[data-upload-progress]',
      wrapPreview: '[data-wrap-preview]',
      table      : '[data-table-actions]',
      btnFilter  : '[data-refresh-filter]',
      textFilter : '[filter-users-text]',
      usersFilter: '[data-filter-users]',
      inputs     : {
        id                     : '[data-input-id]',
        path                   : '[data-input-path]',
        action                 : '[data-input-action]',
        category               : '[data-input-category]',
        type                   : '[data-input-type]',
        typeUrl                : '[data-input-type-url]',
        title                  : '[data-input-title]',
        description            : '[data-input-description]',
        points                 : '[data-input-points]',
        image                  : '[data-input-image]',
        order                  : '[data-input-order]',
        enabled                : '#enabled',
        filter_ddd             : '[filter_ddd]',
        filter_gender          : '[filter_gender]',
        filter_cep             : '[filter_cep]',
        filter_operation_begin : '[filter_operation_begin]',
        filter_age_begin       : '[filter_age_begin]',
        filter_operation_end   : '[filter_operation_end]',
        filter_age_end         : '[filter_age_end]',
      },
    },

    $btnNew: null,

    $btnConfirm: null,

    $modal: null,

    $title: null,

    $form: null,

    $table: null,

    $btnFilter: null,

    $textFilter: null,

    $usersFilter: null,

    $wrapTypeUrl: null,

    $wrapUpload: null,

    $progress: null,

    $wrapFile: null,

    $wrapPreview: null,

    inputs: {
      $id                     : null,
      $action                 : null,
      $category               : null,
      $type                   : null,
      $typeUrl                : null,
      $title                  : null,
      $description            : null,
      $points                 : null,
      $image                  : null,
      $path                   : null,
      $order                  : null,
      $enabled                : null,
      $filter_ddd             : null,
      $filter_gender          : null,
      $filter_cep             : null,
      $filter_operation_begin : null,
      $filter_age_begin       : null,
      $filter_operation_end   : null,
      $filter_age_end         : null,
    },

    dataTable: null,

    dataTableOptions: {
      processing: true,
      serverSide: true,
      pageLength: 25,
      destroy   : true,
      searching : true,
      responsive: true,
      dom       : '<"html5buttons"B>lTfgitp',
      language  : {
        url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json'
      },
      ajax: {
        url: '/actions/search',
      },
      columns: [{
        data: 'id',
      }, {
        data: 'title',
      }, {
        data: 'description',
      }, {
        data: 'path_image',
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {          
          if(sData.match(/sm-exchange/)){
            var url_store = $('[data-store-url]').val();
            $(nTd).html(`<img src="${url_store}/storage/${sData}">`);
          } else {
            $(nTd).html(`<img src="storage/${sData}">`);
          }
        }
      }, {
        data: 'order'
      }, {
        data : 'enabled',
        width: '3%',
        render: function(data, type, row) {
            return sweet.common.iconStatusApp(row.enabled);
        },                  
      }, {
        data: 'grant_points'
      }, {
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
              data-category="${data.action_category_id}"
              data-type="${data.action_type_id}"  
              data-type-url="${ ((data.action_type_metas).length > 0) ? data.action_type_metas[0].value : null}"
              data-title="${data.title}"
              data-description="${data.description}"
              data-grant-points="${data.grant_points}"
              data-image="${data.path_image}"
              data-order="${data.order}"
              data-enabled="${data.enabled}"
              data-filter_ddd="${data.filter_ddd}"
              data-filter_gender="${data.filter_gender}"
              data-filter_cep="${data.filter_cep}"
              data-filter_operation_begin="${data.filter_operation_begin}"
              data-filter_age_begin="${data.filter_age_begin}"
              data-filter_operation_end="${data.filter_operation_end}"
              data-filter_age_end="${data.filter_age_end}"
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
      }],
      buttons: [{
        extend: 'excel',
        title: 'actions',
        text: '<span class="fa fa-file-excel-o"></span> Excel ',
      }, {
        extend: 'pdf',
        title: 'actions',
        text: '<span class="fa fa-file-pdf-o"></span> PDF ',
      }, {
        extend: 'print',
        text: '<span class="fa fa-print"></span> Imprimir ',
        customize: function (win) {
          $(win.document.body).addClass('white-bg');
          $(win.document.body).css('font-size', '10px');
          $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
        },
      }]
    },

    start: function() {
      this.$btnNew      = $(this.selectors.btnNew);
      this.$btnFilter   = $(this.selectors.btnFilter);
      this.$modal       = $(this.selectors.modal);
      this.$btnConfirm  = this.$modal.find(this.selectors.btnConfirm);
      this.$title       = this.$modal.find(this.selectors.title);
      this.$form        = this.$modal.find(this.selectors.form);
      this.$wrapTypeUrl = this.$modal.find(this.selectors.wrapTypeUrl);
      this.$wrapUpload  = this.$modal.find(this.selectors.wrapUpload);
      this.$progress    = this.$modal.find(this.selectors.progress);
      this.$wrapFile    = this.$modal.find(this.selectors.wrapFile);
      this.$wrapPreview = this.$modal.find(this.selectors.wrapPreview);
      this.$textFilter  = this.$modal.find(this.selectors.textFilter);
      this.$usersFilter = this.$modal.find(this.selectors.usersFilter);

      this.inputs.$id                     = this.$form.find(this.selectors.inputs.id);
      this.inputs.$path                   = this.$form.find(this.selectors.inputs.path);
      this.inputs.$action                 = this.$form.find(this.selectors.inputs.action);
      this.inputs.$category               = this.$form.find(this.selectors.inputs.category);
      this.inputs.$type                   = this.$form.find(this.selectors.inputs.type);
      this.inputs.$typeUrl                = this.$form.find(this.selectors.inputs.typeUrl);
      this.inputs.$title                  = this.$form.find(this.selectors.inputs.title);
      this.inputs.$description            = this.$form.find(this.selectors.inputs.description);
      this.inputs.$points                 = this.$form.find(this.selectors.inputs.points);
      this.inputs.$image                  = this.$form.find(this.selectors.inputs.image);
      this.inputs.$order                  = this.$form.find(this.selectors.inputs.order);
      this.inputs.$enabled                = this.$form.find(this.selectors.inputs.enabled);
      this.inputs.$filter_ddd             = this.$form.find(this.selectors.inputs.filter_ddd);
      this.inputs.$filter_gender          = this.$form.find(this.selectors.inputs.filter_gender);
      this.inputs.$filter_cep             = this.$form.find(this.selectors.inputs.filter_cep);
      this.inputs.$filter_operation_begin = this.$form.find(this.selectors.inputs.filter_operation_begin);
      this.inputs.$filter_age_begin       = this.$form.find(this.selectors.inputs.filter_age_begin);
      this.inputs.$filter_operation_end   = this.$form.find(this.selectors.inputs.filter_operation_end);
      this.inputs.$filter_age_end         = this.$form.find(this.selectors.inputs.filter_age_end);

      this.$wrapPoints = $('[data-wrap-points]');

      this.$table = $(this.selectors.table);

      this.bind();
      this.dataTable = this.$table.DataTable(this.dataTableOptions);

      return this;
    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$btnNew.on('click', $.proxy(this.onBtnNewClick, this));
      this.$btnFilter.on('click', $.proxy(this.onBtnFilterClick, this));
      this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
      this.inputs.$image.on('change', $.proxy(this.onImageChange, this));
      this.inputs.$type.on('change', $.proxy(this.onTypeChange, this));
      this.inputs.$filter_operation_begin.on('change', $.proxy(this.onFilterChange, this));
      this.inputs.$type.on('click', $.proxy(this.onTypeClick, this));
      this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this));
      this.$table.on('click', '[data-btn-destroy]', $.proxy(this.onBtnDestroyClick, this));
      this.$form.on('click', '[data-destroy-image]', $.proxy(this.onDestroyImageClick, this));

      return this;
    },

    showModal: function() {
      this.$modal.modal('show');
      return this;
    },

    clearInputs: function() {
      this.$wrapTypeUrl.addClass('sr-only');
      this.inputs.$action.val('');
      this.inputs.$id.val('');
      this.inputs.$path.val('');
      this.inputs.$category.selectpicker('val', '');
      this.inputs.$type.selectpicker('val', '');
      this.inputs.$typeUrl.val('');
      this.inputs.$title.val('');
      this.inputs.$description.val('');
      this.inputs.$points.val('');
      this.inputs.$image.val('');
      this.inputs.$order.val('');
      this.inputs.$enabled.val('');
      this.inputs.$filter_ddd.val('');
      this.inputs.$filter_gender.val('');
      this.inputs.$filter_cep.val('');
      this.inputs.$filter_operation_begin.val('');
      this.inputs.$filter_age_begin.val('');
      this.inputs.$filter_operation_end.val('');
      this.inputs.$filter_age_end.val('');

      return this;
    },

    onFilterChange:function(event) {
      event.preventDefault();

      const $btn = $(event.currentTarget);
      
      switch ($btn[0].value) {
        case '=':
        case '<':
        case '<=':
        case '<>':
            $('.block-end').hide();
            break;
        case '>':
        case '>=':


            $('.block-end').show();
            break;
     }
    },
   
    

    onBtnNewClick: function(event) {
      event.preventDefault();

      this.$title.text('Cadastrar Ação');
      this.$btnConfirm.text('Cadastrar');

      this.inputs.$action.val('create');

      this.$wrapUpload.removeClass('sr-only');
      this.$wrapPreview.addClass('sr-only');

      this.showModal();
    },

    onBtnEditClick: function(event) {
      event.preventDefault();

      const $btn = $(event.currentTarget);

      const id                     = $.trim($btn.data('id'));
      const category               = $.trim($btn.data('category'));
      const type                   = $.trim($btn.data('type'));
      const typeUrl                = $.trim($btn.data('type-url'));
      const title                  = $.trim($btn.data('title'));
      const description            = $.trim($btn.data('description'));
      const grantPoints            = $.trim($btn.data('grant-points'));
      const image                  = $.trim($btn.data('image'));
      const order                  = $.trim($btn.data('order'));
      const enabled                = $.trim($btn.data('enabled'));
      const filter_ddd             = $.trim($btn.data('filter_ddd'));
      const filter_gender          = $.trim($btn.data('filter_gender'));
      const filter_cep             = $.trim($btn.data('filter_cep'));
      const filter_operation_begin = $.trim($btn.data('filter_operation_begin'));
      const filter_age_begin       = $.trim($btn.data('filter_age_begin'));
      const filter_operation_end   = $.trim($btn.data('filter_operation_end'));
      const filter_age_end         = $.trim($btn.data('filter_age_end'));

      this.$title.text('Editar Ação');
      this.$btnConfirm.text('Salvar');

      this.inputs.$action.val('update');
      this.inputs.$id.val(id);
      this.inputs.$path.val(image);
      this.inputs.$category.selectpicker('val', category);
      this.inputs.$type.selectpicker('val', type);
      this.inputs.$typeUrl.val(typeUrl);
      this.inputs.$title.val(title);
      this.inputs.$description.val(description);
      this.inputs.$points.val(grantPoints);
      this.inputs.$order.val(order);
      this.inputs.$enabled.val(enabled);
      this.inputs.$filter_ddd.val(filter_ddd);
      this.inputs.$filter_gender.val(filter_gender);
      this.inputs.$filter_cep.val(filter_cep);
      this.inputs.$filter_operation_begin.val(filter_operation_begin);
      this.inputs.$filter_age_begin.val(filter_age_begin);
      this.inputs.$filter_operation_end.val(filter_operation_end);
      this.inputs.$filter_age_end.val(filter_age_end);

      $('#enabled').selectpicker('refresh');
      $('#filter_gender').selectpicker('refresh');
      $('#filter_operation_begin').selectpicker('refresh');
      $('#filter_operation_end').selectpicker('refresh');

      this.$wrapTypeUrl.removeClass('sr-only');
      this.$wrapUpload.addClass('sr-only');

      this.$wrapPreview
        .html(`
          <div class="col-md-3">
            <img src="storage/${image}" alt="">
          </div>
          <div class="col-md-9">
            <button class="btn btn-danger" type="button" data-path="${image}" data-destroy-image>
              Excluir
            </button>
          </div>
        `)
        .removeClass('sr-only');

      this.showModal();
    },

    onTypeClick: function(event) {
      event.preventDefault();

      $select = $(event.target);

      if (1 === $select.find('option').length) {
        $select.change();
      }
    },

    onTypeChange: function(event) {
      event.preventDefault();

      const type = event.target.value.trim();
      this.$wrapPoints.removeClass('sr-only');
      
      if (5 == type) {
        this.$wrapPoints.addClass('sr-only');
        this.inputs.$points.val(0);
      }

      this.$wrapTypeUrl.removeClass('sr-only');
    },

    onImageChange: function(event) {
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
        url        : '/actions/upload',
        headers    : headers,
        data       : data,
        xhr        : handleProgress,
      });

      uploading.done($.proxy(this.onImageUploadSuccess, this));

      uploading.fail($.proxy(this.onImageUploadFail, this));
    },

    onImageUploadSuccess: function(data) {
      this.$progress.addClass('hidden');
    },

    onImageUploadFail: function(error) {
      console.log(error);
    },

    onDestroyImageClick: function(event) {
      event.preventDefault();

      this.inputs.$image.val('');
      this.inputs.$path.val('');
      this.$wrapPreview.addClass('sr-only');
      this.$wrapUpload.removeClass('sr-only');
    },

    onBtnDestroyClick: function(event) {
      event.preventDefault();

      const id    = $(event.currentTarget).data('id');
      const token = $('meta[name="csrf-token"]').attr('content');

      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/actions/${id}`,
        data   : {
          _method: 'delete',
          _token : token,
        },
      });

      destroying.done($.proxy(this.onDestroySuccess, this));

      destroying.fail($.proxy(this.onDestroyFail, this));
    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if (
        '' === $.trim(this.inputs.$category.val())    ||
        '' === $.trim(this.inputs.$type.val())        ||
        '' === $.trim(this.inputs.$typeUrl.val())     ||
        '' === $.trim(this.inputs.$title.val())       ||
        '' === $.trim(this.inputs.$description.val()) ||
        '' === $.trim(this.inputs.$points.val())      ||
        '' === $.trim(this.inputs.$order.val())       ||
        '' === $.trim(this.inputs.$enabled.val())     ||
        '' === ($.trim(this.inputs.$image.val()) || $.trim(this.inputs.$path.val()))
      ) {
        sweet.common.message('error', 'Todos os campos são obrigatórios');
        return;
      }

      if ('create' === this.inputs.$action.val()) {
        this.onCreateSubmit();
      } else {
        this.onUpdateSubmit();
      }

      this.$wrapFile.fileinput('clear');
    },

    onCreateSubmit: function() {
      const params = {
        category               : this.inputs.$category.val(), 
        type                   : this.inputs.$type.val(),
        typeUrl                : this.inputs.$typeUrl.val(),
        title                  : this.inputs.$title.val(),
        description            : this.inputs.$description.val(),
        points                 : this.inputs.$points.val(),
        image                  : this.inputs.$image.val(),
        order                  : this.inputs.$order.val(),
        enabled                : this.inputs.$enabled.val(),
        filter_gender          : this.inputs.$filter_gender.val(), 
        filter_operation_begin : this.inputs.$filter_operation_begin.val(), 
        filter_age_begin       : this.inputs.$filter_age_begin.val(), 
        filter_operation_end   : this.inputs.$filter_operation_end.val(),
        filter_age_end         : this.inputs.$filter_age_end.val(), 
        filter_ddd             : this.inputs.$filter_ddd.val(), 
        filter_cep             : this.inputs.$filter_cep.val(),
        exchange_id            : null,
      };

      const saving = sweet.common.crud.save({
        params  : params, 
        endpoint: '/actions',
      });

      saving.done($.proxy(this.onCreateSuccess, this));

      saving.fail($.proxy(this.onCreateFail, this));
    },

    onCreateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.dataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados cadastrados com sucesso!');
      }
    },

    onCreateFail: function(error) {
      console.log(error);
    },

    onUpdateSubmit: function() {
      const params = {
        _method                : 'put',
        _token                 : $('meta[name="csrf-token"]').attr('content'),
        id                     : this.inputs.$id.val(),
        category               : this.inputs.$category.val(),
        type                   : this.inputs.$type.val(),
        typeUrl                : this.inputs.$typeUrl.val(),
        title                  : this.inputs.$title.val(),
        description            : this.inputs.$description.val(),
        points                 : this.inputs.$points.val(),
        image                  : this.inputs.$path.val(),
        order                  : this.inputs.$order.val(),
        enabled                : this.inputs.$enabled.val(),
        filter_gender          : this.inputs.$filter_gender.val(),
        filter_operation_begin : this.inputs.$filter_operation_begin.val(),
        filter_age_begin       : this.inputs.$filter_age_begin.val(),
        filter_operation_end   : this.inputs.$filter_operation_end.val(),
        filter_age_end         : this.inputs.$filter_age_end.val(),
        filter_ddd             : this.inputs.$filter_ddd.val(),
        filter_cep             : this.inputs.$filter_cep.val(),
        exchange_id            : null,
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: `/actions/${params.id}`,
      });

      saving.done($.proxy(this.onUpdateSuccess, this));

      saving.fail($.proxy(this.onUpdateFail, this));
    },

    onUpdateSuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.dataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados atualizados com sucesso!');
      }
    },

    onUpdateFail: function(error) {
      console.log(error);
    },

    onDestroySuccess: function(data) {
      if (data.success) {
        this.$modal.modal('hide');
        this.dataTable.ajax.reload().desc;
        sweet.common.message('success', 'Ação excluida com sucesso!');
      }
    },

    onDestroyFail: function(error) {
      console.log(error);
    },

    onHideModal: function() {
      this.clearInputs();
    },

    getFilterValues: function()
    {          
      return {
          'filter_gender' : $('[filter_gender]').val(),
          'filter_operation_begin' : $('[filter_operation_begin]').val(),
          'filter_age_begin' : $('[filter_age_begin]').val(),
          'filter_operation_end' : $('[filter_operation_end]').val(),
          'filter_age_end' : $('[filter_age_end]').val(),
          'filter_ddd' : $('[filter_ddd]').val(),
          'filter_cep' : $('[filter_cep]').val(),
          'filter_users' : $('[data-filter-users]').val(),              
      }
    },

    onBtnFilterClick: function(event) {

      var val = this.getFilterValues();   

      const token = $('meta[name="csrf-token"]').attr('content');

      const searching = $.ajax({ 
          method: 'POST',
          url: '/actions/search-filter',
          contentType: 'application/json',
          data: JSON.stringify({
            _token: token,
            values: val,
            dataType: 'json',
          }),
      })
  
      searching.done($.proxy(this.onSearchingSuccess, this));
      searching.fail($.proxy(this.onSearchingFail, this)); 

    },

    onSearchingSuccess: function(data) {

      this.$textFilter.html(data);
      this.$usersFilter.val(data);

    },

    onSearchingFail: function(error) {
      console.log(error);
    },
  };

  $(function() {
    ScreenActions.start();
  });
})(jQuery);
