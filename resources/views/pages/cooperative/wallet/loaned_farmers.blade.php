@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Loan Applications</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone No.</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Loan Type</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = \Illuminate\Support\Facades\Auth::user()->cooperative->currency @endphp
                            @foreach($loaned_farmers as $key => $lf)

                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($lf->first_name.' '.$lf->other_names)) }}</td>
                                    <td>{{ $lf->phone_no}}</td>
                                    <td>{{$currency.' '.number_format($lf->amount,2,'.',',') }}</td>
                                    <td>{{$currency.' '.number_format($lf->balance,2,'.',',') }}</td>
                                    <td>{{ $lf->type}}</td>
                                    <td>

                                        @if(\Carbon\Carbon::now()->isBefore(\Carbon\Carbon::parse($lf->due_date)))

                                            <div class="badge badge-success ml-2 ">
                                                {{  Carbon\Carbon::parse($lf->due_date)->format('D, d M y') }}
                                            </div>
                                        @else
                                            <div class="badge badge-danger ml-2 ">
                                                {{  Carbon\Carbon::parse($lf->due_date)->format('D, d M y') }}
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            <tr></tr>
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
