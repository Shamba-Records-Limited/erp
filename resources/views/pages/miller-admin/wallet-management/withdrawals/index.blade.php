@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card pt-6">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">

            <div class="card-title">Withdrawals</div>
            <a href="{{route('miller-admin.wallet-management.view-withdraw')}}" class="btn btn-primary">Withdraw Funds</a>
        </div>

        <table class="table table-striped table-bordered dt">
            <thead>
                <tr>
                    <th>Transaction Number</th>
                    <th>Subject</th>
                    <th>Sender</th>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $transaction)
                <tr>
                    <td>{{$transaction->transaction_number}}</td>
                    <td>{{$transaction->subject}}</td>
                    <td>{{$transaction->sender}}</td>
                    <td>{{$transaction->recipient}}</td>
                    <td>{{$transaction->amount}}</td>
                    <td>{{$transaction->status}}</td>
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