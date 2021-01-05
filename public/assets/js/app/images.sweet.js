'use strict';

sweet.images = {
  path: '',

  image: '',

  upload: function(options) {
    $(options.field).on('change', function() {
      var formData = new FormData($(options.form)[0]);

      $('.progress').show();

      $.ajax({
        url:laroute.route(options.route),

        data: formData,

        dataType:'json',

        async:false,

        type:'post',

        processData: false,

        cache: false,

        contentType: false,

        xhr: function() {
          var xhr = $.ajaxSettings.xhr();

          if (xhr.upload) {
            xhr.upload.addEventListener('progress', function (e) {
              if (e.lengthComputable) {
                var percentage = Math.round((e.loaded * 100) / e.total);

                $('.progress-bar').attr({
                  'aria-valuenow':percentage,
                  'style':'width: ' + percentage + '%',
                });
              }
            }, false);

            xhr.upload.addEventListener('load', function (e) {
              $('.progress-bar').attr({
                'aria-valuenow':'100',
                'style':'width: 100%',
              });
            }, false);

            xhr.upload.addEventListener('loadend', function (e) {
              $('.progress-bar').attr({
                'aria-valuenow':'100',
                'style':'width: 100%',
              });

              $('.progress').fadeOut(1000);
            }, false);
          }

          return xhr;
        },

        success: function(response) {
          sweet.images.path  = response.path;
          sweet.images.image = response.name;
        }
      });
    });

    return this;
  },

  deleteUploadImage: function(options) {
    if (sweet.images.path !== '' && sweet.images.image !== '') {
      $.get(laroute.route(options.route), {'path': encodeURIComponent(sweet.upload.path), image:sweet.upload.image}, function(resp, textStatus, jqXHR) {
        if (showMessage && textStatus == 'success') {
          sweet.common.message('success', 'Imagem excluida com sucesso');
        }
      });
    }

    return this;
  }
}
