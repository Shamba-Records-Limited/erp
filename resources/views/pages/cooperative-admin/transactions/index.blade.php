@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">

            <div class="card-title">Transactions</div>
            <a class="btn btn-primary" href="{{route('cooperative-admin.transactions.view-add')}}">Add</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt mb-3">
                <thead>
                    <tr>
                        <th>Transaction Number</th>
                        <th>Subject</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalAmount = 0;
                    @endphp
                    @foreach($transactions as $transaction)
                    @php
                    $totalAmount += $transaction->amount;
                    @endphp
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
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    @if(substr($transaction->subject, 0, 3) == 'LOT')
                                    <a class="text-primary dropdown-item"
                                        href="{{route('cooperative-admin.transactions.detail', $transaction->id )}}">
                                        <i class="fa fa-edit"></i> View Details
                                    </a>
                                    @endif
                                    @if($transaction->status == 'PENDING')
                                    <a class="text-success dropdown-item"
                                        href="{{route('cooperative-admin.transactions.complete', $transaction->id )}}">
                                        <i class="fa fa-edit"></i> Complete
                                    </a>
                                    @endif
                                    @if($transaction->status == 'COMPLETE')
                                    <button class="text-info dropdown-item"
                                        onclick="printReceipt('{{$transaction->id}}')">
                                        <i class="fa fa-edit"></i> Print Receipt
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total:</th>
                        <th>KES {{ number_format($totalAmount, 2) }}</th> <!-- Total amount with two decimal places -->
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
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