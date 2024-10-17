<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex justify-content-between align-items-center pb-4">
                    <h4 class="card-title mb-0">
                        @if(request()->date)
                            {{ 'Product Collections Between  '.request()->date }}
                        @else
                            Products Collection in 1 Week
                        @endif

                    </h4>
                    <div id="stacked-bar-traffic-legend"></div>
                </div>
                <canvas id="stackedbarChart" style="height:250px"></canvas>
            </div>
        </div>
    </div>
</div>
