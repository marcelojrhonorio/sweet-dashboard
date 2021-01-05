{{-- Mainly scripts --}}
<script src="{{ asset('assets/js/library/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/js/library/bootstrap.min.js') }}"></script>

{{-- Custom and plugin javascript --}}
<script src="{{ asset('assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('assets/js/library/inspinia.js') }}"></script>
<script src="{{ asset('assets/js/plugins/pace/pace.min.js') }}"></script>

{{-- Toastr script --}}
<script src="{{ asset('assets/js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jsTree/jstree.min.js') }}"></script>

{{-- Ladda --}}
<script src="{{ asset('assets/js/plugins/ladda/spin.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/ladda/ladda.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/ladda/ladda.jquery.min.js') }}"></script>

{{-- jasny --}}
<script src="{{ asset('assets/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

{{-- mask --}}
<script src="{{ asset('assets/js/plugins/mask/jquery.mask.min.js') }}"></script>

{{-- sweetalert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.29.2/sweetalert2.all.js"></script>
<script src="{{ asset('assets/js/plugins/dataTables/datatables.min.js') }}"></script>

{{-- select --}}
<script src="{{ asset('assets/js/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-select/i18n/defaults-pt_BR.js') }}"></script>

{{-- routes --}}
<script src="{{ asset('assets/js/laroute.js') }}"></script>

{{-- app --}}
<script src="{{ asset('assets/js/app/sweet.js') }}"></script>
<script src="{{ asset('assets/js/app/validate.js') }}"></script>

<script>
  sweet.common.setTimeOut({{ env('AJAX_TIMEOUT') }});
  
  sweet.common.url = '{{env('APP_URL')}}';
  
  sweet.icheck = {
    init: function() {
      $('input.icheckbox').iCheck({
        'checkboxClass': 'icheckbox_square-green',
        'radioClass'   : 'iradio_square-green',
        'increaseArea' : '20%',
      });
    }
  };
  
  (function($) {
    'use strict';
    
    sweet.icheck.init();
    
    toastr.options = {
      'closeButton'      : true,
      'debug'            : false,
      'newestOnTop'      : false,
      'progressBar'      : true,
      'positionClass'    : 'toast-top-center',
      'preventDuplicates': false,
      'onclick'          : null,
      'showDuration'     : 400,
      'hideDuration'     : 1000,
      'timeOut'          : 7000,
      'extendedTimeOut'  : 1000,
      'showEasing'       : 'swing',
      'hideEasing'       : 'linear',
      'showMethod'       : 'fadeIn',
      'hideMethod'       : 'fadeOut',
    };
    
    @if(Session::has('flash_message'))
    var className = '{{ Session::get('flash_message')['class'] }}';
    var title     = '{{ Session::get('flash_message')['title'] }}';
    var message   = '{{ Session::get('flash_message')['message'] }}';
    
    toastr[className](message, title);
    @endif
  })(jQuery);
  </script>

@yield('script')
