@extends('layouts.app')

@push('plugin-styles')
{{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
<div class="header bg-custom-green pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Total Departments</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $data['departments'] }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="mdi mdi-cube"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <i class="mdi mdi-alert-octagon mr-1"></i> All departments in cooperative
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">All Employees</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $data['all_employees'] }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="mdi mdi-receipt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <i class="mdi mdi-bookmark-outline mr-1"></i> Total no. of employees
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Employees on Leave</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $data['employees_on_leave'] }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="mdi mdi-poll-box"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <i class="mdi mdi-calendar mr-1"></i> Currently on leave
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">New Employees</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $data['new_employees'] }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="mdi mdi-account-box-multiple"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <i class="mdi mdi-reload mr-1"></i> Employed last 1 month
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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