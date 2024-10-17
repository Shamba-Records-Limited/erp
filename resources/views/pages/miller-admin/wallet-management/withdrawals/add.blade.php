@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Withdraw Funds</div>
        <form action="{{route('miller-admin.wallet-management.withdraw')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <div>Account</div>
                <div>Account Number: <span class="text-info font-weight-bold">{{$account->acc_number}}</span></div>
                <div>Current Balance: <span class="text-info font-weight-bold">{{$account->balance}}</span></div>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount">

                @if($errors->has('amount'))
                <span class="help-block text-danger">
                    <span>{{ $errors->first('amount') }}</span>
                </span>
                @endif
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description">

                @if($errors->has('description'))
                <span class="help-block text-danger">
                    <span>{{ $errors->first('description') }}</span>
                </span>
                @endif
            </div>
            <div class="form-group">
                <label>Purpose</label>
                <input type="text" class="form-control" name="purpose" id="purpose" placeholder="Enter Purpose">
                <p>e.g. Payment for Farming, Petty Cash etc.</p>

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
                <label>Withdrawal Slip</label>
                <input type="file" class="form-control" name="withdrawal_slip" id="withdrawal_slip">

                @if($errors->has('withdrawal_slip'))
                <span class="help-block text-danger">
                    <span>{{ $errors->first('withdrawal_slip') }}</span>
                </span>
                @endif
            </div>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">Withdraw</button>
                <a href="{{route('miller-admin.wallet-management.view-withdraw')}}" class="btn btn-primary ml-2">Cancel</a>
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