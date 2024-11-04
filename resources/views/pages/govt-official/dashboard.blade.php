@extends('layouts.app')

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
                <select name="cooperative_id" id="cooperative_id" class="form-control form-select" onchange="this.form.submit()">
                    <option value="all">All Cooperatives</option>
                    @foreach($selectableCooperatives as $cooperative)
                    <option value="{{$cooperative->id}}" @if($cooperative_id==$cooperative->id) selected @endif>{{$cooperative->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="date_range" placeholder="Select Date Range" class="form-control form-select" onchange="this.form.submit()" id="dateRange">
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
                Collections Weight (KGs)
            </div>
            <div>
                <canvas id="CollectionsLineChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
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

    <div class="card span-3">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-factory" style="font-size: 30px;color: orange;"></i>
                </div>
                <div>
                    Millers/Processors Count
                </div>

            </div>
            <h3 class="card-subtitle" id="millerCount">{{$data["miller_count"]}}</h3>
        </div>
    </div>



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
                    Cooperative/Aggregator Count
                </div>

            </div>
            <h3 class="card-subtitle" id="cooperativeCount">{{$data["cooperative_count"]}}</h3>
        </div>
    </div>

    <div class="span-4 card" style="overflow-y: scroll; height:350px;">
        <div class="card-body">
            <div class="card-title">Gender Distribution</div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="FarmersGenderDoughnutChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div class="p-4">
                    <div>Male</div>
                    <h3 id="maleCount">{{$data["gender"]->male}}</h3>
                </div>
                <div class="p-4">
                    <div>Female</div>
                    <h3 id="femaleCount">{{$data["gender"]->female}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card span-8" style="overflow-y: scroll; height:350px;">
        <div class="card-body">
            <div class="card-title">Grade Distribution KGs</div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="GradeDistributionBarChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
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
    // total collections
    let totalCollections = @json($data['collections']);
    let totalCollectionLabels = totalCollections.map(c => c.x)
    let totalCollectionValues = totalCollections.map(c => c.y);
    let totalCollectionsLineChartCanvas = document.getElementById("CollectionsLineChart")
    console.log(totalCollectionLabels);

    let totalCollectionsLineChartData = {
        labels: totalCollectionLabels,
        datasets: [{
            label: "All",
            data: totalCollectionValues,
            borderColor: '#4bc0c0',
            backgroundColor: '#4bc0c0aa',
        }],
        // labels: ["Male", "Female", "Other"]
    }
    let totalCollectionsLineOptions = {
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
    }
    let totalCollectionsLineChart = new Chart(totalCollectionsLineChartCanvas, {
        type: "line",
        data: totalCollectionsLineChartData,
        options: totalCollectionsLineOptions
    });

    // collections gender
    let maleCollectionsData = @json($data['male_collections']);
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

    // gender distribution chart
    let rawGenderData = @json($data['gender']);
    let genderData = Object.values(rawGenderData)
    let farmersGenderdoughnutChartCanvas = document.getElementById("FarmersGenderDoughnutChart")
    let genderPieData = {
        datasets: [{
            data: genderData,
            backgroundColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ]
        }],
        labels: ["Male", "Female", "Other"]
    };
    let genderPieOptions = {
        cutoutPercentage: 70,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: true,
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
    };
    let genderChart = new Chart(farmersGenderdoughnutChartCanvas, {
        type: "doughnut",
        data: genderPieData,
        options: genderPieOptions
    });

    // grade distribution chart
    let gradeDistributionData = @json($data['grade_distribution']);
    let gradeDistributionLabels = gradeDistributionData.map(c => c.name)
    let gradeDistributionValues = gradeDistributionData.map(c => c.quantity)
    let gradeDistributionBarChartCanvas = document.getElementById("GradeDistributionBarChart")
    let gradeDistributionBarData = {
        datasets: [{
            data: gradeDistributionValues,
            backgroundColor: [
                'rgba(65, 47, 38, 1)',
                'rgba(165, 113, 80, 1)',
                'rgba(184, 134, 11, 1)',
                'rgba(245, 245, 220, 1)',
            ]
        }],
        labels: gradeDistributionLabels
    };
    let gradeDistributionBarOptions = {
        animationEasing: "easeOutBounce",
        responsive: true,
        maintainAspectRatio: true,
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
    };
    let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
        type: "horizontalBar",
        data: gradeDistributionBarData,
        options: gradeDistributionBarOptions
    });


</script>
@endpush