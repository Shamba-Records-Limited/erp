@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Receipts</div>
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
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush