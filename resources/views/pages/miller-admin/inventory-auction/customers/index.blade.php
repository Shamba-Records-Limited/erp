@extends('layout.master')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Customers</div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="{{route('miller-admin.inventory-auction.add-customer')}}">Add Customer</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{$customer->title}}</td>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->gender}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->phone_number}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="{{route('miller-admin.inventory-auction.view-customer', $customer->id )}}">
                                        <i class="fa fa-edit"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </td>
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