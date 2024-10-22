@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card pt-6">
    <div class="card-body">
        <div class="card-title">Deposit Funds</div>
        <form action="{{route('miller-admin.wallet-management.deposit')}}" method="post" enctype="multipart/form-data">
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
            <!-- source of funds -->
             <div class="form-group">
                <label>Source of Funds</label>
                <input type="text" class="form-control" name="source_of_funds" id="source_of_funds" placeholder="Enter Source of Funds">
                <p>e.g. Bank, ATM, Cheque, etc.</p>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description">
            </div>
            <div class="d-none" id="imagePreviewContainer">
                <div class="imageHolder pl-2">
                    <img id="picturePreview" src="#" alt="pic" height="150px" width="150px" />
                </div>
            </div>
            <div class="form-group">
                <label>Deposit Slip</label>
                <input type="file" class="form-control" name="deposit_slip" id="deposit_slip">
            </div>

            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">Deposit</button>
                <a href="{{route('miller-admin.wallet-management.view-deposit')}}" class="btn btn-primary ml-2">Cancel</a>
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
</script>
@endpush