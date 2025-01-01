@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Stock Levels</h4>
                <div class="table-responsive mt-5">
                    <table class="table table-hover dt clickable mb-6">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Total Collected</th>
                                <th>Unit</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $grandTotal = 0;
                                $unit = $stockLevels->isNotEmpty() ? $stockLevels[0]->unit : ''; // Assuming single unit type across stockLevels
                            @endphp
                            @foreach($stockLevels as $index => $stock)
                                @php $grandTotal += $stock->total_collected; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ number_format($stock->total_collected) }}</td>
                                    <td>{{ $stock->unit }}</td>
                                    <td>
                                        <a href="{{ route('admin.stock-levels.show', $stock->id) }}" class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total:</th>
                                <th>{{ number_format($grandTotal) }} {{ $unit }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
