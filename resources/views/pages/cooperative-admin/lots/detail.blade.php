@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="path/to/your/custom/styles.css">
@endpush

@section('topItem')
@if($action == 'add_grade_distribution')
<!-- Add Grade Distribution Modal -->
<div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Grade Distribution</h5>
                <button type="button" class="close" onclick="window.location.href='?tab=grade_distributions'"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDeliveryItemForm"
                    action="{{ route('cooperative-admin.lots.store-grade-distribution', $lot->lot_number) }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="product_grade_id">Select Order Item</label>
                        <select name="product_grade_id" id="product_grade_id"
                            class="form-control @error('product_grade_id') is-invalid @enderror" required>
                            <option value="">-- Select Grade --</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                        @error('product_grade_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <div class="input-group">
                            <input type="number" name="quantity"
                                class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                placeholder="Enter quantity" value="{{ old('quantity') }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text">{{ $lot_unit }}</span>
                            </div>
                        </div>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save Grade Distribution</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Grade Distribution Modal -->
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Lot Detail</h4>
        <h4 class="card-subtitle mb-2 text-muted">
            Lot Number: <strong class="text-dark">{{ $lot->lot_number }}</strong>
        </h4>
        <div class="container my-5">
            <div class="row">
                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header text-center bg-gradient-primary text-white">
                            <h5 class="font-weight-bold mb-0 text-white">Total Collection Quantity</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="font-weight-bold text-primary display-4">
                                {{ number_format($lot->total_collection_quantity )}}
                            </h2>
                            <p class="font-weight-bold text-muted">{{ $lot_unit }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header text-center bg-gradient-success text-white">
                            <h5 class="font-weight-bold mb-0">Graded</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="font-weight-bold text-success display-4">
                                {{ number_format($lot->total_graded_quantity ?? 0) }}
                            </h2>
                            <p class="mb-0">of <strong>{{ number_format($lot->total_collection_quantity) }}
                                    {{ $lot_unit }}</strong>
                            </p>

                            <!-- Progress Bar -->
                            <div class="progress mt-3">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $lot->total_collection_quantity > 0 ? ($lot->total_graded_quantity / $lot->total_collection_quantity) * 100 : 0 }}%;"
                                    aria-valuenow="{{ $lot->total_graded_quantity }}" aria-valuemin="0"
                                    aria-valuemax="{{ $lot->total_collection_quantity }}">
                                </div>
                            </div>
                            <p class="mt-2 small text-muted">
                                {{ number_format(($lot->total_graded_quantity / $lot->total_collection_quantity) * 100, 2) }}%
                                Graded</p>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header text-center bg-gradient-warning text-white">
                            <h5 class="font-weight-bold mb-0 text-white">Remaining Quantity</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="font-weight-bold text-warning display-4">
                                {{number_format( $lot->remaining_quantity ?? 0) }}
                            </h2>
                            <p class="font-weight-bold text-muted">{{ $lot_unit }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections' ? 'active' : '' }}" href="?tab=collections">Collections</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'grade_distributions' ? 'active' : '' }}"
                    href="?tab=grade_distributions">Grade Distribution</a>
            </li>
        </ul>

        @if ($tab == 'collections')
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Collection</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $collection)
                    <tr>
                        <td>{{ $collection->collection_number }}</td>
                        <td>{{ number_format($collection->quantity) }} {{ $collection->unit }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'grade_distributions')
        <div class="d-flex justify-content-end mb-3">
            <a href="?tab=grade_distributions&action=add_grade_distribution" class="btn btn-primary">Add Grade
                Distribution</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gradeDistributions as $grade)
                    <tr>
                        <td>{{ $grade->grade }}</td>
                        <td>{{ number_format($grade->quantity) }} {{ $grade->unit }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="path/to/your/custom/scripts.js"></script>
@endpush

@push('custom-scripts')
<script>
// Custom JavaScript for interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add any custom scripts here
});
</script>
@endpush