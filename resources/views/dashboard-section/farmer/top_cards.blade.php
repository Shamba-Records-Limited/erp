<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-1">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-crop-landscape text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Farm Size</p>
                        <div class="fluid-container">
                            <h3 class="font-weight-medium text-right mb-0">{{ number_format($user->farmer->farm_size)}}</h3>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('farm.expected-yields') }}">
                        <i class="mdi mdi-account-multiple mr-1" aria-hidden="true"></i> Yield expectations
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
                        <i class="mdi mdi-flower text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Products</p>
                        <div class="fluid-container">
                            <h5 class="font-weight-medium text-right mb-0">{{ $data->product_supplied }}</h5>
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
                        <i class="mdi mdi-cow text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Livestock</p>
                        <div class="fluid-container">
                            <h5 class="font-weight-medium text-right mb-0">{{ $data->total_livestock }}</h5>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('farm.livestock') }}">
                        <i class="mdi mdi-cash mr-1" aria-hidden="true"></i>My Livestock </a>
                </p>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card card-statistics card-bg-color-4">
            <div class="card-body">
                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                    <div class="float-left">
                        <i class="mdi mdi-cash-multiple text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right text-white">Available Balance</p>
                        <div class="fluid-container">
                            <h5 class="font-weight-medium text-right mb-0">
                                {{ $user->cooperative->currency }}
                                {{ number_format($data->wallet? $data->wallet->available_balance : 0, 2, '.',',') }}
                            </h5>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                    <a class="text-white" href="{{ route('farmer.wallet.dashboard') }}">
                        <i class="mdi mdi-cash mr-1" aria-hidden="true"></i>Wallet </a>
                </p>
            </div>
        </div>
    </div>
</div>
