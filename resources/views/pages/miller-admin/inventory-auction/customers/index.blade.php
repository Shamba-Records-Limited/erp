@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Customers</div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="{{route('miller-admin.inventory-auction.add-customer')}}">Add Customer</a>
            <!-- button with dropdown actions: export all, export pending, export expired, export selected -->
            
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
<script>
    function updateStartAndEndDate() {
        let range = $('#dateRange').val();

        if (range == 'custom') {
            $('#startDate').prop('readonly', false);
            $('#endDate').prop('readonly', false);
        } else {
            $('#startDate').prop('readonly', true);
            $('#endDate').prop('readonly', true);
        }

        let today = new Date();
        if (range == 'today') {
            $('#startDate').val(new Date().toISOString().split('T')[0]);
            $('#endDate').val(new Date().toISOString().split('T')[0]);
        } else if (range == 'yesterday') {
            let yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            $('#startDate').val(yesterday.toISOString().split('T')[0]);
            $('#endDate').val(today.toISOString().split('T')[0]);
        } else if (range == 'last7days') {
            let last7days = new Date();
            last7days.setDate(last7days.getDate() - 7);
            $('#startDate').val(last7days.toISOString().split('T')[0]);
            $('#endDate').val(today.toISOString().split('T')[0]);
        } else if (range == 'last30days') {
            let last30days = new Date();
            last30days.setDate(last30days.getDate() - 30);
            $('#startDate').val(last30days.toISOString().split('T')[0]);
            $('#endDate').val(today.toISOString().split('T')[0]);
        } else if (range == 'last60days') {
            let last60days = new Date();
            last60days.setDate(last60days.getDate() - 60);
            $('#startDate').val(last60days.toISOString().split('T')[0]);
            $('#endDate').val(today.toISOString().split('T')[0]);
        }
    }

    $(document).ready(function() {
        updateStartAndEndDate();
    });

    $('#dateRange').change(function() {
        updateStartAndEndDate();
    });
</script>
@endpush