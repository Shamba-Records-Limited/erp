@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Transaction Details</h4>
        <div class="row">
            <div class="col-md-6">
                <p><strong class="label mr-2"><i class="fas fa-file-invoice user-icon"></i>Transaction Number:</strong> {{ $transaction->transaction_number }}</p>
                <p><strong class="label mr-2"><i class="fas fa-dollar-sign user-icon"></i>Amount:</strong> <span class="amount">{{ number_format($transaction->amount, 2) }}</span></p>
                <p><strong class="label mr-2"><i class="fas fa-comment-dots user-icon"></i>Description:</strong> {{ $transaction->description }}</p>
            </div>
            <div class="col-md-6">
                <p><strong class="label mr-2"><i class="fas fa-tags user-icon"></i>Type:</strong> {{ $transaction->type }}</p>
                <p><strong class="label mr-2"><i class="fas fa-check-circle user-icon"></i>Status:</strong> {{ $transaction->status }}</p>
                <p><strong class="label mr-2"><i class="fas fa-calendar-alt user-icon"></i>Created At:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M, Y h:i A') }}</p>
            </div>
        </div>

        <!-- Table for lot details -->
        <h4 class="mt-4">Lot Details</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Lot Number</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lots as $lot)
                        <tr>
                            <td>{{ $lot->lot_number }}</td>
                            <td>{{ number_format($lot->quantity, 2) }} </td>
                            <td>{{ $lot->unit ?? 'N/A' }}</td> <!-- Assuming unit column exists or is set to 'N/A' if null -->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No lot details available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<!-- Add any additional plugin scripts if needed -->
@endpush

@push('custom-scripts')
<!-- Add any custom scripts if needed -->
@endpush
