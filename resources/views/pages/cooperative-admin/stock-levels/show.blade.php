@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Stock Details</h4>
                <div class="table-responsive mt-5">
                    <table class="table table-hover dt clickable mb-6">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lot Number</th>
                                <th>Quantity</th>
                                <th>Unit Measure</th>
                                <th>Date Collected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQuantity = 0;
                                $unit = $productStock->isNotEmpty() ? $productStock[0]->unit : ''; // Set unit from first entry
                            @endphp
                            @foreach($productStock as $index => $stock)
                                @php $totalQuantity += $stock->quantity; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock->lot_number }}</td>
                                    <td>{{ number_format($stock->quantity) }}</td>
                                    <td>{{ $stock->unit }}</td>
                                    <td>{{ $stock->date_collected }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total:</th>
                                <th>{{ number_format($totalQuantity) }} {{ $unit }}</th>
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
