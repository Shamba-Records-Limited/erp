@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <h5 class="card-title text-center">Deposit Funds</h5>
        <form action="{{ route($acc_type.'.wallet-management.deposit') }}" method="post" enctype="multipart/form-data">
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
                <label for="source_of_funds">Source of Funds <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="source_of_funds" id="source_of_funds"
                    placeholder="Enter Source of Funds" value="{{ old('source_of_funds', '') }}">
                <small class="form-text text-muted">e.g. Bank, ATM, Cheque, etc.</small>
                @if($errors->has('source_of_funds'))
                <span class="text-danger">{{ $errors->first('source_of_funds') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" name="description" id="description"
                    placeholder="Enter Description" value="{{ old('description', '') }}">
                @if($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="deposit_slip">Deposit Slip</label>
                <input type="file" class="form-control" name="deposit_slip" id="deposit_slip">
                @if($errors->has('deposit_slip'))
                <span class="text-danger">{{ $errors->first('deposit_slip') }}</span>
                @endif
            </div>

            <div class="d-none" id="imagePreviewContainer">
                <div class="imageHolder">
                    <img id="picturePreview" src="#" alt="Deposit Slip Preview" class="img-fluid rounded" />
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Deposit</button>
                <a href="{{ route('miller-admin.wallet-management.view-deposit') }}"
                    class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
$('#deposit_slip').change(function() {
    previewImage(this, 'picturePreview', 'imagePreviewContainer');
});

function previewImage(input, imgElementId, containerId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + imgElementId).attr('src', e.target.result);
            $('#' + containerId).removeClass('d-none').addClass('d-block');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush


<!-- <style>
.imageHolder {
    text-align: center;
    margin-top: 15px;
}

.img-fluid {
    max-width: 100%;
    height: auto;
}

.help-block {
    margin-top: 5px;
}

.text-danger {
    font-size: 0.875em;
}
</style> -->