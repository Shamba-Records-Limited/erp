@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Crop Calendar Stages</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Crop</th>
                                <th>Name</th>
                                <th>Period</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cropCalendarStages as $key => $stage)
                                @if($stage->type == 1)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $stage->crop_id && $stage->crop->product_id ? ucwords(strtolower($stage->crop->product->name.'('.$stage->crop->variety.')')) : '-' }}</td>
                                    <td>{{ $stage->name }}</td>
                                    <td>{{$stage->period.' '.$stage->period_measure}}</td>
                                </tr>
                                @endif
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
                    <h4 class="card-title">Registered Livestock/Poultry Calendar Stages</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Livestock</th>
                                <th>Name</th>
                                <th>Period</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cropCalendarStages as $key => $stage)
                                @if($stage->type == 2)
                                    <tr>
                                        <td>{{++$key }}</td>
                                        <td>{{ $stage->livestock_id ? ucwords(strtolower($stage->livestock->name.'( '.$stage->livestock->breed->name.' '.$stage->livestock->animal_type.')')) : '-' }}</td>
                                        <td>{{ $stage->name }}</td>
                                        <td>{{$stage->period.' '.$stage->period_measure}}</td>
                                    </tr>
                                @endif
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
    <script>
        const changeStageType = () => {
            const type = $('#type').val()
            if (type === '1') {
                $('#showCrop').removeClass('d-none')
                $('#showLivestock').addClass('d-none')
            } else if (type === '2') {
                $('#showCrop').addClass('d-none')
                $('#showLivestock').removeClass('d-none')
            } else {
                $('#showCrop').addClass('d-none')
                $('#showLivestock').addClass('d-none')
            }
        }
    </script>
@endpush
