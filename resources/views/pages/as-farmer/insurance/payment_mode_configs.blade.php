@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Mode Rate Adjustments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Mode</th>
                                <th>Adjusted Rate</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($configs as $key => $config)
                                @php $mode =
                                            $config->payment_mode == \App\InsuranceSubscriber::MODE_MONTHLY ? 'Monthly' :
                                            ($config->payment_mode == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'Quarterly' :
                                             ($config->payment_mode == \App\InsuranceSubscriber::MODE_WEEKLY ? 'Weekly' : 'Annually'));
                                    ;@endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $mode }}</td>
                                    <td>{{ number_format($config->adjusted_rate,2).'%'}}</td>
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
