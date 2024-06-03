<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

    <!-- plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}"
          type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" type="text/css">
    <!-- end plugin css -->

    @stack('plugin-styles')

    <!-- common css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <!-- end common css -->
    @toastr_css
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href=" {{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href=" {{ asset('assets/plugins/summernote/summernote-bs4.css') }}">


    @stack('style')
</head>
<body data-base-url="{{url('/')}}">

<div class="container-scroller" id="app">
    @yield('topItem')
    @include('layout.header')
    <div class="container-fluid page-body-wrapper">
        @include('layout.sidebar')
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@if(isset($collections_chart))
    {!! $collections_chart->script() !!}
@endif
@if(isset($gender_chart))
    {!! $gender_chart->script() !!}
@endif

<!-- base js -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<!-- end base js -->

<!-- plugin js -->
@stack('plugin-scripts')
<!-- end plugin js -->

<!-- common js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<!-- Datatables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Select 2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!--file input-->
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- end common js -->
{{--date range picker--}}
<script src="{{ asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

@toastr_js
@toastr_render

<script>
    $('.dt').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
    });

    //Initialize Select2 Elements
    $('.select2').select2();

    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    bsCustomFileInput.init();

    $('.textarea').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ]
    });

    function getLabels(data, key) {
        let labels = [];
        data.forEach(d => {
            labels.push(d[key])
        })
        return labels;
    }

    function getDataPoints(data, key) {
        let dataPoints = [];
        data.forEach(d => {
            dataPoints.push(Number(d[key]));
        })
        return dataPoints
    }

    function getMaxValue(data, key) {
        const dataPoints = getDataPoints(data, key)
        const max = dataPoints.reduce(function (a, b) {
            return Math.max(a, b);
        }, -Infinity);
        return Math.ceil(max / 10) * 10
    }

    function calculateStepSize(data, key) {
        const maxValue = getMaxValue(data, key);

        if (maxValue <= 20) {
            return 5
        } else if (maxValue > 20 && maxValue <= 100) {
            return 20
        } else if (maxValue > 100 && maxValue <= 1000) {
            return 100
        } else if (maxValue > 1000 && maxValue <= 10000) {
            return 1000
        } else if (maxValue > 10000 && maxValue <= 100000) {
            return 10000
        } else if (maxValue > 100000 && maxValue <= 1000000) {
            return 100000
        } else if (maxValue > 1000000 && maxValue <= 10000000) {
            return 1000000
        } else if (maxValue > 10000000) {
            return 10000000
        }
    }

    const getPieChartColors = (dataSet) => {
        let colors = [];
        for (let i = 0; i < dataSet.length; i++) {
            if (i > 7) {
                i = i % 7;
            }
            colors.push(ChartColor[i])
        }
        return colors;
    }

    const dateRangePickerFormats = (targetInputField) => {
      const inputObject = $('input[name="'+targetInputField+'"]')
      inputObject.daterangepicker({
        autoUpdateInput: false,
        locale: {
          cancelLabel: 'Clear'
        }
      });

      inputObject.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      });

      inputObject.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
      });
    }


    const centerConfirmDialog = (message, formId, hasForm=false) => {

      const dialogStyle = {
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        zIndex: 9999,
        backgroundColor: 'white',
        padding: '20px',
        borderRadius: '8px',
        boxShadow: '0 0 10px rgba(0, 0, 0, 0.5)',
        minHeight: '100px'
      };

      const overlay = $('<div>')
      .css({
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        background: 'rgba(0, 0, 0, 0.5)',
        zIndex: 9998,
      })
      .appendTo('body');

      const originalConfirm = window.confirm;
      window.confirm = function(message) {
        const dialog = $('<div>')
        .html(message + '<br>' +
            '<button id="confirmOk" style="margin-right: 10px; margin-top: 15px" class="btn btn-sm btn-rounded btn-success">Yes</button>' +
            '<button id="confirmCancel" style="margin-top: 15px" class="btn btn-sm btn-rounded btn-danger">Cancel</button>')
        .css(dialogStyle)
        .appendTo('body');

        $('#confirmOk').on('click', function() {
          dialog.remove();
          overlay.remove();
          if(hasForm){
            $('#'+formId).submit();
          }
        });

        // Handle Cancel button click
        $('#confirmCancel').on('click', function() {
          dialog.remove();
          overlay.remove();
        });

      };

     confirm(message)
    }

    const previewImage = (input, previewId, containerPreviewId) => {
      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
          $('#' + previewId).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('#'+containerPreviewId).removeClass('d-none')
      }else {
        $('#'+containerPreviewId).addClass('d-none')
      }
    }
</script>
@stack('custom-scripts')
</body>
</html>
