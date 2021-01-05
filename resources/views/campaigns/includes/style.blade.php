<link href="{!! asset('assets/css/plugins/uploadfile/uploadfile.css') !!}" type="text/css" rel="stylesheet" >

<style type="text/css">
    #primary-key { display: none; }
    .active, .disabled { font-size: 30px; }
    .active { color: #228B22; }
    .disabled { color: #B22222; }
    .ajax-upload-dragdrop { width: 100% !important; border: 2px dotted #E5E6E7; }
    .ajax-file-upload { background-color: #1ab394; -webkit-box-shadow: 0 2px 0 0 #078469; box-shadow: 0 2px 0 0 #078469; height: 30px; }
    .ajax-file-upload:hover { background-color: #179e80; -webkit-box-shadow: 0 2px 0 0 #117760; box-shadow: 0 2px 0 0 #117760; }
    .ajax-file-upload-statusbar {width: 100% !important; border: 1px solid #eee;}
    legend { font-size: 15px;}
    .ajax-upload-dragdrop { min-height: 105px; }
    .type, .coreg-simples {display: none;}

    .scrollbox {
        height: 200px;
        overflow:auto
    }
    .ajax-file-upload-filename { width: 100% !important; }

    .switch {
        position: fixed;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin: -10px 0px 0px 10px;
    }

    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #B22222;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #228B22;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #228B22;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .btn-save-all { display: none; }
</style>


{{-- <Fields metadata> --}}

<style>
  .fields-metadata {
    margin-top: 20px;
  }

  .table.table-fields > tbody > tr > td {
    vertical-align: middle;
  }

  .table-fields .btn {
    margin-bottom: 0;
  }
</style>

{{-- </Fields metadata> --}}
