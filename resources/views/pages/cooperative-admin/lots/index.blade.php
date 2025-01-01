@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Lots</div>
        <div class="table-responsive">
            <table class="table table-hover dt mb-4 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Quantity</th>
                        <th>Number of Collections</th>
                        <th>Ungraded</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalQuantity = 0;
                    $totalCollectionsCount = 0;
                    $totalUngraded = 0;
                    @endphp
                    @foreach($lots as $key => $lot)
                    @php
                    $quantity = $lot->quantity;
                    $graded = $lot->graded ?? 0;
                    $ungraded = $quantity - $graded;

                    // Accumulate totals
                    $totalQuantity += $quantity;
                    $totalCollectionsCount += $lot->collections_count;
                    $totalUngraded += $ungraded;
                    @endphp
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td><a
                                href="{{ route('cooperative-admin.lots.detail', $lot->lot_number) }}">{{ $lot->lot_number }}</a>
                        </td>
                        <td>{{ number_format($quantity) }} {{$lot->unit_name}}</td>
                        <td>{{ $lot->collections_count }}</td>
                        <td>{{ number_format($ungraded) }} {{$lot->unit_name}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="mt-4">
                    <tr>

                        <th colspan=" 2" class="text-right">Totals:</th>
                        <th>{{ number_format($totalQuantity) }} KG</th>
                        <th>{{ $totalCollectionsCount }}</th>
                        <th>{{ number_format($totalUngraded) }} KG</th>
                        <th></th>
                    </tr>
                    </tfootc>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush