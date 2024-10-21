@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Valuations</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency; @endphp
                            @foreach($valuations as $key => $valuation)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$valuation->type }}</td>
                                    <td>{{$currency.' '.number_format($valuation->amount,2) }}</td>
                                    <td>{{$valuation->description }}</td>
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
