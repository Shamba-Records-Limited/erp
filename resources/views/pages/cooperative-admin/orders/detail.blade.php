@extends('layouts.app')

@push('plugin-styles')
@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

@endpush


@section('topItem')
@if($action == 'add_delivery')
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4>Add Delivery</h4>
                <a class="close-btn" href="?tab=deliveries"><i class="mdi mdi-close"></i></a>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">You are working on a draft delivery. Publish it to apply changes.</div>
                <div class="font-weight-bold">Delivery Items</div>
                <button id="addDeliveryButton" class="btn btn-primary" data-toggle="collapse" data-target="#addDeliveryItemForm">Add Delivery Item</button>
                <form id="addDeliveryItemForm" class="collapse @if ($errors->count() > 0) show @endif" action="{{route('cooperative-admin.order-delivery.add-item', $order->id)}}">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="order_item_id">Select Order Item</label>
                            <select name="order_item_id" id="order_item_id" class="form-control {{ $errors->has('order_item_id') ? ' is-invalid' : '' }}" data-orderitems='@json($orderItems)'>
                                <option value="">-- Select Order Item --</option>
                                @foreach($orderItems as $item)
                                <option value="{{$item->id}}" {{ old('order_item_id') == $item->id ? 'selected' : '' }}> {{ $item->lot_number }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('order_item_id'))
                            <span class="text-danger">{{ $errors->first('order_item_id') }}</span>
                            @endif
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
                    <button class="btn btn-success">Save Delivery Item</button>
                </form>
                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lot</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($draft_delivery_items as $key => $delivery_item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$delivery_item->lot_number}}</td>
                                <td>{{$delivery_item->quantity}}</td>
                               <td>
                                    @if($draft_delivery)
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('cooperative-admin.order-delivery.publish-delivery-draft', $draft_delivery->id) }}" class="text-success dropdown-item">
                                                <i class="fa fa-check-circle"></i> Publish
                                            </a>
                                            <form action="{{ route('cooperative-admin.order-delivery.delete-item', $delivery_item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')" class="text-danger dropdown-item btn btn-link">
                                                    <i class="fa fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="action-buttons">
                    @if($draft_delivery)
                    <a href="{{route('cooperative-admin.order-delivery.publish-delivery-draft', $draft_delivery->id)}}" class="btn btn-outline-primary">Publish</a>
                    <form action="{{route('cooperative-admin.order-delivery.discard-delivery-draft', $draft_delivery->id)}}" method="POST" class="d-inline">
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
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Order Details</h4>
        <div class="container">
    <div class="row justify-content-center mb-6">
        <!-- Left Column: Order Details -->
        <div class="col-md-5">
            <div class="card order-details">
                <div class="card-header text-center">
                    <h5>Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <strong>Batch Number:</strong> <span class="batch-number">{{$order->batch_number}}</span>
                    </div>
                    <div class="aggregate-info mt-3">
                        <h6 class="text-muted">Grading Distribution</h6>
                        <ul class="list-group">
                            @foreach ($aggregateGradeDistribution as $distribution)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $distribution->grade }}
                                    <span class="badge " style="background-color:#FFD89D">{{ $distribution->total }} KG</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Pie Chart -->
        <div class="col-md-5">
            <div class="card delivery-status">
                <div class="card-header text-center">
                    <h5>Delivery Status</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="deliveryStatusChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
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
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot</th>
                        <th>Quantity</th>
                        <th>Not Delivered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->lot_number}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->undelivered}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'deliveries')
        <div class="d-flex justify-content-end mb-2">
            <a href="?tab=deliveries&action=add_delivery" class="btn btn-primary">Add Delivery</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Delivery No.</th>
                        <th>No of Items</th>
                        <th>Status</th>
                        <th>Delivered At</th>
                        <th>Approved At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDeliveries as $key => $delivery)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$delivery->delivery_number}}</td>
                        <td>{{$delivery->total_items}}</td>
                        <td class="{{ $delivery->published_at ? 'text-success' : 'text-warning' }}">
                            {{ $delivery->published_at ? 'Published' : 'Draft' }}
                        </td>
                        <td>{{$delivery->published_at}}</td>
                        <td>
                            @if ($delivery->approved_at)
                            {{$delivery->approved_at}}
                            @else
                            <div class="text-warning">Not Approved</div>
                            @endif
                        </td>
                        <td>
                            @if(is_null($delivery->published_at))
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-success dropdown-item" href="?tab=deliveries&action=add_delivery">
                                        <i class="mdi mdi-check-all"></i> Publish
                                    </a>
                                    <form action="{{ route('cooperative-admin.order-delivery.discard-delivery-draft', $delivery->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <a onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger dropdown-item">
                                            <i class="mdi mdi-delete-forever-outline"></i> Discard
                                        </a>
                                    </form>
                                </div>
                            </div>
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
 $("#addDeliveryItemForm [name='order_item_id']").change(function(e) {
    let rawOrderItems = $(this).attr("data-orderitems");
    let orderItemId = $(this).val();
    let orderItems = JSON.parse(rawOrderItems);

    let selectedOrderItem = orderItems.find(x => x.id == orderItemId);
    if (selectedOrderItem) {
        $("#addDeliveryItemForm [name='quantity']").val(selectedOrderItem.quantity);
        $("#addDeliveryItemForm [name='unit_id']").val(selectedOrderItem.unit_id).trigger("change");
    }
});


</script>
<script>
    var deliveredCount = {{ $deliveredCount }};
    var pendingCount = {{ $pendingCount }};
    
    var ctx = document.getElementById('deliveryStatusChart').getContext('2d');
    var deliveryStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Delivered', 'Pending'], 
            datasets: [{
                data: [deliveredCount, pendingCount],
                backgroundColor: ['#4CAF50', '#FF9800'], 
                hoverBackgroundColor: ['#45a049', '#f39c12']
            }]
        },
        options: {
            responsive: true,
            legend: {
                
                display: true,  

                position: 'top',
            },
            title: {
                display: true,
                text: 'Delivery Status'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
</script>






@endpush

<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .modal-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .modal-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        margin-top: 10px;
    }

    .alert {
        margin-bottom: 15px;
    }

    .info-box, .aggregate-info {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .btn-toggle {
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-striped tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
.container {
    margin-top: 30px;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #007bff;
    color: white;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.order-details {
    background-color: #f8f9fa;
}

.delivery-status {
    background-color: #ffffff;
}

.info-box {
    font-size: 1.2rem;
    margin-bottom: 20px;
}

.batch-number {
    font-weight: bold;
    color: #007bff;
}

.aggregate-info {
    margin-top: 20px;
}

.list-group-item {
    background-color: #f8f9fa;
    border: none;
}

.list-group-item:hover {
    background-color: #e9ecef;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 300px;
}

    /* Additional styles for buttons, etc. */
</style>
