@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Add Existing Farmer</div>
        <form action="{{route('cooperative-admin.farmers.view_add_existing')}}" method="GET">
            <div class="form-row align-items-start">
                <div class="form-group">
                    <input class="form-control" type="text" name="search" id="search" placeholder="Search" value="{{$search}}">
                    <div class="text-sm">You can search by member number or id number of farmer</div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg ml-2">Search</button>
            </div>
        </form>
        <div>
            @foreach($farmers as $key => $farmer)
            <div class="row align-items-start p-2 rounded border justify-content-between">
                <div>
                    <div class="font-weight-bold">{{$farmer->username}}</div>
                    <div>Id No: {{$farmer->id_no}}</div>
                    <div>Member No: {{$farmer->member_no}}</div>
                </div>
                <form action="{{route('cooperative-admin.farmers.add_existing')}}" method="post">
                    @csrf
                    <input type="hidden" name="farmer_id" value="{{$farmer->id}}">
                    <button class="btn btn-primary">Add this farmer</button>
                </form>
            </div>

            @endforeach
        </div>
        @if ($searchDone == true && count($farmers) == 0)
        <div>
            No Record found <a href="{{route('cooperative-admin.farmers.view_add_new')}}">Add New?</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush