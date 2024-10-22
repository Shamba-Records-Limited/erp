@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pending Payments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone No.</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = \Illuminate\Support\Facades\Auth::user()->cooperative->currency; $total = 0; @endphp
                            @foreach($pending_payments as $key => $pp)
                                @php $total += $pp->current_balance @endphp
                                <tr>
                                    <td>{{++$key }}</td>

                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $pp->id) }}">
                                        {{ ucwords(strtolower($pp->first_name.' '.$pp->other_names)) }}
                                        </a>
                                    </td>
                                    <td>{{ $pp->phone_no}}</td>
                                    <td>{{$currency.' '.number_format($pp->current_balance,2,'.',',') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td></td>
                                <td>
                                    <b>{{$currency.' '.number_format($total,2,'.',',') }}</b>
                                </td>
                            </tr>
                            </tfoot>
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