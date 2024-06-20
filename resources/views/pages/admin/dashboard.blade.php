@extends('layout.master')

@push('plugin-styles')
<style>
    .dashgrid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 10px;
    }

    .span-8 {
        grid-column: span 8;
    }

    .span-4 {
        grid-column: span 4;
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
<div class="dashgrid">
    <div class="card span-8 row-span-2">
        <div class="card-body">
            <div class="card-title">
                Collections Weight (KGs)
            </div>
            <div>
                <canvas id="CollectionsBarChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="card border span-4">
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

    <div class="card span-2">
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

    <div class="card span-2">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 30px;color: #a57150;"></i>
                </div>
                <div>
                    Collection Count
                </div>
            </div>
            <h3 class="card-subtitle" id="collectionCount">{{$data["collection_count"]}}</h3>
        </div>
    </div>

    <div class="card span-4">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-office-building" style="font-size: 30px;color: #ff6384;"></i>
                </div>
                <div>
                    Cooperatives Count
                </div>
            </div>
            <h3 class="card-subtitle" id="collectionCount">{{$data["cooperatives_count"]}}</h3>
        </div>
    </div>



    <div class="card span-8 row-span-2">
        <div class="card-body">
            <div class="card-title">
                Collections Weight By Cooperatives (KGs)
            </div>
            <div>
                <canvas id="CooperativeCollectionsLineChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="card span-4">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-factory" style="font-size: 30px;color: #c14a09;"></i>
                </div>
                <div>
                    Millers Count
                </div>
            </div>
            <h3 class="card-subtitle" id="collectionCount">{{$data["collection_count"]}}</h3>
        </div>
    </div>

    <div class="card span-6" style="overflow-y: scroll; height:350px;">
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

    // coop collections chart
    let coopCollectionsArrData = @json($data['collections_by_cooperative']);
    let coopCollectionsLabels = [];
    let firstKey = Object.keys(coopCollectionsArrData)[0];
    if (coopCollectionsArrData.length != 0) {
        coopCollectionsLabels = coopCollectionsArrData[firstKey].map(c => c.x)
    }
    let coopCollectionsBarChartCanvas = document.getElementById("CooperativeCollectionsLineChart")

    let coopCollectionsBarData = {
        labels: collectionsLabels,
        datasets: [],
        // labels: ["Male", "Female", "Other"]
    };
    for (let key in coopCollectionsArrData) {
        let coopCollections = coopCollectionsArrData[key];
        let values = coopCollections.map(c => c.y)
        coopCollectionsBarData.datasets.push({
            label: key,
            data: values,
        });
    }


    let coopCollectionsBarOptions = {
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
    let coopCollectionsBarChart = new Chart(coopCollectionsBarChartCanvas, {
        type: "line",
        data: coopCollectionsBarData,
        options: coopCollectionsBarOptions
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