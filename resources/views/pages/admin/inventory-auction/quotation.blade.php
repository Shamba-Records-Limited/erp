@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('topItem')
@if($isAddingQuotation == '1' || !empty($viewingQuotationId))
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">
                    @if($isAddingQuotation == '1') Add Quotation @else View Quotation @endif
                </h4>
                <a class="close-btn" href="?">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">               

                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($draftQuotation->items as $item)
                        @php $subTotal = $item->price * $item->quantity; $total += $subTotal; @endphp
                        <tr>
                            <td>{{$item->number}}</td>
                            <td>KES {{$item->price}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>KES {{$subTotal}}</td>
                            <td>
                                @if($isAddingQuotation == '1')
                               <!-- <form action="{{route('admin.inventory-auction.quotations.delete-quotation-item', $item->id)}}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger">Remove</button>
                                </form>-->
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end p-3">
                    Total: <span class="font-weight-bold text-success">KES {{$total}}</span>
                </div>
                <div class="mt-3">
                    @if($isAddingQuotation == '1')
                    <a href="{{route('cooperative-admin.inventory-auction.quotations.publish-quotation')}}" class="btn btn-primary" onclick="return confirm('Are you sure you want to publish quotation?')">Publish</a>
                    @endif
                </div>
                @if(!empty($viewingQuotationId))
                <div class="text-center mt-3">
                    {{ QrCode::size(70)->generate(route('common.view-quotation', $draftQuotation->id)) }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <div class="card-title">Quotations</div>
        <div class="d-flex justify-content-end">
            <div class="dropdown ml-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" id="exportAllQuotations">Export All</button>
                    <button class="dropdown-item" id="exportCompletedQuotations">Export Completed</button>
                    <button class="dropdown-item" id="exportPendingQuotations">Export Pending</button>
                    <button class="dropdown-item" id="exportExpiredQuotations">Export Expired</button>
                </div>
            </div>
        </div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Quotation Number</th>
                        <th>Number of Items</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotations as $quotation)
                    <tr>
                        <td><a href="?viewing_quotation_id={{$quotation->id}}">{{$quotation->quotation_number}}</a></td>
                        <td>{{$quotation->items_count}}</td>
                        <td>KES {{$quotation->total_price}}</td>
                        @php
                        $status = 'Invoice Pending';
                        if($quotation->expires_at != ''){
                        $now = now();

                        if($quotation->expires_at < $now) { $status='Expired' ; } } if($quotation->has_invoice){
                            $status = 'Complete';
                            }
                            @endphp
                            <td>
                                @if($status == 'Invoice Pending')
                                <div class="badge badge-warning">{{$status}}</div>
                                @elseif($status == 'Expired')
                                <div class="badge badge-danger">{{$status}}</div>
                                @elseif($status == 'Complete')
                                <div class="badge badge-success">{{$status}}</div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- <a class="text-info dropdown-item" href="{{route('common.view-quotation', $quotation->id)}}">
                                            <i class="fa fa-pdf"></i> View Quotation
                                        </a> -->
                                        <!-- <a class="text-info dropdown-item" href="{{route('cooperative-admin.inventory-auction.quotations.export-quotation', $quotation->id)}}"> -->
                                        <a class="text-dark dropdown-item" href="?viewing_quotation_id={{$quotation->id}}" onclick="">
                                            <i class="fa fa-pdf"></i> View Quotation
                                        </a>
                                        <button class="text-info dropdown-item" onclick="printQuotation('{{$quotation->id}}')">
                                            <i class="fa fa-pdf"></i> Print Quotation
                                        </button>
                                        @if($status == 'Expired')
                                        <a class="text-primary dropdown-item" href="#">
                                            <i class="fa fa-pdf"></i> Regenerate Quotation
                                        </a>
                                        @elseif($status == 'Complete')
                                        <button class="text-primary dropdown-item" onclick="printInvoice('{{$quotation->invoice->id}}')">
                                            <i class="md md-edit"></i> Print Invoice
                                        </button>
                                        @elseif($quotation->no_invoice)
                                        <a class="text-success dropdown-item" href="{{ route('cooperative-admin.inventory-auction.quotations.create-invoice', $quotation->id) }}">
                                            <i class="md md-edit"></i>Create Invoice From Quotation
                                        </a>
                                        @endif
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
    let item_type;

    function displayItemSelector() {
        item_type = $("#item_type").val()
        // alert(item_type)
        if (item_type == 'Final Product') {
            $("#final_product_item_id").closest(".form-group").removeClass("d-none")
            $("#milled_inventory_item_id").closest(".form-group").addClass("d-none")
        } else {
            $("#final_product_item_id").closest(".form-group").addClass("d-none")
            $("#milled_inventory_item_id").closest(".form-group").removeClass("d-none")
        }
    }

    $("#final_product_item_id").change(function() {
        let final_product;
        let item_id = $(this).val();
        $.ajax({
            method: "get",
            url: `/cooperative-admin/inventory-auction/final-product/${item_id}`
        }).then((resp) => {
            // fill in current price
            $("#price").val(resp.selling_price)
            // get max quantity
            let maxQty = resp.count
            // loop through current rows checking for quantity usages
            let rows = $("#quotation_items tbody tr")
            console.log(rows.length)
            for (let row of rows) {
                let cur_number = $(row).find(".item_number").text();
                if (cur_number == resp.product_number) {
                    let row_qty = parseInt($(row).find(".item_quantity").text());
                    maxQty -= row_qty
                }
            }

            $("#quantity-max-helper").remove();
            $("#quantity").attr("max", maxQty)
            $(`<p id='quantity-max-helper'>Max Quantity: ${maxQty}</p>`).insertAfter("#quantity");
        })
    });

    $(document).on("load", function() {
        displayItemSelector();
    })

    $("#item_type").change(function() {
        displayItemSelector();
    });

    function setNeverExpires() {
        $("#expires_at").val("");
    }

    function setExpiresInAMonth() {
        var currentDate = new Date();
        var newDate = new Date(currentDate.setMonth(currentDate.getMonth() + 1));
        var formattedDate = newDate.toISOString().slice(0, 16);

        $("#expires_at").val(formattedDate);
    }

    $('#save_basic_details_form').on('submit', function(event) {
        // Get the current datetime
        var currentDate = new Date();

        // Get the selected datetime from the input
        var rawSelectedDate = $('#dateInput').val();
        if (rawSelectedDate != "") {
            var selectedDate = new Date(rawSelectedDate);

            // Compare the selected datetime with the current datetime
            if (selectedDate <= currentDate) {
                // Prevent form submission
                event.preventDefault();
                $("expires_at_error").val("Please select a datetime that is after the current datetime.")
            }
        }
    });

    function printQuotation(quotationId) {
        $.ajax({
            url: `/quotations/${quotationId}/print`,
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

    function printInvoice(invoiceId) {
        $.ajax({
            url: `/invoices/${invoiceId}/print`,
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
        showExportDialog('Export All Quotations');
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
        window.location.href = `/admin/inventory-auction/quotations/export-many/${exportType}?start_date=${startDate}&end_date=${endDate}&export_status=${exportStatus}`;

        dismissExportDialog();
    })
</script>
@endpush
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .modal-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .modal-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
    }

    .modal-body {
        margin-top: 10px;
    }

    .alert {
        margin-bottom: 15px;
    }

    .info-box, .aggregate-info {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .btn-toggle {
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-striped tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .container {
        margin-top: 30px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .order-details {
        background-color: #f8f9fa;
    }

    .delivery-status {
        background-color: #ffffff;
    }

    .info-box {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .batch-number {
        font-weight: bold;
        color: #007bff;
    }

    .aggregate-info {
        margin-top: 20px;
    }

    .list-group-item {
        background-color: #f8f9fa;
        border: none;
    }

    .list-group-item:hover {
        background-color: #e9ecef;
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 300px;
    }
</style>