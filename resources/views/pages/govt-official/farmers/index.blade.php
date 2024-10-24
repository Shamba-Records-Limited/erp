@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmers</div>
        <div class="d-flex justify-content-end">
            <select class="form-select">
                <option>All</option>
                <option>Coop x</option>
            </select>
        </div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cooperative</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>County</th>
                        <th>Sub County</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmers as $key => $farmer)
                    <tr>
                        <td>{{++$key }}</td>
                        <td>{{$farmer->coop_name}}</td>
                        <td>
                            <a href="{{route('govt-official.farmers.details', $farmer->id)}}">{{$farmer->first_name}} {{$farmer->other_names}}</a>
                        </td>
                        <td>{{$farmer->gender}}</td>
                        <td>{{$farmer->county_name}}</td>
                        <td>{{$farmer->sub_county_name}}</td>
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