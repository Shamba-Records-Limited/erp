@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">

            <div class="card-title">Transactions</div>
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
                        <td>
                            <a href="{{route('farmer.transactions.detail', $transaction->id )}}">
                                {{$transaction->transaction_number}}
                            </a>
                        </td>
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
                                    <a class="text-primary dropdown-item" href="{{route('farmer.transactions.detail', $transaction->id )}}">
                                        <i class="fa fa-edit"></i> View Details
                                    </a>
                                    @if($transaction->recipient == 'Me' && $transaction->status == 'PENDING')
                                    <a class="text-success dropdown-item" href="{{route('farmer.transactions.complete', $transaction->id )}}">
                                        <i class="fa fa-edit"></i> Complete
                                    </a>
                                    @endif
                                    @if($transaction->status == 'COMPLETE')
                                    <button class="text-info dropdown-item" onclick="printReceipt('{{$transaction->id}}')">
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