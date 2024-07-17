@extends('layout.master')

@push('plugin-styles')

@endpush

@push('chartjs')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
@endpush

@push('plugin-styles')
<style>
    .grid {
        display: grid;
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashgrid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 10px;
    }

    .span-9 {
        grid-column: span 9;
    }

    .span-8 {
        grid-column: span 8;
    }

    .span-4 {
        grid-column: span 4;
    }

    .span-3 {
        grid-column: span 3;
    }

    .span-2 {
        grid-column: span 2;
    }

    .span-6 {
        grid-column: span 6;
    }


    .row-span-2 {
        grid-row: span 2;
    }
</style>
@endpush

@section('content')
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
        <button class="btn btn-warning mt-1 ml-2" href="{{route('cooperative-admin.dashboard.export')}}" onclick="exportChart()">Export</button>
    </div>

</div>


<div class="dashgrid">

    <div class="card span-9 row-span-2">
        <div class="card-body">
            <div class="card-title">
                Collections Weight (KGs) By Gender
            </div>
            <div>
                <canvas id="CollectionsGenderBarChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="card span-3">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-domain" style="font-size: 30px;color: #a57150;"></i>
                </div>
                <div>
                    Cooperatives Count
                </div>

            </div>
            <h3 class="card-subtitle" id="cooperativeCount">{{$data["cooperative_count"]}}</h3>
        </div>
    </div>

    <div class="card span-3">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-factory" style="font-size: 30px;color: orange;"></i>
                </div>
                <div>
                    Millers Count
                </div>

            </div>
            <h3 class="card-subtitle" id="millerCount">{{$data["miller_count"]}}</h3>
        </div>
    </div>

    <div class="card span-3">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-account-group-outline" style="font-size: 30px;color: #36a2eb;"></i>
                </div>
                <div>
                    Farmer Count
                </div>

            </div>
            <h3 class="card-subtitle" id="farmerCount">{{$data["farmer_count"]}}</h3>
        </div>
    </div>

    <div class="card border span-3">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Collection Total Weight</div>
            </div>
            <h3 class="card-subtitle " id="collectionTotalWeight">{{$data["total_collection_weight"] ?? "0"}} KG</h3>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    // collections gender
    let maleCollectionsData = @json($data['male_collections']);
    console.log(maleCollectionsData);
    let maleCollectionValues = maleCollectionsData.map(c => c.y);

    let collectionsGenderLabels = maleCollectionsData.map(c => c.x)
    let collectionsGenderBarChartCanvas = document.getElementById("CollectionsGenderBarChart")

    let femaleCollectionsData = @json($data['female_collections']);
    let femaleCollectionValues = femaleCollectionsData.map(c => c.y);


    let collectionsGenderBarData = {
        labels: collectionsGenderLabels,
        datasets: [{
            label: 'Male',
            data: maleCollectionValues,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
        }, {
            label: 'Female',
            data: femaleCollectionValues,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
        }],
        // labels: ["Male", "Female", "Other"]
    };
    let collectionsGenderBarOptions = {
        animationEasing: "easeOutBounce",
        animateScale: true,
        responsive: true,
        maintainAspectRatio: false,
        showScale: true,
        legend: {
            display: true
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
    let collectionsGenderBarChart = new Chart(collectionsGenderBarChartCanvas, {
        type: "line",
        data: collectionsGenderBarData,
        options: collectionsGenderBarOptions
    });
</script>
@endpush