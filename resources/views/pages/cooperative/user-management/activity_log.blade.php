@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#filterTransactions"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterTransactions">
                        <span class="mdi mdi-search-web"></span>Filter
                    </button>
                    <div class="collapse @if (request()->get('employee') != null) show @endif " id="filterTransactions">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Activity Log</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.activity_log') }}" method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="farmer">Employee</label>
                                    <select name="employee" id="employee"
                                            class="form-control select2bs4"
                                    >
                                        <option value="">---Select Employee---</option>
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->id}}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                                                {{ucwords(strtolower($employee->first_name.' '.$employee->other_names))}}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="purpose">Date Range</label>
                                    <input type="text" name="dates"
                                           class="form-control date-range{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                           id="dates" value="{{ request()->get('dates') ?? old('dates') }}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <button type="submit" class="btn btn-sm btn-primary btn-fw btn-block"
                                            id="submit-btn">
                                        Filter
                                    </button>
                                </div>

                                @if(request()->get('employee') || request()->get('dates'))
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <a href="{{route('cooperative.activity_log')}}"
                                           class="btn btn-sm btn-info btn-fw btn-block" id="submit-btn">
                                            Reset
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['reports'], config('enums.system_permissions')['download']))

                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.activity_log.download', ['type'=>'csv','employee' => request()->get('employee'),'dates' => request()->get('dates')])}}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.activity_log.download',['type'=>'xlsx','employee' => request()->get('employee'),'dates' => request()->get('dates')])}}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.activity_log.download', ['type'=>'pdf','employee' => request()->get('employee'),'dates' => request()->get('dates')])}}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Transaction History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Activity</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                            @endphp
                            @foreach($auditTrails as $key => $auditTrail)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($auditTrail->user->first_name.' '.$auditTrail->user->other_names)) }}</td>
                                    <td>{{ $auditTrail->activity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($auditTrail->created_at)->format('Y-m-d') }}</td>
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
    <script>
        $('input[name="dates"]').daterangepicker({
            locale: {
                format: 'DD-MMM-YYYY'
            }
        });
    </script>
@endpush
