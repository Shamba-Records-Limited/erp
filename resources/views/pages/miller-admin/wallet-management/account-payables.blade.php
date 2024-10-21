@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

<div class="card pt-6">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Account Payables</div>
            <div class="d-flex">
                <div class="dropdown ml-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Make Payment
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" id="makePayment" href="{{route('miller-admin.wallet-management.view-make-payment')}}">Make Payment</a>
                    </div>
                </div>
                <a class="btn btn-primary ml-2" id="addOperationalExpense" href="{{route('cooperative-admin.wallet-management.operational-expenses.add')}}">Add Operational Expense</a>
            </div>

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
                @foreach($payables as $transaction)
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