@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Product Limits</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Premium (Annual)</th>
                                <th>Limit</th>
                                <th>Rate</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php $currency = Auth::user()->cooperative->currency @endphp
                            @foreach($claim_limits as $key => $cl)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $cl->product->name }}</td>
                                    <td>{{ $currency.' '.number_format($cl->product->premium) }}</td>
                                    <td> {{$currency.' '.number_format($cl->amount)}}</td>
                                    <td>{{ $cl->limit_rate.'%' }}</td>
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
