@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Receipts</div>
        <div class="d-flex justify-content-end">
            <div class="dropdown ml-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" id="exportAllQuotations">Export All</button>
                </div>
            </div>
        </div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Receipt Number</th>
                        <th>Number of Items</th>
                        <th>Total Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                    <tr>
                        <td>{{$receipt->receipt_number}}</td>
                        <td>{{$receipt->items_count}}</td>
                        <td>KES {{$receipt->total_price}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" onclick="printReceipt('{{$receipt->id}}')">
                                        <i class="fa fa-pdf"></i> Print Receipt
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--<h1>Export Dialogue Modal</h1>-->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Quotations</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="form-group">
                        <label>Export Type</label>
                        <div>
                            <label><input type="radio" name="exportType" value="pdf" checked> PDF</label>
                            <label><input type="radio" name="exportType" value="excel"> Excel</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input 
                            type="date" 
                            id="startDate" 
                            class="form-control" 
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                        >
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Date</label>
                        <input 
                            type="date" 
                            id="endDate" 
                            class="form-control" 
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                        >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="doExport">Export</button>
            </div>
        </div>
    </div>
</div>
<!--<h1>End Export Dialogue Modal</h1>-->
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function printReceipt(receiptId) {
        $.ajax({
            url: `/receipts/${receiptId}/print`,
            method: 'GET',
            success: function(resp) {
                // alert(resp);
                printContent(resp);
            },
            error: function(errResp) {
                alert(errResp);
            }
        })
    }


    function showExportDialog(title) {
    $("#exportModalLabel").text(title); // Update the modal title
    $("#exportModal").modal('show');   // Show the modal
    }

    function dismissExportDialog() {
    $("#exportModal").modal('hide'); // Hide the modal
    }

    let exportStatus = 'all';
    $('#exportAllQuotations').on('click', function() {
        exportStatus = 'all';
        showExportDialog('Export All Receipts');
    });

    $('#exportCompletedQuotations').on('click', function() {
        exportStatus = 'Complete';
        showExportDialog('Export Completed Quotations');
    });

    $('#exportPendingQuotations').on('click', function() {
        exportStatus = 'Invoice Pending';
        showExportDialog('Export Pending Quotations');
    });

    $('#exportExpiredQuotations').on('click', function() {
        exportStatus = 'Expired';
        showExportDialog('Export Expired Quotations');
    });

    $("#doExport").on('click', function() {
        let exportType = $("[name='exportType']:checked").val();
        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();
        window.location.href = `/admin/inventory-auction/receipts/export-many/${exportType}?start_date=${startDate}&end_date=${endDate}&export_status=${exportStatus}`;

        dismissExportDialog();
    })

</script>
@endpush