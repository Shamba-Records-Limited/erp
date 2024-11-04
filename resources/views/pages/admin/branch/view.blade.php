@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Branch Statistics</h1>
    <h2>Branch Name: {{ $branch->name }}</h2>
    <p>Total Farmers: {{ $totalFarmers }}</p>

    <h3>Farmers under this Branch:</h3>
    <ul>
        @foreach($farmers as $farmer)
        <li>{{ $farmer->username }}</li> <!-- Display farmer's username -->
        @endforeach
    </ul>

    <h3>Collections for this Branch:</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Collection Number</th>
                <th>Farmer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Collection Time</th>
                <th>Date Collected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            <tr>
                <td>{{ $collection->collection_number }}</td>
                <td>{{ $collection->username }}</td>
                <td>{{ $collection->product_name }}</td> <!-- Display product name -->
                <td>{{ $collection->quantity }}</td>
                <td>{{ $collectionTimeLabels[$collection->collection_time] ?? 'N/A' }}</td>
                <!-- Display collection time -->
                <td>{{ $collection->date_collected }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Other relevant statistics can go here...</p>
    <!-- Add more statistics as needed -->
</div>
@endsection