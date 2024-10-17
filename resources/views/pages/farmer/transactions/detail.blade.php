@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-start">
            <div class="card-title">Transaction: {{$transaction->transaction_number}}</div>
            @php
            $badgeCls = 'badge-warning';
            if($transaction->status == 'complete'){
            $badgeCls = 'badge-success';
            }
            @endphp
            <div class="ml-4 badge {{$badgeCls}}">{{ $transaction->status }}</div>
        </div>
        <div>Amount: <span class="text-primary">KES {{$transaction->amount}}</span></div>
        <div>Pricing: <span class="text-primary">KES {{round($transaction->pricing, 2)}} PER KG</span></div>
        @if($transaction->subject_type == 'LOT' || $transaction->subject_type == 'LOT_GROUP')
        <table class="table">
            <thead>
                <tr>
                    <th>Lot Number</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lots as $lot)
                <tr>
                    <td>{{$lot->lot_number}}</td>
                    <td>{{$lot->quantity}} KG</td>
                    <td>{{round($lot->quantity * $transaction->pricing, 2)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjectItems as $item)
                <tr>
                    <td>{{$item["identification"]}}</td>
                    <td>{{$item["quantity"]}}</td>
                    <td>{{$item["sub_total"]}}</td>
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
<script>
    function printReceipt(transactionId) {
        $.ajax({
            url: `/transaction-receipts/${transactionId}/print`,
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
</script>
@endpush