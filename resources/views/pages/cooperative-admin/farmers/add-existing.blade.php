@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="card-title text-center mb-4">Add Existing Farmer</h2>
        <form action="{{route('cooperative-admin.farmers.view_add_existing')}}" method="GET" class="text-center mb-4">
            <div class="form-row align-items-start justify-content-center">
                <div class="form-group">
                    <input class="form-control" type="text" name="search" id="search" placeholder="Search"
                        value="{{$search}}">
                    <small class="form-text text-muted">Search by member number or ID number of farmer</small>
                </div>
                <button type="submit" class="btn btn-primary btn-lg ml-2">Search</button>
            </div>
        </form>
        <div class="farmer-list">
            @foreach($farmers as $key => $farmer)
            <div class="row farmer-card align-items-center p-3 rounded shadow-sm mb-4 bg-white">
                <div class="col-md-8">
                    <h5 class="font-weight-bold text-primary mb-2">Name: {{$farmer->username}}</h5>
                    <p class="mb-1"><strong>Id No:</strong> {{$farmer->id_no}}</p>
                    <p class="mb-1"><strong>Member No:</strong> {{$farmer->member_no}}</p>
                </div>
                <div class="col-md-4 text-md-right text-center">
                    <form action="{{ route('cooperative-admin.farmers.add_existing') }}" method="post">
                        @csrf
                        <input type="hidden" name="farmer_id" value="{{ $farmer->id }}">
                        <button class="btn btn-success px-4 py-2">Add Farmer</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @if ($searchDone == true && count($farmers) == 0)
        <div class="text-center mt-4">
            <p>No Record found. <a href="{{route('cooperative-admin.farmers.view_add_new')}}">Add New?</a></p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush