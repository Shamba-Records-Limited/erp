@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
{{--                    <a class="btn btn-sm btn-info float-right text-white"--}}
{{--                       href="{{ route('download.savings.installment.report',[$saving_account_id, 'csv']) }}">--}}
{{--                        <i class="mdi mdi-download"></i> CSV--}}
{{--                    </a>--}}

{{--                    <a class="btn btn-sm btn-github float-right text-white"--}}
{{--                       href="{{ route('download.savings.installment.report',[$saving_account_id, 'xlsx']) }}" style="margin-right: -5px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> Excel--}}
{{--                    </a>--}}
{{--                    <a class="btn btn-sm btn-success float-right text-white"--}}
{{--                       href="{{ route('download.savings.installment.report',[$saving_account_id, env('PDF_FORMAT')]) }}" style="margin-right: -8px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> PDF--}}
{{--                    </a>--}}

                    <h4 class="card-title">Installments for saving type: {{ $saving_type->type }}</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Ref</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                            @endphp
                            @foreach($saving_statement as $key => $st)
                                @php
                                    $total_amount += $st->amount
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $currency.' '.number_format($st->amount) }}</td>
                                    <td>{{$st->reference }}</td>
                                    <td>{{\Carbon\Carbon::parse($st->date)->format('D, d M Y  H:i:s') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Totals</th>
                                    <th colspan="3">{{$currency.' '.number_format($total_amount)}}</th>
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
