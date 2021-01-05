@section('script')
  <script src="{!! asset('assets/js/plugins/tabledit/jquery.tabledit.js') !!}?{{ date('hisdmY') }}"></script>
  <script src="{!! asset('assets/js/plugins/uploadfile/uploadfile.js') !!}?{{ date('hisdmY') }}"></script>
  <script src="{!! asset('assets/js/app/campaigns.js') !!}?{{ date('hisdmY') }}"></script>
  <script src="{!! asset('assets/js/app/unified-campaigns.js') !!}?{{ date('hisdmY') }}"></script>

  <script>
    (function($) {
      $('.fileUploader').uploadFile({
          url: '{{ route('upload.campaigns') }}',
          method: 'POST',
          multiple: false,
          dragDrop: true,
          maxFileCount: 1,
          returnType: "json",
          fileName: 'file',
          acceptFiles: 'image/*',
          maxFileSize:1000*1024,
          dragDropStr: '<span><b>Arraste e solte a imagem para Upload</b></span>',
          abortStr: 'Excluir',
          cancelStr: 'Cancelar',
          doneStr: 'Sucesso',
          multiDragErrorStr: 'Permitido apenas um arquivo para Upload',
          extErrorStr: 'Tipo de arquivo inválido. Extensões permitidas:',
          sizeErrorStr: 'Tamanho do arquivo ultrapassa o máximo permitido',
          uploadErrorStr: 'Upload não autorizado',
          uploadStr: 'Upload',
          showPreview: false,
          previewHeight: '200px',
          previewWidth: '200px',
          showDelete: true,
          showDownload: false,
          statusBarWidth:600,
          dragdropWidth:600,
          onSuccess:function(files,data,xhr,pd) {
            $('.ajax-file-upload-red').html('Excluir');

            if (!sweet.validate.isNull(data.name)) {
              $('.fileUploader').hide();
            }
          },

          deleteCallback: function (data, pd) {
            $.get('{{ route('delete.image.campaigns') }}', {'path': encodeURIComponent(data.path), image:data.name}, function(resp,textStatus, jqXHR) {
                if (textStatus == 'success') {
                    $('.fileUploader').show();
                }
            });

            //  pd.statusbar.hide(); //You choice.
          },

          onLoad: function(obj) {},

          downloadCallback: function(filename,pd) {
            // location.href="download.php?filename="+filename;
          }
      });

      $('.tooltip-filter').tooltip({
        selector: '[data-toggle=tooltip]',
        container: 'body',
      });

      @unless (empty($edit))
        $('#table-clickout').Tabledit({
          url: '{{ route('index.clickout.campaigns') }}',
          restoreButton: false,
          columns: {
            identifier: [0, 'id'],
            editable: [[1, 'answer'], [2, 'affirmative', '{"Sim":"Sim", "Não":"Não"}'], [3, 'link']]
          },
          buttons: {
            save: {
              html: 'Salvar'
            },
            confirm: {
              html: 'Confirma?'
            }
          },
          onDraw: function () {
            // console.log('onDraw()');
          },
          onSuccess: function (data, textStatus, jqXHR) {
            // console.log('onSuccess(data, textStatus, jqXHR)');
            // console.log(data);
            // console.log(textStatus);
            // console.log(jqXHR);
          },
          onFail: function (jqXHR, textStatus, errorThrown) {
            // console.log('onFail(jqXHR, textStatus, errorThrown)');
            // console.log(jqXHR);
            // console.log(textStatus);
            // console.log(errorThrown);
          },
          onAlways: function () {
            // console.log('onAlways()');
          },
          onAjax: function (action, serialize) {
            // console.log('onAjax(action, serialize)');
            // console.log(action);
            // console.log(serialize);
          }
        });
      @endunless
    })(jQuery);
  </script>

  @if (empty($edit))
    <script src="{{ asset('assets/js/app/campaigns-fields.js') }}"></script>
  @else
    <script>
      {{-- Inject field types JSON --}}
      window.sweet = window.sweet || {};
      window.sweet.fieldTypes = {!! $fieldTypes !!};
    </script>

    <script src="{{ asset('assets/js/app/campaigns-fields-edit.js') }}"></script>
  @endif
@endsection
