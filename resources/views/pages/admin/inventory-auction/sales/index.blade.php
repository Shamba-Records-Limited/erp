@extends('layouts.app')

@push('plugin-styles')
<!-- Add custom styles here if needed -->
@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">

             <!--<div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                   <div>
                        <a class="btn btn-primary btn-sm" href="{{route('admin.inventory-auction.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span> Download Excel Sheet</a>
                        <a class="btn btn-primary btn-sm" href="{{route('admin.inventory-auction.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span> Download PDF</a>
                    </div>
                </div> 
            </div>-->
            
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
            <div class="card-body">
                <h4 class="card-title">Accumulative Sales</h4>
                <div class="table-responsive">
                    <table id="warehouseTable" class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>Entity Type</th>
                                <th>Sales Count</th>
                                <th>Total Amount</th>
                                <th>Tota Discount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accumulatedSales as $key => $sale)
                            <tr>
                                <td>{{$sale->entity_type}}</td>
                                <td>{{$sale->total_sales_count}}</td>
                                <td>{{$sale->total_paid_amount}}</td>
                                <td>{{$sale->total_discount}}</td>
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

<!-- Sales Table -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
            <div class="card-body">
                <h4 class="card-title">Sales By Miller/Cooperative</h4>
                <div class="table-responsive">
                    <table id="warehouseTable" class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Entity Type</th>
                                <th>Cooperative Name</th>
                                <th>Miller Name</th>
                                <th>Sales Count</th>
                                <th>Total Amount</th>
                                <th>Tota Discount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $key => $sale)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$sale->entity_type}}</td>
                                <td>{{$sale->cooperative_name}}</td>
                                <td>{{$sale->miller_name}}</td>
                                <td>{{$sale->total_sales}}</td>
                                <td>{{$sale->total_paid_amount}}</td>
                                <td>{{$sale->total_discount}}</td>
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
