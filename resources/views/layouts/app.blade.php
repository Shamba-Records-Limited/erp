<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Argon Dashboard') }}</title>
    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
</head>

<body class="{{ $class ?? '' }}">
    @auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.navbars.sidebar')
    @endauth

    <div class="main-content">
        @include('layouts.navbars.navbar')
        @yield('topItem')
        @include('layout.export-dialog')

        <div class="content-wrapper">
            <div class="d-flex" id='wallet_cont'>

            </div>
            @yield('content')
        </div>

        @guest()
        @include('layouts.footers.guest')
        @endguest

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        @stack('js')
        {{--date range picker--}}
        <script src="{{ asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
        <script>
        document.body.addEventListener('htmx:configRequest', (event) => {
            event.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="_token"]').content;
        });

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
            const max = dataPoints.reduce(function(a, b) {
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
            const inputObject = $('input[name="' + targetInputField + '"]')
            inputObject.daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            inputObject.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            });

            inputObject.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        }


        const centerConfirmDialog = (message, formId, hasForm = false) => {

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
                        '<button id="confirmCancel" style="margin-top: 15px" class="btn btn-sm btn-rounded btn-danger">Cancel</button>'
                    )
                    .css(dialogStyle)
                    .appendTo('body');

                $('#confirmOk').on('click', function() {
                    dialog.remove();
                    overlay.remove();
                    if (hasForm) {
                        $('#' + formId).submit();
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

                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('#' + containerPreviewId).removeClass('d-none')
            } else {
                $('#' + containerPreviewId).addClass('d-none')
            }
        }

        // load wallet details
        $.ajax({
            url: '{{route("wallet.details")}}',
            method: 'get',
            success: function(resp) {
                if (resp.has_wallet) {
                    for (let w of resp.wallets) {
                        let balance = Number(w.balance).toLocaleString()
                        let elem = `<div class="p-2 border rounded bg-light">`
                        elem += `<span class="border p-1">Wallet</span>`
                        elem +=
                            ` Acc No: <span class="font-weight-bold text-primary">${w.acc_number}</span>&nbsp`
                        elem += ` Bal: <span class="font-weight-bold text-primary">KES ${balance}</span>`
                        elem += `</div>`
                        $("#wallet_cont").append(elem)
                    }
                }
            },
            error: function(errResp) {
                alert(errResp);
            }
        })

        function printContent(content) {
            var originalContent = document.body.innerHTML;

            // Temporarily replace the entire body with the content to print
            document.body.innerHTML = content;

            // Trigger the print dialog
            window.print();

            // Restore the original content after printing
            document.body.innerHTML = originalContent;
        }

        // export dialog script
        function updateStartAndEndDate() {
            let range = $('#dateRange').val();

            if (range == 'custom') {
                $('#startDate').prop('readonly', false);
                $('#endDate').prop('readonly', false);
            } else {
                $('#startDate').prop('readonly', true);
                $('#endDate').prop('readonly', true);
            }

            let today = new Date();
            if (range == 'today') {
                $('#startDate').val(new Date().toISOString().split('T')[0]);
                $('#endDate').val(new Date().toISOString().split('T')[0]);
            } else if (range == 'yesterday') {
                let yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                $('#startDate').val(yesterday.toISOString().split('T')[0]);
                $('#endDate').val(today.toISOString().split('T')[0]);
            } else if (range == 'last7days') {
                let last7days = new Date();
                last7days.setDate(last7days.getDate() - 7);
                $('#startDate').val(last7days.toISOString().split('T')[0]);
                $('#endDate').val(today.toISOString().split('T')[0]);
            } else if (range == 'last30days') {
                let last30days = new Date();
                last30days.setDate(last30days.getDate() - 30);
                $('#startDate').val(last30days.toISOString().split('T')[0]);
                $('#endDate').val(today.toISOString().split('T')[0]);
            } else if (range == 'last60days') {
                let last60days = new Date();
                last60days.setDate(last60days.getDate() - 60);
                $('#startDate').val(last60days.toISOString().split('T')[0]);
                $('#endDate').val(today.toISOString().split('T')[0]);
            }
        }

        function showExportDialog(title = 'Export Data') {
            $('#exportModalLabel').text(title);
            $('#exportModal').modal('show');
        }

        function dismissExportDialog() {
            $('#exportModal').modal('hide');
        }

        $(document).ready(function() {
            updateStartAndEndDate();
        });

        $('#dateRange').change(function() {
            updateStartAndEndDate();
        });

        function createPaginationElem(current_page, last_page, onPageClick) {
            let onSides = 1;
            let nav = document.createElement('nav');

            let paginationUl = document.createElement('ul');
            paginationUl.className = 'pagination';
            nav.appendChild(paginationUl);

            // Add prev
            let prevLi = document.createElement('li');
            prevLi.className = 'page-item';
            if (current_page == 1) {
                prevLi.className += ' disabled';
            }
            let prevLink = document.createElement('a');
            prevLink.className = 'page-link';
            prevLink.href = '#';
            prevLink.innerHTML = 'Prev';
            prevLink.onclick = function() {
                onPageClick(current_page - 1);
            }
            prevLi.appendChild(prevLink);
            paginationUl.appendChild(prevLi);

            // Loop through
            for (let i = 1; i <= last_page; i++) {
                // Define offset
                let offset = (i == 1 || last_page) ? onSides + 1 : onSides;
                // If added
                if (i == 1 || (current_page - offset <= i && current_page + offset >= i) ||
                    i == current_page || i == last_page) {
                    let pageLi = document.createElement('li');
                    pageLi.className = 'page-item';
                    if (i == current_page) {
                        pageLi.className += ' active';
                        pageLi.ariaCurrent = 'page';
                    }

                    let pageLink = document.createElement('a');
                    pageLink.className = 'page-link';
                    pageLink.href = '#';
                    pageLink.innerHTML = i;
                    pageLink.onclick = function() {
                        onPageClick(i);
                    }
                    pageLi.appendChild(pageLink);
                    paginationUl.appendChild(pageLi);
                } else if (i == current_page - (offset + 1) || i == current_page + (offset + 1)) {
                    let pageLi = document.createElement('li');
                    pageLi.className = 'page-item';

                    let pageLink = document.createElement('a');
                    pageLink.className = 'page-link';
                    pageLink.href = '#';
                    pageLink.innerHTML = '...';
                    pageLi.appendChild(pageLink);
                    paginationUl.appendChild(pageLi);
                }
            }

            // Add next
            let nextLi = document.createElement('li');
            nextLi.className = 'page-item';
            if (current_page == last_page) {
                nextLi.className += ' disabled';
            }
            let nextLink = document.createElement('a');
            nextLink.className = 'page-link';
            nextLink.href = '#';
            nextLink.innerHTML = 'Next';
            nextLink.onclick = function() {
                onPageClick(current_page + 1);
            }
            nextLi.appendChild(nextLink);
            paginationUl.appendChild(nextLi);

            return nav;
        }
        </script>
        @stack('custom-scripts')
</body>

</html>