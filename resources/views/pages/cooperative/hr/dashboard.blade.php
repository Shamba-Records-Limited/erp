@extends('layouts.app')

@push('plugin-styles')
{{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
<div class="row">
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-cube text-danger icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Total Departments</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['departments'] }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> All departments in cooperative </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-receipt text-warning icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">All Employees</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['all_employees'] }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total no. of employees </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-poll-box text-success icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Employees on Leave</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['employees_on_leave'] }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Currently on leave </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">New Employees</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['new_employees'] }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Employed last 1 month </p>
      </div>
    </div>
  </div>
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
          <h2 class="card-title mb-0">Employee Gender Chart</h2>
        </div> 
        <div class="chart-container">
        {!! $gender_chart->container() !!}
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <p class="mb-0 text-right">Time Sheet</p>
          <div class="table-responsive">
            <table class="table table-hover dt">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Employee No</th>
                    <th>Name</th>
                    <th>Activity</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($data['employees'] as $key => $item)
                      <tr>
                        <td>{{++$key }}</td>
                        <td>{{$item->employee->employee_no }}</td>
                        <td>{{$item->first_name }} {{$item->last_name }}</td>
                        <td>
                          @if($item->employee->employeeLeave->count() > 0)
                            {{ $item->employee->employeeLeave[0]->status }}
                          @endif
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
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('/assets/js/dashboard.js') }}"></script>
@endpush