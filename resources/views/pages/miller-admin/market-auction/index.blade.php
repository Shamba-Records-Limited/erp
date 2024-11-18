@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="row mt-6 mb-4 pl-6">
    <div class="col-md-4 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-header text-center bg-gradient-primary text-white">
                <h5 class="font-weight-bold mb-0 text-white">Total Number of Cooperatives/Aggregators</h5>
            </div>
            <div class="card-body text-center">
                <h2 id="cooperative-count" class="font-weight-bold text-primary display-4">{{ count($cooperatives) }}</h2>
                <!-- <p class="font-weight-bold text-muted">Cooperatives</p> -->
            </div>
        </div>
    </div>
</div>

<div class="card pt-6">
    <div class="card-body">
        <div class="card-title">Market Auction</div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cooperative/Aggregator</th>
                        <th>Available Lots</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cooperatives as $key => $cooperative)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $cooperative->name }}</td>
                        <td>{{ $cooperative->lots_count }}</td>
                        <td>
                            <a href="{{ route('miller-admin.market-auction.coop-collections.show', $cooperative->id) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right font-weight-bold">Total:</td>
                        <td class="font-weight-bold">{{ $totalLots }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
