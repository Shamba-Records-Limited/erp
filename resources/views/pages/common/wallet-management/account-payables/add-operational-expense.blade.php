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
        <h5 class="card-title text-center">Add Operational Expense</h5>
        <form action="{{ route($acc_type.'.wallet-management.operational-expenses.add') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <h4>Account Information</h4>
                <p>Account Number: <strong class="semi-bold" style="color: black;">{{ $account->acc_number }}</strong></p>
                <p>Current Balance: <strong class="text-success semi-bold">KES {{ formatCurrency($account->balance) }}</strong></p>
            </div>

            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" value="{{ old('amount', '') }}">
                @if($errors->has('amount'))
                <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" value="{{ old('description', '') }}">
                @if($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="purpose" id="purpose" placeholder="Enter Purpose" value="{{ old('purpose', '') }}">
                <small class="form-text text-muted">e.g. Payment for Farming, Petty Cash, etc.</small>
                @if($errors->has('purpose'))
                <span class="text-danger">{{ $errors->first('purpose') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="operational_expense_slip">Operational Expense Slip</label>
                <input type="file" class="form-control" name="operational_expense_slip" id="operational_expense_slip">
                @if($errors->has('operational_expense_slip'))
                <span class="text-danger">{{ $errors->first('operational_expense_slip') }}</span>
                @endif
            </div>

            <div class="d-none" id="imagePreviewContainer">
                <div class="imageHolder">
                    <img id="picturePreview" src="#" alt="Expense Slip Preview" class="img-fluid rounded" />
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Operational Expense</button>
                <a href="{{ route('miller-admin.wallet-management.view-withdraw') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    $('#operational_expense_slip').change(function() {
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
