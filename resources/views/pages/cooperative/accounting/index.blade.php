@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                        href="{{ route('cooperative.accounting.reports.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                        href="{{ route('cooperative.accounting.reports.download','xlsx') }}"
                        style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                        href="{{ route('cooperative.accounting.reports.download', 'pdf') }}"
                        style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Financial Period Summary</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Type</th>
                                <th>Balance CF</th>
                                <th>Balance BF</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fy as $key => $f)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ $f->start_period }}</td>
                                    <td>{{ $f->end_period }}</td>
                                    <td>
                                        @if(strtolower($f->type) === 'monthly')
                                            <badge class="badge badge-warning text-white">{{ strtoupper($f->type)  }}</badge>
                                        @elseif(strtolower($f->type) === 'quarterly')
                                            <badge class="badge badge-dark text-white">{{ strtoupper($f->type)  }}</badge>
                                        @else
                                            <badge class="badge badge-primary text-white">{{ strtoupper($f->type)  }}</badge>
                                        @endif
                                    </td>
                                    <td>{{ $f->balance_cf !== null ? number_format($f->balance_cf, 2, '.', ','): '-' }}</td>
                                    <td>{{ number_format($f->balance_bf, 2, '.', ',') }}</td>
                                    <td>
                                        @if($f->active)
                                            <button type="button"
                                                    class="btn btn-sm btn-success btn-rounded btn-fw disabled">Active
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn btn-sm btn-secondary btn-rounded btn-fw disabled">
                                                {{ \Illuminate\Support\Carbon::parse($f->end_period)->gt(\Illuminate\Support\Carbon::now()) ? "Inactive" : "Closed" }}
                                            </button>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{ route('cooperative.accounting.report_type', $f->id) }}"
                                                   class="btn btn-sm btn-info btn-rounded btn-sm btn-fw">Reports</a>
                                            </div>
                                            <div class="col-md-6">
                                                @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['edit']))
                                                <form method="post"
                                                      action="{{ route('cooperative.accounting.close_financial_period', $f->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger btn-rounded btn-fw btn-sm">
                                                        Close
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
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
