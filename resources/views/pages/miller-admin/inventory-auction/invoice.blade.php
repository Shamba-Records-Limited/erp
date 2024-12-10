@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('topItem')
@if($isAddingInvoice == '1' || !empty($viewingInvoiceId))
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">
                    @if($isAddingInvoice == '1') Add Invoice @else View Invoice @endif
                </h4>
                <a class="close-btn" href="?">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">
                @if(!empty($viewingInvoiceId))
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" onclick="printInvoice('{{$draftInvoice->id}}')">
                        <i class="fa fa-pdf"></i> Print Invoice
                    </button>
                </div>
                @endif
                <h5 class="mb-4">
                    Invoice Number: <span class="font-weight-bold">{{$draftInvoice->invoice_number}}</span>
                </h5>
                <form action="{{route('miller-admin.inventory-auction.invoices.save-basic-details')}}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{$draftInvoice->id}}">
                    <div class="form-group">
                        <label for="customer_id">Select Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control form-select {{ $errors->has('customer_id') ? ' is-invalid' : '' }}" value="{{old('customer_id', '')}}" @if(!empty($viewingInvoiceId)) disabled @endif>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}" @if(old('customer_id',$draftInvoice->customer_id) == $customer->id) selected @endif>{{$customer->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('customer_id'))
                            <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="expires_at">Valid To</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="form-control {{ $errors->has('expires_at') ? ' is-invalid' : '' }}" value="{{old('expires_at', $draftInvoice->expires_at) }}" @if(!empty($viewingInvoiceId)) disabled @endif>
                        @if($errors->has('expires_at'))
                            <span class="text-danger">{{ $errors->first('expires_at') }}</span>
                        @endif
                        @if($isAddingInvoice == '1')
                        <div class="mt-2">
                            <button class="btn btn-light" type="button" onclick="setNeverExpires()">Never</button>
                            <button class="btn btn-light" type="button" onclick="setExpiresInAMonth()">In a month</button>
                        </div>
                        @endif
                    </div>
                    @if($isAddingInvoice == '1')
                    <button type="submit" class="btn btn-primary">Save Basic Details</button>
                    @endif
                </form>
                <hr class="my-4" />
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Invoice Items</h5>
                    @if($isAddingInvoice == '1')
                    <button class="btn btn-primary" data-toggle="collapse" href="#addInvoiceItem">Add Invoice Item</button>
                    @endif
                </div>
                <div class="collapse border rounded p-3 mt-3" id="addInvoiceItem">
                    <form action="{{route('miller-admin.inventory-auction.invoices.save-invoice-item')}}" method="POST">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{$draftInvoice->id}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>Item Type</label>
                                <select name="item_type" id="item_type" class="form-control {{ $errors->has('item_type') ? ' is-invalid' : '' }}">
                                    <option>Final Product</option>
                                    <option>Milled Inventory</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="final_product_item_id">Final Product</label>
                                <select name="final_product_item_id" id="final_product_item_id" class="form-control {{ $errors->has('final_product_item_id') ? ' is-invalid' : '' }}">
                                    <option value="">-- Select Item --</option>
                                    @foreach($finalProducts as $finalProduct)
                                    <option value="{{$finalProduct->id}}">{{$finalProduct->product_number}} {{$finalProduct->name}} {{$finalProduct->quantity}} {{$finalProduct->unit}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('final_product_item_id'))
                                    <span class="text-danger">{{ $errors->first('final_product_item_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-12">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="Enter price" value="{{old('price', '')}}">
                                @if($errors->has('price'))
                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-12">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="Enter quantity" value="{{old('quantity', '')}}">
                                @if($errors->has('quantity'))
                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Add Invoice Item</button>
                        </div>
                    </form>
                </div>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($draftInvoice->items as $item)
                        @php $subTotal = $item->price * $item->quantity; $total += $subTotal; @endphp
                        <tr>
                            <td>{{$item->number}}</td>
                            <td>KES {{$item->price}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>KES {{$subTotal}}</td>
                            <td>
                                @if($isAddingInvoice == '1')
                                <form action="{{route('miller-admin.inventory-auction.invoices.delete-invoice-item', $item->id)}}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger">Remove</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end p-3">
                    Total: <span class="font-weight-bold text-success">KES {{$total}}</span>
                </div>
                @if($isAddingInvoice == '1')
                <div class="mt-3">
                    <a href="{{route('miller-admin.inventory-auction.invoices.publish-invoice')}}" class="btn btn-primary">Publish</a>
                </div>
                @endif
                @if(!empty($viewingInvoiceId))
                <div class="text-center mt-3">
                    {{ QrCode::size(70)->generate(route('common.view-invoice', $draftInvoice->id)) }}
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
        <div class="card-title">Invoices</div>
        <div class="d-flex justify-content-end">
            <a href="?is_adding_invoice=1" class="btn btn-primary">Add Invoice</a>
            <div class="dropdown ml-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" id="exportAllInvoices">Export All</button>
                    <button class="dropdown-item" id="exportCompletedInvoices">Export Completed</button>
                    <button class="dropdown-item" id="exportPendingInvoices">Export Pending</button>
                </div>
            </div>
        </div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Number of Items</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td><a href="?viewing_invoice_id={{$invoice->id}}">{{$invoice->invoice_number}}</a></td>
                        <td>{{$invoice->items_count}}</td>
                        <td>KES {{$invoice->total_price}}</td>
                        @php
                        $status = 'Pending Payment';
                        if($invoice->expires_at != ''){
                        $now = now();

                        if($invoice->expires_at < $now) { $status='Expired' ; } } if($invoice->has_receipt){
                            $status = 'Complete';
                            }
                            @endphp <td>
                                @if($status == 'Pending Payment')
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
                                        <!-- <a class="text-info dropdown-item" href="{{route('common.view-invoice', $invoice->id)}}">
                                            <i class="fa fa-pdf"></i> View Invoice
                                        </a> -->
                                        <a class="text-dark dropdown-item" href="?viewing_invoice_id={{$invoice->id}}" onclick="">
                                            <i class="fa fa-pdf"></i> View Invoice
                                        </a>
                                        <button class="text-info dropdown-item" onclick="printInvoice('{{$invoice->id}}')">
                                            <i class="fa fa-pdf"></i> Print Invoice
                                        </button>
                                        @if($status == 'Expired')
                                        <a class="text-primary dropdown-item" href="#">
                                            <i class="fa fa-pdf"></i> Regenerate Invoice
                                        </a>
                                        @elseif($status == 'Complete')
                                        <a class="text-primary dropdown-item" href="#" onclick="printReceipt('{{$invoice->transaction->receipt->id}}')">
                                            <i class="fa fa-pdf"></i> Print Receipt
                                        </a>
                                        @elseif($invoice->has_receipt == false)
                                        todo: add dialog 
                                        <a class="text-success dropdown-item" href="{{ route('miller-admin.inventory-auction.invoices.mark-as-paid', $invoice->id) }}">
                                            <i class="fa fa-edit"></i>Make Payment
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
        alert(item_type)
        if (item_type == 'Final Product') {
            $("$final_product_item_id").closest(".form-group").removeClass("d-none")
            $("milled_inventory_item_id").closest(".form-group").addClass("d-none")
        } else {
            $("$final_product_item_id").closest(".form-group").addClass("d-none")
            $("milled_inventory_item_id").closest(".form-group").removeClass("d-none")
        }
    }

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
    $('#exportAllInvoices').on('click', function() {
        exportStatus = 'all';
        showExportDialog('Export All Invoices');
    });

    $('#exportCompletedInvoices').on('click', function() {
        exportStatus = 'Complete';
        showExportDialog('Export Completed Invoices');
    });

    $('#exportPendingInvoices').on('click', function() {
        exportStatus = 'Pending';
        showExportDialog('Export Pending Invoices');
    });

    $("#doExport").on('click', function() {
        let exportType = $("[name='exportType']:checked").val();
        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();
        window.location.href = `/miller-admin/inventory-auction/invoices/export-many/${exportType}?start_date=${startDate}&end_date=${endDate}&export_status=${exportStatus}`;

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