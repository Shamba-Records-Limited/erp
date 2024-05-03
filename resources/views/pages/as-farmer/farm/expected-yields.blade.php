@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Yield Setups</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Yields From</th>
                                <th>Production Per</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expected_yields as $key => $yield)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $yield->crop_id ? ( $yield->crop->product_id ? 'Crop: '.$yield->crop->product->name.' ('.$yield->crop->variety.')' : '-') :
                                            'Livestock: '.$yield->livestock->name.' ('.$yield->livestock->breed->name.')' }}
                                    </td>
                                    <td>{{ ucwords($yield->volume_indicator) }}</td>
                                    <td>{{ $yield->quantity.' '.$yield->farm_unit->name }}</td>
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
    <script>
        const alterProduct = () => {
            const type = $("#type").val();
            if (type === 'farm') {
                $("#for-crop").removeClass('d-none')
                $("#for-livestock").addClass('d-none')
                return;
            }
            if (type === 'livestock') {
                $("#for-crop").addClass('d-none')
                $("#for-livestock").removeClass('d-none')
            } else {
                $("#for-crop").addClass('d-none')
                $("#for-livestock").addClass('d-none')
            }
        }
    </script>
@endpush

@push('custom-scripts')
@endpush
