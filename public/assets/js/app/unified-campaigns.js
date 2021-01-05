(function($)  {
  const ScreenUnifiedCampaigns = {

    start: function() {
      this.$btnDown = $('[data-btn-actions-down]');
      this.$btnUp = $('[data-btn-actions-up]');
      this.$actionsWrapper = $('[data-actions-form-wrapper]');
      this.$linkToAction = $("input[name='link-to-action']");
      
      this.$selectAction = $('[data-select-action]');

      this.$linkAction = $('[data-input-action]');
      this.$progress   = $('[data-upload-progress]');

      this.$form = $('[data-form-actions]');
      this.$form2 = $('#form-create-campaign');      

      this.$id                     =  $('[data-input-id]');
      this.$path                   =  $('[data-input-path]');
      this.$action                 =  $('[data-input-action]');
      this.$category               =  $('[data-input-category]');
      this.$type                   =  $('[data-input-type]');
      this.$typeUrl                =  $('[data-input-type-url]');
      this.$title                  =  $('[data-input-title]');
      this.$description            =  $('[data-input-description]');
      this.$points                 =  $('[data-input-points]');
      this.$image                  =  $('[data-input-image]');
      this.$order                  =  $('[data-input-order]');
      this.$enabled                =  $('#action-enabled');
      this.$filter_ddd             =  $('[action_filter_ddd]');
      this.$filter_gender          =  $('[action_filter_gender]');
      this.$filter_cep             =  $('[action_filter_cep]');
      this.$filter_operation_begin =  $('[action_filter_operation_begin]');
      this.$filter_age_begin       =  $('[action_filter_age_begin]');
      this.$filter_operation_end   =  $('[action_filter_operation_end]');
      this.$filter_age_end         =  $('[action_filter_age_end]');

      this.$wrapUpload             =  $('[data-wrap-upload]');
      this.$wrapPreview            =  $('[data-wrap-preview]');

      this.bind();
    },

    bind: function() {
      this.$btnDown.on('click', $.proxy(this.onBtnDownClick, this));
      this.$btnUp.on('click', $.proxy(this.onBtnUpClick, this));
      this.$linkToAction.on('click', $.proxy(this.onLinkToActionClick, this));
      this.$linkAction.on('change', $.proxy(this.onActionChange, this));
      this.$image.on('change', $.proxy(this.onImageChange, this));
      this.$wrapPreview.on('click', '[data-destroy-image]', $.proxy(this.onDestroyImageClick, this));
      this.$filter_operation_begin.on('change', $.proxy(this.onFilterChange, this));
    },

    clearInputs: function() {
     // this.$wrapTypeUrl.addClass('sr-only');
      this.$action.selectpicker('val', '');
      this.$id.val('');
      this.$path.val('');
      this.$category.selectpicker('val', '');
      this.$type.selectpicker('val', '');
      this.$typeUrl.val('');
      this.$title.val('');
      this.$description.val('');
      this.$points.val('');
      this.$image.val('');
      this.$order.val('');
      this.$enabled.selectpicker('val', '');
      this.$filter_ddd.val('');
      this.$filter_gender.selectpicker('val', '');
      this.$filter_cep.val('');
      this.$filter_operation_begin.selectpicker('val', '');
      this.$filter_age_begin.val('');
      this.$filter_operation_end.selectpicker('val', '');
      this.$filter_age_end.val('');
      this.$wrapPreview.addClass('sr-only');
      this.$wrapUpload.removeClass('sr-only');

      return this;
    },

    onBtnDownClick: function(event) {
      if ($("input[name='actions']").prop("checked") === false) {
        return;
      }
      
      event.preventDefault();
      this.$btnDown.addClass("sr-only");
      this.$btnUp.removeClass("sr-only");
      this.$actionsWrapper.removeClass("sr-only");
    },

    onBtnUpClick: function(event) {
      event.preventDefault();
      this.$btnUp.addClass("sr-only");
      this.$btnDown.removeClass("sr-only");
      this.$actionsWrapper.addClass("sr-only");
    },

    onLinkToActionClick: function() {
      if (this.$linkToAction.prop("checked") === true) {
        // Abrir select para selecionar a ação.
        this.$selectAction.removeClass('sr-only');

      } else {
        // Ocultar select e cadastrar uma nova ação.
        this.$selectAction.addClass('sr-only');
        this.clearInputs();
      }
      
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


    onActionChange: function(event) {

      var actions_id = this.$linkAction.val();

      const token = $('meta[name="csrf-token"]').attr('content');

      const search = $.ajax({
        cache  : false,
        method : 'GET',
        url    : `/actions/get/${actions_id}`,
        data   : {
          _method: 'get',
          _token : token,
        },
      });

      search.done($.proxy(this.onSearchSuccess, this));
      search.fail($.proxy(this.onSearchFail, this));

    },

    onSearchSuccess: function(data) {
      if (data) {
        //console.log(data);

        var action = data.action;
        var actionTypeMeta = data.actionTypeMeta;

        this.$id.val(action.id);
        this.$path.val(action.path_image);
        // this.$action.val();
        this.$category.val(action.action_category_id);
        this.$category.selectpicker('refresh');

        this.$type.val(action.action_type_id);
        this.$type.selectpicker('refresh');

        this.$typeUrl.val(actionTypeMeta.value);
        this.$title.val(action.title);
        this.$description.val(action.description);
        this.$points.val(action.grant_points);

        //$('[data-input-image-path]').val(action.path_image);
        //$('[data-input-image]').val(action.path_image);        
        
        this.$wrapUpload.addClass('sr-only');

        this.$wrapPreview
        .html(`
          <div class="col-md-3">
            <img src="/storage/${action.path_image}" alt="">
          </div>
          <div class="col-md-9">
            <button class="btn btn-danger" type="button" data-path="${action.path_image}" data-destroy-image>
              Excluir
            </button>
          </div>
        `)
        .removeClass('sr-only');
        

        this.$order.val(action.order);
       
        this.$enabled.val(action.enabled);
        this.$enabled.selectpicker('refresh');

        this.$filter_ddd.val(action.filter_ddd);
        this.$filter_gender.val(action.filter_gender);
        this.$filter_gender.selectpicker('refresh');

        this.$filter_cep.val(action.filter_cep);
        this.$filter_operation_begin.val(action.filter_operation_begin);
        this.$filter_operation_begin.selectpicker('refresh');

        this.$filter_age_begin.val(action.filter_age_begin);
        this.$filter_operation_end.val(action.filter_operation_end);
        this.$filter_operation_end.selectpicker('refresh');

        this.$filter_age_end.val(action.filter_age_end);
        
      }
    },

    onSearchFail: function(error) {
      console.log(error);
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

      var form_upload;

      if(this.$form[0]) {
        form_upload = this.$form[0];
      } else {
        form_upload = this.$form2[0];
      }
      
      const data = new FormData(form_upload);

      console.log(this.$form[0]);
      console.log(this.$form2);
      console.log(data);
      
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
        url        : '/actions/upload/image',
        headers    : headers,
        data       : data,
        xhr        : handleProgress,
      });

      uploading.done($.proxy(this.onImageUploadSuccess, this));

      uploading.fail($.proxy(this.onImageUploadFail, this));
    },

    onImageUploadSuccess: function(data) {
      this.$progress.addClass('hidden');

      $('[data-input-image-path]').val(data.data.path + data.data.name);
      $('[data-input-path]').val(data.data.path + data.data.name);

    },

    onImageUploadFail: function(error) {
      console.log(error);
    },

    onDestroyImageClick: function(event) {
      event.preventDefault();

      this.$image.val('');
      this.$path.val('');
      this.$wrapPreview.addClass('sr-only');
      this.$wrapUpload.removeClass('sr-only');
    },

    onFilterChange:function(event) {
      event.preventDefault();

      const $btn = $(event.currentTarget);
      
      switch ($btn[0].value) {
        case '=':
        case '<':
        case '<=':
        case '<>':
            $('.action-block-end').hide();
            break;
        case '>':
        case '>=':


            $('.action-block-end').show();
            break;
     }
    },
    

  };

  $(function() {
    ScreenUnifiedCampaigns.start();
  })
})(jQuery);