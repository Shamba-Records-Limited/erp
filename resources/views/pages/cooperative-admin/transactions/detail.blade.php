@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-titile">Transaction: {{$transaction->transaction_number}}</div>
        <div>Amount: <span class="text-primary">KES {{$transaction->amount}}</span></div>

        <table class="table">
            <thead>
                <tr>
                    <th>Lot Number</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lots as $lot)
                <tr>
                    <td>{{$lot->lot_number}}</td>
                    <td>{{$lot->quantity}} KG</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush