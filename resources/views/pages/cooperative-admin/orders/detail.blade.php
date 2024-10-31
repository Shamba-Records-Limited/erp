@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="?tab=items" class="list-group-item list-group-item-action {{ $tab == 'items' ? 'active' : '' }}">Order Items</a>
                <a href="?tab=deliveries" class="list-group-item list-group-item-action {{ $tab == 'deliveries' ? 'active' : '' }}">Deliveries</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">Order Details</h3>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="border rounded p-3">
                                <strong>Batch Number:</strong> <span class="font-weight-bold">{{$order->batch_number}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <h4>Total: <span class="text-success">{{$totalInOrder}} KG</span></h4>
                        </div>
                    </div>

                    <!-- Delivery Chart -->
                    <div class="my-4" style="width:200px; height:200px;">
                        <h5>Delivery Distribution</h5>
                        <canvas id="deliveryChart"></canvas>
                    </div>

                    <div class="d-flex justify-content-end">
                        @if ($tab == 'deliveries')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
                            Add Delivery
                        </button>
                        @endif
                    </div>

                 
                </div>
            </div>
        </div>
    </div>
       <div class="card">
       <div class="card-body">
                        @if ($tab == 'items' || empty($tab))
                        <div class="table-responsive">
                            <table class="table table-hover dt clickable">
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
                        <div class="table-responsive">
                            <table class="table table-hover dt ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Delivery No.</th>
                                        <th>No of Items</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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
                                            <span class="text-success">Published</span>
                                            @else
                                            <span class="text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null($delivery->published_at))
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
                                                Edit
                                            </button>
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
</div>

<!-- Add Delivery Modal -->
<div class="modal fade" id="addDeliveryModal" tabindex="-1" aria-labelledby="addDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeliveryModalLabel">Add Delivery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft delivery. Publish it to apply changes</div>
                
                <form id="addDeliveryItemForm" action="{{ route('cooperative-admin.order-delivery.add-item', $order->id) }}" >
                    @csrf
                    <div class="mb-3">
                        <label for="order_item_id" class="form-label">Select Order Item</label>
                        <select name="order_item_id" id="order_item_id" class="form-select {{ $errors->has('order_item_id') ? 'is-invalid' : '' }}" data-orderitems='@json($orderItems)'>
                            <option value="">-- Select Order Item --</option>
                            @foreach($orderItems as $item)
                                <option value="{{ $item->id }}" {{ old('order_item_id') == $item->id ? 'selected' : '' }}>{{ $item->lot_number }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('order_item_id'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('order_item_id') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" id="quantity" placeholder="Enter quantity" value="{{ old('quantity') }}">
                        @if ($errors->has('quantity'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Save Delivery Item</button>
                </form>

                <div class="table-responsive mt-3">
                    <table class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lot</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="deliveryItemsBody">
                            @foreach($draft_delivery_items as $key => $delivery_item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $delivery_item->lot_number }}</td>
                                    <td>{{ $delivery_item->quantity }}</td>
                                    <td>
                                        <form action="{{ route('cooperative-admin.order-delivery.delete-item', $delivery_item->id) }}" method="POST">
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

                <!-- Publish Button -->
                <div class="d-flex justify-content-end mt-3">
                    @if($draft_delivery)
                    <form action="{{ route('cooperative-admin.order-delivery.publish-delivery-draft', $draft_delivery->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" onclick="return confirm('Once published, this delivery cannot be changed. Are you sure?')">Publish Delivery</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('custom-scripts')
<script>
 $(document).on('click', '[data-bs-toggle="modal"]', function() {
    const targetModal = $(this).data('bs-target');
    $(targetModal).modal('show');
});
</script>
<script>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('deliveryChart').getContext('2d');
    const deliveryChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Delivered', 'Pending'],
            datasets: [{
                data: [{{ $deliveredCount }}, {{ $pendingCount }}],
                backgroundColor: ['#36A2EB', '#FF6384'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            }
        }
    });
</script>

@endpush
