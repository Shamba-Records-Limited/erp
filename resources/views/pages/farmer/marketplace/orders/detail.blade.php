@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('topItem')

@endsection


@section('content')

<div class="card">
    <div class="card-body">
        <div class="card-title">Order Details</div>
        <div class="row mb-2">
            <div class="col-12 col-md-6 col-lg-4 border rounded p-2">
                Sale Number: <span class="font-weight-bold">{{$order->batch_number}}</span>
            </div>

            <div class="col-12 col-md-6 col-lg-4 border rounded p-2">
                Sale Value: <span class="font-weight-bold">KShs.{{$order->paid_amount}}</span>
            </div>
        </div>
     
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total(KShs)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->sale_price * $item->quantity}}</td>
                        <td></td>
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
