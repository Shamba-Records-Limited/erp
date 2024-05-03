@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-4  col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="profile-image">

                        @if($farmer->profile_picture)
                            <div class="form-group col-12">
                                <img src="{{url('storage/'.$farmer->profile_picture)}}"
                                     class="ml-auto mr-auto d-block"
                                     height="150px" width="150px"/>
                            </div>
                        @else
                            <img src="{{ url('assets/images/avatar.png') }}" height="150px" width="150px"
                                 alt="profile image" class="ml-auto mr-auto d-block">
                        @endif

                    </div>
                    <div class="text-wrapper mt-3 ml-3">
                        <h6><b>Name:</b><small
                                    class="text-muted ml-4 float-right">{{ ucwords(strtolower($farmer->first_name).' '.strtolower($farmer->other_names) ) }}</small>
                        </h6>
                        <hr>
                        <h6><b>Email:</b> <small
                                    class="text-muted ml-4 float-right">{{ strtolower($farmer->email) }}</small>
                        </h6>
                        <hr>
                        <h6><b>username:</b> <small
                                    class="text-muted ml-4 float-right">{{ $farmer->username }}</small>
                        </h6>
                        <hr>
                        <h6><b>Phone: </b> <small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->phone_no }}</small>
                        </h6>
                        <hr>
                        <h6><b>ID No:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->id_no }}</small>
                        </h6>
                        <hr>
                        <h6><b>Gender:</b> <small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->gender == "M" ? "Male": ($farmer->farmer->gender == "F" ? "Female" : "Other") }}</small>
                        </h6>
                        <hr>
                        <h6><b>DOB:</b><small
                                    class="text-muted ml-4 float-right">{{  Carbon\Carbon::parse($farmer->farmer->dob)->format('d F Y') }}</small>
                        </h6>
                        <hr>
                        <h6><b>Age:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->age }}
                                years</small></h6>
                        <hr>
                        <h6><b>Country:</b><small class="text-muted ml-4 float-right"> <span
                                        class="mr-2"> <img
                                            src="{{ asset(get_country_flag($farmer->farmer->country->iso_code)) }}"
                                            height="25px"
                                            width="45px"/></span> {{$farmer->farmer->country->name }}
                            </small></h6>
                        <hr>
                        <h6><b>County:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->county }}</small>
                        </h6>
                        <hr>
                        <h6><b>Route:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->route->name }}</small>
                        </h6>
                        <hr>
                        <h6><b>Member No:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->member_no }}</small>
                        </h6>
                        <hr>
                        <h6><b>Customer Type:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->customer_type }}</small>
                        </h6>
                        <hr>
                        <h6><b>KRA Pin:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->id_no }}</small>
                        </h6>
                        <hr>
                        <h6><b>Bank Details:</b><small
                                    class="text-muted ml-4 float-right">{{ $farmer->farmer->bank_account }}
                                , {{ $farmer->farmer->bank_branch->bank->name }}
                                ,{{ $farmer->farmer->bank_branch->name }}</small></h6>
                        <hr>

                        <a class=" mt-2 btn btn-info btn-sm btn-block"
                           href="{{ route('cooperative.farmer.edit', $farmer->farmer->id) }}">
                            Edit <span class="mdi mdi-file-edit"></span>
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="row">
                <div class="col-lg-4 col-sm-12 grid-margin">
                    <div class="card card-statistics  card-bg-color-1">
                        <div class="card-body">
                            <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                <div class="float-left">
                                    <i class="mdi mdi-crop-landscape text-danger icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right text-white">Farm size</p>
                                    <div class="fluid-container">
                                        <h3 class="font-weight-medium text-right mb-0">{{ number_format($farmer->farmer->farm_size,2,'.',',') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                <i class="mdi mdi-alert-octagon mr-1 text-white"
                                   aria-hidden="true"></i>
                                <span class="text-white">Total farm size in acres</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12 grid-margin">
                    <div class="card card-statistics  card-bg-color-2">
                        <div class="card-body">
                            <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                <div class="float-left">
                                    <i class="mdi mdi-flower text-warning icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right text-white">Products</p>
                                    <div class="fluid-container">
                                        <h3 class="font-weight-medium text-right mb-0">{{$products_they_supply}}</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                <i class="mdi mdi-bookmark-outline mr-1 text-white"
                                   aria-hidden="true"></i>
                                <a href="{{ route('cooperative.farmer.products.suppliers.show', $farmer->id) }}"
                                   class="text-white"><span
                                            class="text-white">Products Supplied </span></a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12 grid-margin">
                    <div class="card card-statistics  card-bg-color-3">
                        <div class="card-body">
                            <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                <div class="float-left">
                                    <i class="mdi mdi-cow text-success icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right text-white">Livestock</p>
                                    <div class="fluid-container">
                                        <h3 class="font-weight-medium text-right mb-0">{{$total_livestock}}</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                <i class="mdi mdi-bookmark-outline mr-1 text-white"
                                   aria-hidden="true"></i>
                                <span class="text-white">Livestock owned By farmer</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-12 grid-margin">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Vet Schedules</h4>
                                    <div class="shedule-list d-flex align-items-center justify-content-between mb-3">
                                        <h3>{{ Carbon\Carbon::now()->format('F, Y') }}</h3>
                                        <small>{{$bookings->count()}} Bookings</small>
                                    </div>

                                    @foreach($bookings as $booking)

                                        <div class="event border-bottom py-3">
                                            <p class="mb-2 font-weight-medium">{{$booking->event_name}}</p>
                                            <div class="d-flex align-items-center">
                                                <div class="badge {{ Illuminate\Support\Arr::random(['badge-warning','badge-success','badge-danger','badge-info'],1)[0]}} mr-2">
                                                    {{  Carbon\Carbon::parse($booking->event_start)->format('d, F H:i') }}
                                                </div>
                                                {{--                                <small class="text-muted ml-2">London, UK</small>--}}
                                                -
                                                <div class="badge {{ Illuminate\Support\Arr::random(['badge-warning','badge-success','badge-danger','badge-info'],1)[0]}} ml-2 ">
                                                    {{  Carbon\Carbon::parse($booking->event_end)->format('d, F H:i') }}
                                                </div>
                                                <small class="text-muted ml-2">{{ ucwords(strtolower($booking->vet->first_name.' '.$booking->vet->other_names)) }}</small>
                                                <div class="image-grouped ml-auto">
                                                    @if($booking->vet->vet->profile_image and file_exists('storage/'.$booking->vet->vet->profile_image))
                                                        <img src="{{ asset('storage/'.$booking->vet->vet->profile_image )}}"
                                                             alt="profile">
                                                    @else
                                                        <img src="{{ url('assets/images/avatar.png') }}"
                                                             alt="profile"
                                                             class="rounded-circle t-image">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12 mt-2">
                            <div class="card card-statistics card-bg-color-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-wallet text-success icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <p class="mb-0 text-right text-white">Available
                                                Balance</p>
                                            <div class="fluid-container">
                                                <h3 class="font-weight-medium text-right mb-0">{{ $farmer->cooperative->currency }} {{ number_format($wallet? $wallet->available_balance : 0, 2, '.',',') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                        <i class="mdi mdi-alert-octagon mr-1 text-white"
                                           aria-hidden="true"></i>
                                        <span class="text-white">Farmer's Available Balance </span>
                                    </p>
                                </div>

                            </div>

                            <div class="card card-statistics card-bg-color-5 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-bank text-warning icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <p class="mb-0 text-right text-white">Pending
                                                Payments</p>
                                            <div class="fluid-container">
                                                <h3 class="font-weight-medium text-right mb-0">{{ $farmer->cooperative->currency }} {{ number_format($wallet ? $wallet->current_balance : 0, 2, '.',',') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                        <i class="mdi mdi-bookmark-outline mr-1 text-white"
                                           aria-hidden="true"></i>
                                        <span class="text-white">Farmer's Pending Payments</span>
                                    </p>
                                </div>

                                <button type="button"
                                        class="btn btn-info btn-fw btn-sm float-right m-2"
                                        data-toggle="modal" data-target="#payFarmerModal">
                                    <span class="mdi mdi-paypal"></span>Pay Farmer
                                </button>

                                {{--  modals edit start--}}
                                <div class="modal fade" id="payFarmerModal" tabindex="-1"
                                     role="dialog"
                                     aria-labelledby="payModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="payModalLabel">
                                                    Pay {{ ucwords(strtolower($farmer->first_name).' '.strtolower($farmer->other_names) ) }}</h5>
                                                <button type="button" class="close"
                                                        data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('cooperative.wallet.pay_farmer') }}"
                                                  method="post">
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <label for="amount">Amount</label>
                                                            <input type="text" name="amount"
                                                                   class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                                   id="amount" placeholder="200"
                                                                   value="{{ old('amount') }}"
                                                                   required>
                                                            @if ($errors->has('amount'))
                                                                <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('amount')  }}</strong>
                                                        </span>
                                                            @endif
                                                        </div>

                                                        <input type="hidden" name="farmer_id"
                                                               value="{{$farmer->farmer->id}}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        Pay
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{--  modal end   --}}

                            </div>

                            {{--  reports --}}

                            <div class="card card-statistics card-bg-color-2 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-download text-danger icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <h3 class="font-weight-medium text-right mb-0">View
                                                Collections </h3>
                                        </div>
                                        <div class="container-fluid">
                                            <a class="btn btn-sm btn-danger btn-rounded float-right text-white"
                                               href="{{ route('cooperative.collections.farmer.view', $farmer->farmer->id) }}">
                                                View
                                            </a>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card card-statistics card-bg-color-5 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-download text-primary icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <h3 class="font-weight-medium text-right mb-0">View
                                                Payments</h3>
                                        </div>

                                        <div class="container-fluid">
                                            <a class="btn btn-sm btn-primary btn-rounded float-right text-white"
                                               href="{{route('cooperative.wallet.show_payment_histories', $farmer->farmer->id)}}">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-statistics card-bg-color-2 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-cash text-success icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <h3 class="mb-0 text-right text-white">Purchases</h3>
                                            <div class="fluid-container">
                                                <h3 class="font-weight-medium text-right mb-0">{{ $farmer->cooperative->currency }} {{ number_format($purchases, 2, '.',',') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid">
                                        <a class="btn btn-sm btn-primary btn-rounded float-right text-white"
                                           href="{{route('cooperative.farmer.purchases', $farmer->farmer->id)}}">
                                            View
                                        </a>
                                    </div>
                                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                        <i class="mdi mdi-alert-octagon mr-1 text-white"
                                           aria-hidden="true"></i>
                                        <span class="text-white">Farmer's Total Purchases </span>
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{--  collected this month --}}
                <div class="col-lg-6 col-sm-12 grid-margin">
                    <div class="row flex-grow">
                        <div class="col-md-6 col-xl-12 grid-margin grid-margin-md-0 grid-margin-xl stretch-card">
                            <div class="card card-revenue">
                                <div class="card-body d-flex align-items-center">
                                    <div class="d-flex flex-grow">
                                        <div class="mr-auto">
                                            <p class="highlight-text mb-0 text-white"> {{$collections->count()}}</p>
                                            <p class="text-white"> Collected this month </p>
                                            {{--                                        <div class="badge badge-pill"> 18% </div>--}}
                                        </div>
                                        <div class="ml-auto align-self-end">
                                            <div id="revenue-chart" sparkType="bar"
                                                 sparkBarColor="#e6ecf5"
                                                 barWidth="2"> {{$collection_quantity}} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-12 stretch-card">
                            <div class="card card-revenue-table">
                                <div class="card-body">

                                    @php
                                        $total_price = 0;
                                    @endphp
                                    @foreach($collections as $c)

                                        @php
                                            $price = $c->quantity * $c->product->buying_price;
                                            $total_price += $price
                                        @endphp
                                        <div class="revenue-item d-flex">
                                            <div class="revenue-desc">
                                                <h6>{{$c->product->name}}</h6>
                                                <p class="font-weight-light">{{ $c->quantity }} {{$c->product->unit->name}} </p>
                                            </div>
                                            <div class="revenue-amount">
                                                <p class="text-primary"> {{$farmer->cooperative->currency}} {{ number_format(($price),2,'.',',') }} </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="revenue-amount text-right py-3">
                                        <h2 class="text-primary"> {{$farmer->cooperative->currency}} {{ number_format(($total_price),2,'.',',') }} </h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-12 stretch-card">
                            <div class="card card-statistics card-bg-color-1 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-cash-multiple text-warning icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <p class="mb-0 text-right text-white">Current
                                                Savings</p>
                                            <div class="fluid-container">
                                                <h3 class="font-weight-medium text-right mb-0">{{ $farmer->cooperative->currency }} {{ number_format($total_savings, 2, '.',',') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                        <i class="mdi mdi-bookmark-outline mr-1 text-white"
                                           aria-hidden="true"></i>
                                        <span class="text-white">Farmer's Saving</span>
                                    </p>
                                </div>

                                <a href="{{ route('cooperative.farmer.savings', $farmer->farmer->id) }}"
                                   class="btn btn-info btn-fw btn-sm float-right m-2 text-white">
                                    View Savings
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-12 stretch-card">
                            <div class="card card-statistics card-bg-color-2 mt-2">
                                <div class="card-body">
                                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                        <div class="float-left">
                                            <i class="mdi mdi-cash-multiple text-warning icon-lg"></i>
                                        </div>
                                        <div class="float-right">
                                            <p class="mb-0 text-right text-white">Amount in
                                                Loans</p>
                                            <div class="fluid-container">
                                                <h3 class="font-weight-medium text-right mb-0">{{ $farmer->cooperative->currency }} {{ number_format($total_loans, 2, '.',',') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                                        <i class="mdi mdi-bookmark-outline mr-1 text-white"
                                           aria-hidden="true"></i>
                                        <span class="text-white">Farmer's Loan</span>
                                    </p>
                                </div>

                                <a href="{{route('cooperative.farmer.loans', $farmer->farmer->id)}}"
                                   class="btn btn-info btn-fw btn-sm float-right m-2 text-white">
                                    View Loans
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    <div class="row">--}}
    {{--        <div class="col-lg-12 grid-margin stretch-card">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-body">--}}

    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush

@push('custom-scripts')

    <script src="{{ asset('/assets/js/chart.js') }}"></script>

    <script>
      if ($("#revenue-chart").length) {
        $("#revenue-chart").sparkline("html", {
          enableTagOptions: true,
          width: "100%",
          height: "70",
          fillColor: "false",
          barWidth: 2,
          barSpacing: 10,
          chartRangeMin: 0
        });
      }
    </script>
@endpush
