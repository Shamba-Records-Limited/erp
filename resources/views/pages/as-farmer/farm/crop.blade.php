@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Crops</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Variety</th>
                                <th>Farm Unit</th>
                                <th>Recommended Areas</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($crops as $key => $crop)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $crop->product_id ? $crop->product->name : '-' }}</td>
                                    <td>{{ $crop->variety }}</td>
                                    <td>{{ $crop->expected_yields.' '.($crop->farm_unit_id ? $crop->farm_unit->name : '')}}</td>
                                    <td>{{ $crop->recommended_areas }}</td>
                                    <td>{{ $crop->description }}</td>
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
