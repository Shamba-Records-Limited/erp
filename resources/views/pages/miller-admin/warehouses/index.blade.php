@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="container my-5">
                <div class="row">
                    <!-- Total Number of Warehouses Card -->
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header text-center bg-gradient-primary text-white">
                                <h5 class="font-weight-bold mb-0 text-white">Total Number of Warehouses</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 id="warehouse-count" class="font-weight-bold text-primary display-4">0</h2>
                                <p class="font-weight-bold text-muted">Warehouses</p>
                            </div>
                        </div>
                    </div>

                    <!-- Number of Unique Locations Card -->
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header text-center bg-gradient-success text-white">
                                <h5 class="font-weight-bold mb-0">Number of Locations</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 id="location-count" class="font-weight-bold text-success display-4">0</h2>
                                <p class="font-weight-bold text-muted">Unique Locations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div>
                    <button type="button" class="btn btn-primary btn-fw btn-sm" data-toggle="collapse" data-target="#addCollectionForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addCollectionForm"><span class="mdi mdi-plus"></span>Add Warehouse
                    </button>
                    <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.warehouses.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span>Download Excel Sheet
                    </a>
                    <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.warehouses.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span>Download PDF
                    </a>
                </div>

                <div class="collapse @if ($errors->count() > 0) show @endif" id="addCollectionForm">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Add Warehouse</h4>
                        </div>
                    </div>

                    <form action="{{ route('miller-admin.warehouses.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Warehouse Details</h6>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="ABC" value="{{ old('name')}}" required>
                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="text" name="location" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" value="{{ old('location')}}" id="location" placeholder="Nairobi" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <h6 class="h6 mt-2">Warehouse Admin</h6>
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name" class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" id="f_name" placeholder="John" value="{{ old('f_name')}}" required>
                                @if ($errors->has('f_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="o_name">Other Names</label>
                                <input type="text" name="o_names" value="{{ old('o_names')}}" class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}" id="o_name" placeholder="Doe" required>
                                @if ($errors->has('o_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="u_name">User Name</label>
                                <input type="text" name="u_name" class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}" id="u_name" placeholder="j_doe" value="{{ old('u_name')}}" required>
                                @if ($errors->has('u_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('u_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="user_email">Email</label>
                                <input type="email" name="user_email" class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}" id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}" required>
                                @if ($errors->has('user_email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warehouse Table -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Warehouses</h4>
                <div class="table-responsive">
                    <table id="warehouseTable" class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouses as $key => $warehouse)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$warehouse->name}}</td>
                                <td>{{$warehouse->location}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#warehouseTable').DataTable();
        
        // Set warehouse count in the card based on the number of rows in the DataTable
        $('#warehouse-count').text(table.rows().count());
        
        // Get unique locations and set location count in the card
        var uniqueLocations = [];
        table.column(2).data().each(function(value) {
            if (uniqueLocations.indexOf(value) === -1) {
                uniqueLocations.push(value);
            }
        });
        $('#location-count').text(uniqueLocations.length);
    });
</script>
@endpush

