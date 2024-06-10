@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div>
    <h3>Mini Dashboard</h3>
</div>
<div class="d-flex justify-content-between w-100">
    <div>Dashboard</div>
    <div class="d-flex align-items-start">
        <form class="d-flex">
            <div class="form-group">
                <select name="date_range" placeholder="Select Date Range" class="form-control select2bs4" onchange="this.form.submit()" id="dateRange">
                    <option value="week" @if($date_range=="week" ) selected @endif)>This Week</option>
                    <option value="month" @if($date_range=="month" ) selected @endif>This Month</option>
                    <option value="year" @if($date_range=="year" ) selected @endif>This Year</option>
                    <option value="custom" @if($date_range=="custom" ) selected @endif>Custom</option>
                </select>
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="from_date" value="{{$from_date}}" onchange="this.form.submit()" id="fromDate" />
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="to_date" value="{{$to_date}}" onchange="this.form.submit()" id="toDate" />
            </div>
        </form>
    </div>

</div>
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Collections Weight (KGs)
                </div>
                <div>
                    <canvas id="CollectionsBarChart" class="mb-4 mb-md-0" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    // collections chart
    let collectionsData = @json($data['collections']);
    let collectionsLabels = collectionsData.map(c => c.x)
    let collectionsValues = collectionsData.map(c => c.y)
    console.log(collectionsData);
    let collectionsBarChartCanvas = document.getElementById("CollectionsBarChart")
    let collectionsBarData = {
        labels: collectionsLabels,
        datasets: [{
            data: collectionsValues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
        }],
        // labels: ["Male", "Female", "Other"]
    };
    let collectionsBarOptions = {
        animationEasing: "easeOutBounce",
        animateScale: true,
        responsive: true,
        maintainAspectRatio: false,
        showScale: true,
        legend: {
            display: false
        },
        layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        }
        // y axis is KGs
    };
    let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
        type: "line",
        data: collectionsBarData,
        options: collectionsBarOptions
    });

    
</script>
@endpush