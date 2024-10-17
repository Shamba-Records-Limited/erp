@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Crop Calendars</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Production per</th>
                                <th>Yields</th>
                                <th>Expected Yields</th>
                                <th>Deviation</th>
                                <th>Units</th>
                                <th>Period</th>
                                <th>Comments</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                function calculate_deviation($expected, $actual): string
                                {
                                    $difference = $actual - $expected ;
                                    if($difference <  0){
                                        return "$difference (less)";
                                    }

                                    if($difference >  0){
                                        return "$difference (more)";
                                    }
                                    return $difference;
                                }
                            @endphp
                            @foreach($farmer_yields_crop as $key => $yield)
                                @php $expected_yield = $yield->expected_yields->quantity * $yield->volume_indicator_count@endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $yield->crop->product_id ? ucwords(strtolower( $yield->crop->product->name.'('. $yield->crop->variety.')')) : '-'}}</td>
                                    <td>{{ ucwords($yield->expected_yields->volume_indicator.' ('.$yield->volume_indicator_count.')') }}</td>
                                    <td>{{ number_format($yield->yields) }}</td>
                                    <td>{{ number_format($expected_yield) }}</td>
                                    <td>{{ calculate_deviation($expected_yield, $yield->yields) }}</td>
                                    <td>{{ $yield->unit->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($yield->date)->format('M, Y') }}</td>
                                    <td>{{ $yield->comments }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Livestock/Poultry Calendars</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Production per</th>
                                <th>Yields</th>
                                <th>Expected Yields</th>
                                <th>Deviation</th>
                                <th>Units</th>
                                <th>Period</th>
                                <th>Comments</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($farmer_yields_livestock as $key => $yield)
                                @php $expected_yield = $yield->expected_yields->quantity * $yield->volume_indicator_count@endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ ucwords(strtolower($yield->product))}}</td>
                                    <td>{{ ucwords($yield->expected_yields->volume_indicator.' ('.$yield->volume_indicator_count.')') }}</td>
                                    <td>{{ number_format($yield->yields) }}</td>
                                    <td>{{ number_format($expected_yield) }}</td>
                                    <td>{{ calculate_deviation($expected_yield, $yield->yields) }}</td>
                                    <td>{{ $yield->unit->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($yield->date)->format('M, Y') }}</td>
                                    <td>{{ $yield->comments }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
