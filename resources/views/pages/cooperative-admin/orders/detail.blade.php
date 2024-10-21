@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('topItem')
@if($action == 'add_delivery')
<!-- add delivery -->
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
                        <h4 class="text-center">Add Delivery</h4>
                    </div>
                </div>
                <div class="card-body">

                    <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft delivery. Publish it to apply changes</div>
                    <div>
                        <div class="font-weight-bold">Delivery Items</div>

                        <button id="addDeliveryButton" class="btn border p-3 w-100" data-toggle="collapse" data-target="#addDeliveryItemForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addDeliveryItemForm">Add Delivery Item</button>
                        <form id="addDeliveryItemForm" class="border rounded p-2 collapse @if ($errors->count() > 0) show @endif " action="{{route('cooperative-admin.order-delivery.add-item', $order->id)}}">
                            <div class="row">
                                <div class="form-group col-lg-4 col-md-4 col-12">
                                    <label for="bank_id">Select Order Item</label>
                                    <select name="order_item_id" id="order_item_id" class="form-control select2bs4 {{ $errors->has('order_item_id') ? ' is-invalid' : '' }}" data-orderitems='@json($orderItems)'>
                                        <option value="">-- Select Order Item --</option>
                                        @foreach($orderItems as $item)
                                        <option value="{{$item->id}}" {{ old('order_item_id') == $item->id ? 'selected' : '' }}> {{ $item->lot_number }}</option>
                                        @endforeach

                                        @if ($errors->has('order_item_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('order_item_id')  }}</strong>
                                        </span>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-12">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="Enter quantity" value="{{ old('quantity') }}">

                                    @if ($errors->has('quantity'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('quantity')  }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary">Save Delivery Item</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive p-2">
                            <table class="table table-hover dt clickable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Lot</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($draft_delivery_items as $key => $delivery_item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$delivery_item->lot_number}}</td>
                                        <td>{{$delivery_item->quantity}}</td>
                                        <td>
                                            <form action="{{route('cooperative-admin.order-delivery.delete-item', $delivery_item->id)}}" method="POST">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-danger"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex">
                            @if($draft_delivery)
                            <a href="{{route('cooperative-admin.order-delivery.publish-delivery-draft', $draft_delivery->id)}}" class="btn btn-outline-primary" onclick="return confirm('Once published this delivery cannot be changed.')">Publish</a>
                            <form action="{{route('cooperative-admin.order-delivery.discard-delivery-draft', $draft_delivery->id)}}" method="POST">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button class="btn btn-outline-secondary" onclick="return confirm('Once discarded the changes will be lost forever.')">Discard Draft</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /add delivery -->
@endif
@endsection

@section('content')


<div class="card">
    <div class="card-body">
        <div class="card-title">Order Details</div>

        <div class="row mb-2">
            <div class="col-12 col-md-6 col-lg-4 border rounded p-2">
                Batch Number: <span class="font-weight-bold">{{$order->batch_number}}</span>
            </div>
        </div>

        <div class="border border-success rounded  shadow-sm p-2 mb-2">
            <div class="d-flex justify-content-between">
                <div class="text-success">Total: <span class="font-weight-bold">{{$totalInOrder}}KG</span> </div>
                <div>
                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#aggregateDistribution" aria-controls="aggregateDistribution">
                        <div class="mdi mdi-chevron-down"></div>
                    </button>
                </div>
            </div>
            <div class="collapse p-2" id="aggregateDistribution">
                <h4>Grading Distribution</h4>
                @foreach ($aggregateGradeDistribution as $distribution)
                <div>
                    {{$distribution->total}}KG of {{ $distribution->grade }}
                </div>
                @endforeach

            </div>
        </div>

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
                        <th>Lot</th>
                        <th>Quantity</th>
                        <th>Not Delivered</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->lot_number}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->undelivered}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'deliveries')
        <div class="d-flex justify-content-end p-2">
            <a href="?tab=deliveries&action=add_delivery" class="btn btn-primary">Add Delivery</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Delivery No.</th>
                        <th>No of Items</th>
                        <th>Status</th>
                        <th>Delivered At</th>
                        <th>Approved At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDeliveries as $key => $delivery)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$delivery->delivery_number}}</td>
                        <td>{{$delivery->total_items}}</td>
                        <td>
                            @if($delivery->published_at)
                            <div class="text-success">Published</div>
                            @else
                            <div class="text-warning">Draft</div>
                            @endif
                        </td>
                        <td>{{$delivery->published_at}}</td>
                        <td>
                            @if ($delivery->approved_at)
                            {{$delivery->approved_at}}
                            @else
                            <div class="text-warning">Not Approved</div>
                            @endif
                        </td>
                        <td class="d-flex justify-items-end">
                            @if(is_null($delivery->published_at))
                            <a title="publish" href="?tab=deliveries&action=add_delivery" class="btn btn-outline-primary mr-1"><i class="mdi mdi-check-all"></i></a>
                            <form action="{{route('cooperative-admin.order-delivery.discard-delivery-draft', $delivery->id)}}" method="POST">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button title="discard" class="btn btn-outline-danger" onclick="return confirm('This will permanently delete changes')"><i class="mdi mdi-delete-forever-outline"></i></button>
                            </form>
                            @endif
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
<script>
    // const isAddingDelivery = false;

    // function addDelivery() {
    //     isAddingDelivery = true;
    // }

    $("#addDeliveryItemForm [name='order_item_id']").change(function(e) {
        let rawOrderItems = $(this).attr("data-orderitems")
        let orderItemId = $(this).val()
        let orderItems = JSON.parse(rawOrderItems);

        let selectedOrderItem = orderItems.filter((x) => {
            return x.id == orderItemId;
        })[0];

        $("#addDeliveryItemForm [name='quantity']").val(selectedOrderItem.quantity);
        $("#addDeliveryItemForm [name='unit_id']").val(selectedOrderItem.unit_id).trigger("change");
    });
</script>
@endpush