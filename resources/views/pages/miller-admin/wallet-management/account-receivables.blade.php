@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Account Receivables</div>
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
                @foreach($receivables as $transaction)
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