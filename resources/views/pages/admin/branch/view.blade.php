@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Branch Statistics</h1>
    <h2>Branch Name: {{ $branch->name }}</h2>
    <p>Total Farmers: {{ $totalFarmers }}</p>
    <p>Other relevant statistics can go here...</p>
    <!-- Add more statistics as needed -->
</div>
@endsection