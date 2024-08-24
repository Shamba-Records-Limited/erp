@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Withdraw Funds</div>
        <form action="{{route('miller-admin.transactions.withdraw')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <div>Account</div>
                <div>Account Number: <span class="text-info font-weight-bold">{{$account->acc_number}}</span></div>
                <div>Current Balance: <span class="text-info font-weight-bold">{{$account->balance}}</span></div>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description">
            </div>
            <div class="form-group">
                <label>Purpose</label>
                <input type="text" class="form-control" name="purpose" id="purpose" placeholder="Enter Purpose">
                <p>e.g. Payment for Farming, Petty Cash etc.</p>
            </div>
            <div class="form-group">
                <label>Withdrawal Slip</label>
                <input type="file" class="form-control" name="withdrawal_slip" id="withdrawal_slip">
            </div>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">Withdraw</button>
                <a href="{{route('miller-admin.transactions.view-withdraw')}}" class="btn btn-primary ml-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
