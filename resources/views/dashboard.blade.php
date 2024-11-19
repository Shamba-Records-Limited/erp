@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
<style>
      .welcome-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset('argon/img/brand/shamba-traceability.avif') }}") center center/cover no-repeat;
            filter: blur(8px);
            transform: translateZ(0) scale(1.05);
            transition: transform 0.5s ease;
            z-index: 0;
        }
</style>
    <div class="welcome-container">
        <!-- Dark overlay for better text readability -->
        <div class="welcome-overlay"></div>

        <!-- Welcome content with title, subtitle, image, and CTA button -->
        <div class="welcome-content">
            <h2 class="welcome-title">Welcome to Shamba Traceability</h2>
            <p class="welcome-subtitle">
                Enhancing agricultural transparency and traceability with a powerful ERP solution for cooperatives and producers.
            </p>
            <img src="{{ asset('argon/img/brand/coffee.png') }}" alt="Agriculture Image" class="welcome-image" />
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush