@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <div class="welcome-container pl-8" style="text-align: center; margin-top: 50px;">
        <h2 style="font-size: 2.5em; color: #333; margin-bottom: 20px;">Welcome to Shamba Coffee ERP</h2>
        <img src="{{ asset('argon/img/brand/coffee.png') }}" alt="Coffee Image" style="max-width: 200px; height: auto; margin: 0 auto;" />
    </div>
    
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
