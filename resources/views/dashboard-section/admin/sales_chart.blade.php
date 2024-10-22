<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex justify-content-between align-items-center pb-4">
                    <h4 class="card-title mb-0">
                        @if(request()->date)
                            {{ 'Sales Trend Between '.request()->date }}
                        @else
                            Last 12 Month sales Trend
                        @endif

                    </h4>
                    <div id="line-traffic-legend"></div>
                </div>
                <canvas id="lineChart" style="height:250px"></canvas>
            </div>
        </div>
    </div>
</div>
