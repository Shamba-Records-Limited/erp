
@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#reportFilterAccordion"
                            aria-expanded="@if(request()->date) true @else false @endif"
                            aria-controls="reportFilterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(request()->date) show @endif "
                         id="reportFilterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('home') }}" method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Period</label>
                                    <input type="text" name="date"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="date"
                                           value="{{ request()->date }}">

                                    @if ($errors->has('date'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('date')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <a href="{{ route('home') }}" type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('/assets/js/dashboard.js') }}"></script>
    @if($user->hasRole('cooperative admin'))
        @include('pages.charts.admin-dashboard')
    @elseif($user->hasRole('farmer'))
        @include('pages.charts.as-farmer.main-dashboard')
    @endif
    <script>
      dateRangePickerFormats("date")
    </script>
@endpush
