@extends('layouts.app')

@push('plugin-styles')
{{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
<div class="row">
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-cube text-danger icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">No. of collections</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['count_collections'] }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Collections </p>
      </div>
    </div>
  </div>
  <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-receipt text-warning icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Farmers who collected</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['farmers']->count() }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total no. of farmers </p>
      </div>
    </div>
  </div> -->
  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-poll-box text-success icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Products Collected</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $data['products']->count() }}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
          <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>All products in collection </p>
      </div>
    </div>
  </div>

  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
          <h2 class="card-title mb-0">Collections</h2>
        </div> 
        <div class="chart-container">
        {!! $collections_chart->container() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <p class="mb-0 text-right">Latest Collections</p>
          <div class="table-responsive">
            <table class="table table-hover dt">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Produce</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($data['latest'] as $key => $item)
                      <tr>
                        <td>{{++$key }}</td>
                        <td>{{$item->product->name }}</td>
                        <td>{{ number_format($item->quantity,2,'.',',') }} {{$item->product->unit->name }}   </td>
                        <td>{{ \Carbon\Carbon::create($item->date_collected)->format('Y-m-d') }}   </td>
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