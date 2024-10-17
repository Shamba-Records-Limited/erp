@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">
            <div class="card-title">Transactions</div>
            <div class="d-flex">
                <a class="btn btn-primary" href="{{route('miller-admin.wallet-management.view-make-payment')}}">Make Payment</a>
                <a class="btn btn-primary" href="#">Make Bulk Payment</a>
                <a class="btn btn-primary" href="{{route('miller-admin.wallet-management.view-deposit')}}">Deposit Funds</a>
                <a class="btn btn-primary" href="{{route('miller-admin.wallet-management.view-withdraw')}}">Withdraw Funds</a>
                <div class="dropdown ml-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" id="exportAllTransactions">Export All</button>
                        <button class="dropdown-item" id="exportCompletedTransactions">Export Completed</button>
                        <button class="dropdown-item" id="exportPendingTransactions">Export Pending</button>
                    </div>
                </div>
            </div>
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
                        <!-- add comma to amount -->
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
                                    @if(!empty($transaction->receipt_id))
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

    let exportStatus = 'all';
    $('#exportAllTransactions').on('click', function() {
        exportStatus = 'all';
        showExportDialog('Export All Transactions');
    });

    $('#exportCompletedTransactions').on('click', function() {
        exportStatus = 'Complete';
        showExportDialog('Export Completed Transactions');
    });

    $('#exportPendingTransactions').on('click', function() {
        exportStatus = 'Pending';
        showExportDialog('Export Pending Transactions');
    });

    $("#doExport").on('click', function() {
        let exportType = $("[name='exportType']:checked").val();
        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();
        window.location.href = `/miller-admin/inventory-auction/transactions/export-many/${exportType}?start_date=${startDate}&end_date=${endDate}&export_status=${exportStatus}`;

        dismissExportDialog();
    })
</script>
@endpush