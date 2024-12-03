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
                @if(!empty($viewingQuotationId))
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" onclick="printQuotation('{{$draftQuotation->id}}')">
                        <i class="fa fa-pdf"></i> Print Quotation
                    </button>
                </div>
                @endif
                <h5 class="mb-4">
                    Quotation Number: <span class="font-weight-bold">{{$draftQuotation->quotation_number}}</span>
                </h5>
                <form action="{{route('miller-admin.inventory-auction.quotations.save-basic_details')}}" method="POST" id="save_basic_details_form">
                    @csrf
                    <input type="hidden" name="quotation_id" value="{{$draftQuotation->id}}">
                    <div class="form-group">
                        <label for="customer_id">Select Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control form-select {{ $errors->has('customer_id') ? ' is-invalid' : '' }}" value="{{old('customer_id', '')}}" @if(!empty($viewingQuotationId)) disabled @endif>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}" @if(old('customer_id', $draftQuotation->customer_id) == $customer->id) selected @endif>{{$customer->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('customer_id'))
                            <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="expires_at">Valid To</label>
                        <input class="form-control" type="datetime-local" name="expires_at" id="expires_at" value="{{old('expires_at', $draftQuotation->expires_at)}}" @if(!empty($viewingQuotationId)) disabled @endif>
                        @if($errors->has('expires_at'))
                            <span class="text-danger">{{ $errors->first('expires_at') }}</span>
                        @endif
                        @if($isAddingQuotation == '1')
                        <div class="mt-2">
                            <button class="btn btn-light" type="button" onclick="setNeverExpires()">Never</button>
                            <button class="btn btn-light" type="button" onclick="setExpiresInAMonth()">In a month</button>
                        </div>
                        @endif
                    </div>
                    @if($isAddingQuotation == '1')
                    <button type="submit" class="btn btn-primary">Save Basic Details</button>
                    @endif
                </form>
                <hr class="my-4" />
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Quotation Items</h5>
                    @if($isAddingQuotation == '1')
                    <button class="btn btn-primary" data-toggle="collapse" href="#addQuotationItem">Add Quotation Item</button>
                    @endif
                </div>
                <div class="collapse border rounded p-3 mt-3" id="addQuotationItem">
                    <form action="{{route('miller-admin.inventory-auction.quotations.save-quotation-item')}}" method="POST">
                        @csrf
                        <input type="hidden" name="quotation_id" value="{{$draftQuotation->id}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="item_type">Item Type</label>
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
                                <input type="number" name="price" id="price" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="Enter price" value="{{old('price')}}">
                                @if($errors->has('price'))
                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-12">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="Enter quantity" value="{{old('quantity')}}">
                                @if($errors->has('quantity'))
                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Add Quotation Item</button>
                        </div>
                    </form>
                </div>
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
                                <form action="{{route('miller-admin.inventory-auction.quotations.delete-quotation-item', $item->id)}}" method="POST">
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
                <div class="mt-3">
                    @if($isAddingQuotation == '1')
                    <a href="{{route('miller-admin.inventory-auction.quotations.publish-quotation')}}" class="btn btn-primary" onclick="return confirm('Are you sure you want to publish quotation?')">Publish</a>
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
            <a href="?is_adding_quotation=1" class="btn btn-primary">Add Quotation</a>
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
                                        <!-- <a class="text-info dropdown-item" href="{{route('miller-admin.inventory-auction.quotations.export-quotation', $quotation->id)}}"> -->
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
                                        <a class="text-success dropdown-item" href="{{ route('miller-admin.inventory-auction.quotations.create-invoice', $quotation->id) }}">
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
            url: `/miller-admin/inventory-auction/final-product/${item_id}`
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
        window.location.href = `/miller-admin/inventory-auction/quotations/export-many/${exportType}?start_date=${startDate}&end_date=${endDate}&export_status=${exportStatus}`;

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