@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('topItem')
<!-- view delivery -->
@if($delivery_to_view)
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4>View Delivery</h4>
                <a class="close-btn" href="?tab=deliveries"><i class="mdi mdi-close"></i></a>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end p-2">
                    @if($delivery_to_view->approved_at && strpos($delivery_to_view->delivery_number, 'REJECTED') == false)
                    <div class="text-success border border-success p-2 rounded">Approved</div>

                    @elseif($delivery_to_view->approved_at && strpos($delivery_to_view->delivery_number, 'REJECTED') !== false)
                    <div class="text-warning border border-warning p-2 rounded">Rejected</div>
                    @else
                    <div class="text-warning border border-warning p-2 rounded">Pending Approval</div>
                    @endif
                </div>

                @if($delivery_to_view->approved_at)
                <div class="mt-2">
                    <div class="border rounded p-2 d-flex">
                        <div>Approved At:</div>
                        <div class="font-weight-bold ml-2">{{ $delivery_to_view->approved_at }}</div>
                    </div>
                    <div class="border rounded p-2 d-flex mt-2">
                        <div>Approved By:</div>
                        <div class="font-weight-bold ml-2">{{ $delivery_to_view->first_name }} {{ $delivery_to_view->other_names }}</div>
                    </div>
                </div>
                @else
                <div class="text-warning m-2 border border-warning p-2 rounded">
                    This delivery is yet to be approved
                </div>
                @endif

                <div class="font-weight-bold mt-4">Delivery Items</div>
                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lot</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveryItems as $key => $delivery_item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $delivery_item->lot_number }}</td>
                                <td>{{ $delivery_item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(is_null($delivery_to_view->approved_at))
                <div class="action-buttons mt-4">
                    <a href="{{ route('miller-admin.orders.approve-delivery', $delivery_to_view->id) }}" class="btn btn-primary">Approve</a>
                    <a href="#" 
                        class="btn btn-secondary ml-2" 
                        onclick="confirmRejectOrder('{{ route('miller-admin.orders.reject-delivery', $delivery_to_view->id) }}')">
                        Reject
                    </a>
                </div>
                @endif
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
        <div class="row mb-2">
            <div class="col-12 col-md-6 col-lg-4 border rounded p-2">
                Batch Number: <span class="font-weight-bold">{{$order->batch_number}}</span>
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
                        <th>Product</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->lot_number}}</td>
                        <td>{{$item->quantity}}</td>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDeliveries as $key => $delivery)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$delivery->total_items}}</td>
                        <td>
                            @if($delivery->approved_at && strpos($delivery->delivery_number, 'REJECTED') == false)
                            <div class="text-success">Approved</div>
                            @elseif($delivery->approved_at && strpos($delivery->delivery_number, 'REJECTED') !== false)
                            <div class="text-warning">Rejected</div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
      function confirmRejectOrder1(url) {
        // Show confirmation dialog
        if (confirm("Are You Sure You Want To Reject Order!")) {
            // If user confirms, redirect to the given URL
            window.location.href = url;
        }
        // If user cancels, nothing happens
    }
    function confirmRejectOrder(url) {
        Swal.fire({
            title: 'Are You Sure?',
            text: "You want to reject this order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the given URL
                window.location.href = url;
            }
        });
    }
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
