<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if(isset($data['title'])) echo $data['title']; else echo "Power Bi"; ?></title>

	<!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
    <!-- Cursor -->
    <link rel="stylesheet" href="{{asset('dist/css/cursor.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">
    <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <!--   ***********************************  -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--   ***********************************  -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- google chart -->
    {{--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>--}}
    <style>
        .dropdown .dropdown-menu {
        background-color: rgba(255, 255, 255, 0.8);
        }
        td.details-control {
        background: url('{{asset('dist/img/avatar5.png')}}');
        cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{asset('dist/img/avatar5.png')}}');
            height:10px;
            width:10px;
        }
    </style>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" href="{{asset('custom/css/vreport.css')}}"/>
    <script type="text/javascript">
        const numberWithCommas = (x) => {
          return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    // $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    //     $("#success-alert").slideUp(500);
    // });
    // $("#danger").hide();
    </script>
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
    <div id="myModal" class="modal fade row">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="box-body">
              <img style="width:100%" src="{{asset('dist/img/loading.gif')}}" alt="Loading.."/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div id="chartModal" class="modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <div id="chartModalCloseButton"></div>
                    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    <h4 id="chartModalTitle" class="modal-title"></h4>
                </div>
                <div  class="modal-body">
                    <div class="chart">
                        <div id="chartModalBody" style="height:360px" ><b></b></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="modalClose" type="button" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="app">
        @include('inc.nav')
<!--                 @if(Session::has('message'))
            <div class="alert alert-info">
                <p>{{ Session::get('message') }}</p>
            </div>
        @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif -->
            @yield('content')
        <footer class="main-footer">
            <strong>Copyright © 2018 <a href="">Power BI</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <!-- jQuery 2.1.4 -->
     <script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- Knob -->
    <script src="{{asset('plugins/knob/jquery.knob.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('plugins/fastclick/fastclick.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/app.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    {{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>--}}
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>


    <script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
    </script>
</body>
</html>
