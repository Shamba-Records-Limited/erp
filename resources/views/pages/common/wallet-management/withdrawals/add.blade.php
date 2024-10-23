@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
if (empty($acc_type)) {
$acc_type = 'miller-admin';
}

// Function to format currency with commas
function formatCurrency($amount) {
return number_format($amount, 2);
}
@endphp
<div class="card shadow-lg">
    <div class="card-body">
        <div class="card-title text-center">Withdraw Funds</div>
        <form action="{{route($acc_type.'.wallet-management.withdraw')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <h4>Account Information</h4>
                <p>Account Number: <strong class="semi-bold" style="color: black;">{{ $account->acc_number }}</strong>
                </p>
                <p>Current Balance: <strong class="text-success semi-bold">KES
                        {{ formatCurrency($account->balance) }}</strong>
                </p>
            </div>
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount"
                    value="{{ old('amount', '') }}">
                @if($errors->has('amount'))
                <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="description">Description<span class=" text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="description" id="description"
                    placeholder="Enter Description" value="{{ old('description', '') }}">
                @if($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="purpose">Purpose<span class=" text-danger">*</span></label>
                <input type="text" class="form-control" name="purpose" id="purpose" placeholder="Enter Purpose"
                    value="{{ old('purpose', '') }}">
                <small class="form-text text-muted">e.g. Payment for Farming, Petty Cash etc.</small>

                @if($errors->has('purpose'))
                <span class="help-block text-danger">
                    <span>{{ $errors->first('purpose') }}</span>
                </span>
                @endif
            </div>
            <div class="d-none" id="imagePreviewContainer">
                <div class="imageHolder pl-2">
                    <img id="picturePreview" src="#" alt="pic" height="150px" width="150px" />
                </div>
            </div>
            <div class="form-group">
                <label for="withdrawal_slip"> Withdrawal Slip<span class=" text-danger">*</span></label>
                <input type="file" class="form-control" name="withdrawal_slip" id="withdrawal_slip"
                    value="{{ old('withdrawal_slip', '') }}">

                @if($errors->has('withdrawal_slip'))
                <span class="help-block text-danger">
                    <span>{{ $errors->first('withdrawal_slip') }}</span>
                </span>
                @endif
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-minus-circle"></i> Withdraw</button>
                <a href="{{route('miller-admin.wallet-management.view-withdraw')}}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
$('#withdrawal_slip').change(function() {
    previewImage(this, 'picturePreview', 'imagePreviewContainer');
});
</script>
@endpush