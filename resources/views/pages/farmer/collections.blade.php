@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" id="title_header">Collections</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt clickable" id="jsonDataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Collection No</th>
                                <th>Lot No</th>
                                <th>Cooperative</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Collection Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $key => $collection)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$collection->collection_number}}</td>
                                <td>{{$collection->lot_number}}</td>
                                <td>{{$collection->coop_name}}</td>
                                <td>{{$collection->product_name}}</td>
                                <td>{{$collection->quantity}} {{$collection->unit}}</td>
                                <td>{{ $collection_time_options[$collection->collection_time]}}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-primary dropdown-item"  onclick="printReceipt('{{$collection->id}}')" >
                                                <i class="fa fa-pdf"></i> Generate Receipt
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
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function set_unit_id() {
        let product_id = $("#product_id").val();
        if (product_id == "") {
            return;
        }

        let url = `/common/product/${product_id}/unit`
        console.log(url);
        axios.get(url).then(function(response) {
            unit = response.data;
            $("#unit").val(unit);
            $("#unit").trigger("change");
        }).catch(function(error) {
            console.log(error);
        });
    }

    $(document).ready(function() {
        set_unit_id();
    });

    var jsonData = @json($collections);
    var id_type = 'collection_receipt';
    var titleText = '  Collection Receipt';

</script>
@endpush
