@extends('layout.master')

@push('plugin-styles')

@endpush

@push('chartjs')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
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

<div class="row grid-margin">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Farmer Count
                </div>
                <div class="card-subtitle" id="farmerCount">{{$data["farmer_count"]}}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Collection Count
                </div>
                <div class="card-subtitle" id="collectionCount">{{$data["collection_count"]}}</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Collection Total Weight
                </div>
                <div class="card-subtitle" id="collectionTotalWeight">{{$data["total_collection_weight"]}} KG</div>
            </div>
        </div>
    </div>
</div>

@php
$total_gender_distribution = $data["gender"]->female + $data["gender"]->male + $data["gender"]->other
@endphp
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
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Collections Weight (KGs) By Gender
                </div>
                <div>
                    <canvas id="CollectionsGenderBarChart" class="mb-4 mb-md-0" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:350px;">
            <div class="card-body">
                <div class="card-title">Collection By Wet Mills (KGs)</div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center">
                        <canvas id="WetMillCollectionsBarChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:350px;">
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


</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:350px;">
            <div class="card-body">
                <div class="card-title">Gender Distribution</div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center">
                        <canvas id="FarmersGenderDoughnutChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                    </div>
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
    let collectionsBarChartCanvas = document.getElementById("CollectionsBarChart")

    let collectionsBarData = {
        labels: collectionsLabels,
        datasets: [{
            label: 'All',
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
    let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
        type: "line",
        data: collectionsBarData,
        options: collectionsBarOptions
    });

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

    // wet mill collections chart
    let wetMillCollectionsData = @json($data['collections_by_wet_mills']);
    let wetMillCollectionsLabels = wetMillCollectionsData.map(c => c.name)
    let wetMillCollectionsValues = wetMillCollectionsData.map(c => c.quantity)
    let wetMillCollectionsBarChartCanvas = document.getElementById("WetMillCollectionsBarChart")
    let wetMillCollectionsBarData = {
        labels: wetMillCollectionsLabels,
        datasets: [{
            data: wetMillCollectionsValues,
            backgroundColor: []
        }]
    };
    let wetMillCollectionsBarOptions = {
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
    }

    let wetMillCollectionsBarChart = new Chart(wetMillCollectionsBarChartCanvas, {
        type: "bar",
        data: wetMillCollectionsBarData,
        options: wetMillCollectionsBarOptions
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

    // gender distribution chart
    let rawGenderData = @json($data['gender']);
    let genderData = Object.values(rawGenderData)
    let farmersGenderdoughnutChartCanvas = document.getElementById("FarmersGenderDoughnutChart")
    let genderPieData = {
        datasets: [{
            data: genderData,
            backgroundColor: [
                successColor,
                primaryColor,
                dangerColor
            ],
            borderColor: [
                successColor,
                primaryColor,
                dangerColor
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



    function exportChart() {
        let dateRange = document.getElementById("dateRange").value;
        let fromDate = document.getElementById("fromDate").value;
        let toDate = document.getElementById("toDate").value;

        let farmerCount = document.getElementById("farmerCount").innerHTML;
        let collectionCount = document.getElementById("collectionCount").innerHTML;
        let collectionTotalWeight = document.getElementById("collectionTotalWeight").innerHTML;


        var genderChartImg = genderChart.toBase64Image();
        var collectionsBarChartImg = collectionsBarChart.toBase64Image();
        var gradeDistributionChartImg = gradeDistributionChart.toBase64Image();

        let data = {
            dateRange,
            fromDate,
            toDate,
            farmerCount,
            collectionCount,
            collectionTotalWeight,
            genderChartImg,
            collectionsBarChartImg,
            gradeDistributionChartImg
        }

        fetch('/cooperative-admin/dashboard/export', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.blob())
            .then(blob => {
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "chart.pdf";
                link.click();
            });
    }
</script>
@endpush