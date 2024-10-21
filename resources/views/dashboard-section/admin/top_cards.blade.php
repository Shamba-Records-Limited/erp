<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-1">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-account text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Our Farmers</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{$data->farmers_count}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('cooperative.farmers.show') }}">
                        <i class="mdi mdi-account-multiple mr-1" aria-hidden="true"></i> Who we partner with
                    </a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-2">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-receipt text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Collections</p>
                        <div class="fluid-container">
                            <h5 class="font-weight-medium text-right mb-0">{{ $data->collections}}</h5>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('farmer.collections.show') }}">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> This Month collections
                    </a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-3">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-cash-multiple text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Income</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }} {{ $data->income }}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('cooperative.accounting.charts_of_account') }}">
                        <i class="mdi mdi-cash mr-1" aria-hidden="true"></i> Accounting Details </a>
                </p>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-4">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-cash-multiple text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Expenses</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }}
                                {{ $data->expense }}
                            </h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('cooperative.accounting.charts_of_account') }}">
                        <i class="mdi mdi-cash-multiple mr-1" aria-hidden="true"></i> Accounting Details </a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-5">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-chart-bar text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Profit Margins</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }}
                                {{ $data->profit }}
                            </h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('cooperative.accounting.reports') }}">
                        <i class="mdi mdi-chart-bar mr-1" aria-hidden="true"></i> Accounting Report </a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-6">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-receipt text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Sales</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }}
                                {{ $data->sales }}
                            </h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('sales.pos') }}">
                        <i class="mdi mdi-receipt mr-1" aria-hidden="true"></i> Total Sales </a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-7">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-account-multiple text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Employees</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ number_format($data->employees) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('hr.employees.show') }}">
                        <i class="mdi mdi-account-multiple mr-1" aria-hidden="true"></i> Employees </a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-1">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-cash text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Available Stock Value</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }}
                                {{ $data->stock_value }}
                            </h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('cooperative.manufacturing.production') }}">
                        <i class="mdi mdi-cash mr-1" aria-hidden="true"></i> Production </a>
                </p>
            </div>
        </div>
    </div>
</div>
