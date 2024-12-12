@extends('layouts.app')

@push('plugin-styles')
<!-- Add custom styles here if needed -->
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
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
                <div class="d-flex justify-content-end mb-3">
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{route('admin.warehouses.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span> Download Excel Sheet</a>
                        <a class="btn btn-primary btn-sm" href="{{route('admin.warehouses.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span> Download PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warehouse Table -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
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
