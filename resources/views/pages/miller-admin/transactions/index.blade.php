@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">

        <div class="card-title">Transactions</div>
        <a class="btn btn-primary" href="{{route('miller-admin.transactions.view-add')}}">Add Cooperative Payment</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Transaction Number</th>
                        <th>Subject</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{$transaction->transaction_number}}</td>
                        <td>{{$transaction->subject}}</td>
                        <td>{{$transaction->sender}}</td>
                        <td>{{$transaction->recipient}}</td>
                        <td>KES {{$transaction->amount}}</td>
                        @php
                        $statusCls = 'text-warning';
                        if($transaction->status == 'COMPLETE'){
                        $statusCls = 'text-success';
                        }
                        @endphp
                        <td>
                            <div class="{{$statusCls}}">
                                {{$transaction->status}}
                            </div>
                        </td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="{{route('miller-admin.transactions.detail', $transaction->id )}}">
                                        <i class="fa fa-edit"></i>View Details
                                    </a>
                                    @if($transaction->status == 'COMPLETE' && !empty($transaction->receipt_id))
                                    <button class="text-info dropdown-item" onclick="printReceipt('{{$transaction->receipt_id}}')">
                                        <i class="fa fa-edit"></i> Print Receipt
                                    </button>
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
    function printReceipt(receiptId){
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
</script>
@endpush