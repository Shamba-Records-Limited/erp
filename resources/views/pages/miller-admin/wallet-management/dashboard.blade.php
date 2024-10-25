@extends('layouts.app')

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
<div class="d-flex justify-content-between w-100">
    <div>Dashboard</div>
    <div class="d-flex align-items-start">
        <form class="d-flex">
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
    <div class="card span-6">
        <div class="card-body">
            <div class="card-title">
                Income vs Expenses
            </div>
            <div>
                <canvas id="IncomeVsExpensesChart" class="mb-4 mb-md-0" height="250"></canvas>
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
    // income vs expenses chart
    let expenseData = @json($data['expenses']);
    let incomeVsExpenseLabels = expenseData.map(c => c.x)
    let expenseValues = expenseData.map(c => c.y)
    let incomeVsExpensesChart = document.getElementById("IncomeVsExpensesChart")

    let incomeData = @json($data['income']);
    let incomeValues = incomeData.map(c => c.y)

    let incomeVsExpenseChartData = {
        labels: incomeVsExpenseLabels,
        datasets: [{
            label: 'Expenses',
            data: expenseValues,
            borderColor: 'rgba(192, 75, 192, 1)',
            backgroundColor: 'rgba(192, 75, 192, 0.2)',
        }, {
            label: 'Income',
            data: incomeValues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
        }
    ],
        // labels: ["Male", "Female", "Other"]
    };
    let incomeVsExpenseChartOptions = {
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
    let incomeVsExpenseChart = new Chart(incomeVsExpensesChart, {
        type: "line",
        data: incomeVsExpenseChartData,
        options: incomeVsExpenseChartOptions
    });

    
</script>
@endpush