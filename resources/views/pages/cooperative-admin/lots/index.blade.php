@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Lots</div>
        <div class="table-responsive">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Quantity</th>
                        <th>Number of Collections</th>
                        <th>Ungraded</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $key => $lot)
                    <tr>
                        <td>{{++$key }}</td>
                        <td><a href="{{route('cooperative-admin.lots.detail', $lot->lot_number)}}">{{$lot->lot_number}}</a></td>
                        <td>{{$lot->quantity}} KG</td>
                        <td>{{$lot->collections_count}}</td>
                        <td>{{$lot->quantity - ($lot->graded ?? 0)}} KG</td>
                        <td></td>
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