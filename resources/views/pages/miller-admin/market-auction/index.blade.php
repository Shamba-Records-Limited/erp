@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<br>
<br>
<br>
<div class="card">
    <div class="card-body">
        <div class="card-title">Market Auction</div>
        <div class="card-subtitle">Cooperatives</div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Num of Lots</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cooperatives as $key => $cooperative)
                    <tr>
                        <td>{{++$key }}</td>
                        <td>{{$cooperative->name}}</td>
                        <td>{{$cooperative->lots_count}}</td>
                        <td><a href="{{route('miller-admin.market-auction.coop-collections.show', $cooperative->id)}}" class="btn btn-primary">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush