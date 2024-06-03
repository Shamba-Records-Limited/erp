@extends('layout.master')

@push('plugin-styles')

@endpush

@section('topItem')
<!-- view delivery -->
@if(false)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?tab=deliveries">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">View Delivery</h4>
                    </div>
                </div>
                <div class="card-body">

                    <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft delivery. Publish it to apply changes</div>
                    <div>
                        <div class="font-weight-bold">Delivery Items</div>

                        <div class="table-responsive p-2">
                            <table class="table table-hover dt clickable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deliveryItems as $key => $delivery_item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$delivery_item->product_name}} - {{$delivery_item->product_category}}</td>
                                        <td>{{$delivery_item->quantity}} {{$delivery_item->unit_abbr}}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- /view delivery -->
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div class="card-title">Order Details</div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'items'?'active':'' }}" href="?tab=items">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'deliveries'?'active':'' }}" href="?tab=deliveries">Deliveries</a>
            </li>
        </ul>

        @if ($tab == 'items' || empty($tab))
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->product_name}} - {{$item->product_category}}</td>
                        <td>{{$item->quantity}} {{$item->unit_abbr}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'deliveries')
        
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No of Items</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDeliveries as $key => $delivery)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$delivery->total_items}}</td>
                        <td>
                            @if($delivery->approved_at)
                            <div class="text-success">Approved</div>
                            @else
                            <div class="text-warning">Pending</div>
                            @endif

                        </td>
                        <td class="d-flex justify-items-end">
                            <a title="view" href="?tab=deliveries&delivery_id_to_view={{$delivery->id}}" class="btn btn-outline-primary mr-1"><i class="mdi mdi-eye"></i></a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush