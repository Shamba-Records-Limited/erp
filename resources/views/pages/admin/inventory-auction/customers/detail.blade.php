@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Customer Details</div>
        <div class="card-subtitle">{{$customer->title}} {{$customer->name}} &nbsp; -> &nbsp; {{$customer->address}}</div>
        <div class="row">
            <div class="col d-flex">
                <div class="">Email:</div>
                <div class="font-weight-bold"> {{$customer->email}}</div>
            </div>
            <div class="col d-flex">
                <div class="">Phone:</div>
                <div class="font-weight-bold"> {{$customer->phone_number}}</div>
            </div>
            <div class="col d-flex">
                <div class="">Gender:</div>
                <div class="font-weight-bold"> {{$customer->gender}}</div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'quotations'?'active':'' }}" href="?tab=quotations">Quotations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'invoices'?'active':'' }}" href="?tab=invoices">Invoices</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'receipts'?'active':'' }}" href="?tab=receipts">Receipts</a>
            </li>
        </ul>
        @if($tab == 'quotations')

        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Quotation Number</th>
                        <th>Number of Items</th>
                        <th>Total Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotations as $quotation)
                    <tr>
                        <td>{{$quotation->quotation_number}}</td>
                        <td>{{$quotation->items_count}}</td>
                        <td>KES {{$quotation->total_price}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="#">
                                        <i class="fa fa-pdf"></i> Print Quotation
                                    </a>
                                    @if($quotation->no_invoice)
                                    <a class="text-info dropdown-item" href="{{ route('cooperative-admin.inventory-auction.quotations.create-invoice', $quotation->id) }}">
                                        <i class="fa fa-edit"></i>Create Invoice From Quotation
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
        @elseif($tab == 'invoices')
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Number of Items</th>
                        <th>Total Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{$invoice->invoice_number}}</td>
                        <td>{{$invoice->items_count}}</td>
                        <td>KES {{$invoice->total_price}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="#">
                                        <i class="fa fa-pdf"></i> Print Receipt
                                    </a>
                                    @if($invoice->has_receipt == false)
                                    <a class="text-info dropdown-item" href="{{ route('cooperative-admin.inventory-auction.invoices.create-receipt', $invoice->id) }}">
                                        <i class="fa fa-edit"></i>Create Receipt From Invoice
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
        @elseif($tab == 'receipts')
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
                                    <a class="text-primary dropdown-item" href="#">
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
        @endif

    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush