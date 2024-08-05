@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">

        <div class="card-title">Transactions</div>
        <a class="btn btn-primary" href="{{route('miller-admin.transactions.view-add')}}">Add</a>
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
                        <td></td>
                        <td>Me</td>
                        <td>{{$transaction->dest}}</td>
                        <td>KES {{$transaction->amount}}</td>
                        <td>{{$transaction->status}}</td>
                        <td>
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