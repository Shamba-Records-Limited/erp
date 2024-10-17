<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .d-flex {
        display: flex;
    }

    .d-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        border-radius: 10px;
    }

    .card-title {
        font-size: 1.5em;
    }

    .card-value {
        font-weight: bold;
        font-size: 2em;
    }

    .range-span {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin: 10px;
    }

    .border {
        border: 1px solid #ccc;
        border-radius: 10px;
    }

    .p-10 {
        padding: 10px;
    }

    .m-10 {
        margin: 10px;
    }

    .text-center {
        text-align: center;
    }
    </style>
</head>

<body>

    <table>
        <tbody>
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Farmer Count</div>
                        <div class="card-value">{{$farmerCount}}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Collection Count</div>
                        <div class="card-value">{{$collectionCount}}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">
                            Collection Total Weight
                        </div>
                        <div class="card-value">{{$collectionTotalWeight}}</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>


    <div class="border p-10">
        <div>
            <span class="range-span">Range:{{$dateRange}}</span>
            <span class="range-span">From: {{$fromDate}}</span>
            <span class="range-span">To: {{$toDate}}</span>
        </div>
        <div class="card-title">Collections</div>
        <img src="{{$collectionsBarChartImg}}" style="width: 100%;">
    </div>
    <table>
        <tbody>
            <tr>
                <td style="width: 50%; padding: 10px;" class="text-center">
                    <div class="border p-10">
                        <div class="card-title">Grade Distribution</div>
                        <img src="{{$gradeDistributionChartImg}}" style="width: 300px;">
                    </div>
                </td>
                <td style="width: 50%; padding: 10px;" class="text-center">
                    <div class="border p-10">
                        <div class="card-title">Gender Distribution</div>
                        <img src="{{$genderChartImg}}" style="width: 300px;">
                    </div>
                </td>
            </tr>
    </table>
</body>

</html>