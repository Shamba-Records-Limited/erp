@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Tracking Tree</h1>

    <!-- Root Type and Tree Direction Dropdowns -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="get_root_details">
                <div class="form-group">
                    <label for="root_type">Root Type</label>
                    <select class="form-control" name="root_type" id="root_type" required>
                        <option value="">-- SELECT ROOT TYPE --</option>
                        <option value="collection">Collection</option>
                        <option value="lot">Lot</option>
                        <option value="final_product">Final Product</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="direction">Tree Direction</label>
                    <select class="form-control" name="direction" id="direction" required>
                        <option value="to_source">To Source</option>
                        <option value="to_final_product">To Final Product</option>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Hierarchical Data Structure as Timeline -->
    <div class="timeline">
        <!-- Order Level -->
        <div class="timeline-item">
            <div class="timeline-icon">
                <i class="fas fa-circle"></i>
            </div>
            <div class="timeline-content">
                <h5>Order: B20240703-1</h5>
                <button class="btn btn-success btn-sm" data-toggle="collapse" data-target="#orderDetails" aria-expanded="false" aria-controls="orderDetails">Show Details</button>
                <div id="orderDetails" class="collapse mt-2">
                    <!-- Delivery Level -->
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Delivery:</h6>
                            <p>Child Details Here</p>
                            <button class="btn btn-success btn-sm" data-toggle="collapse" data-target="#deliveryDetails" aria-expanded="false" aria-controls="deliveryDetails">Show Children</button>
                            <div id="deliveryDetails" class="collapse mt-2">
                                <!-- Pre-Milled Inventory Level -->
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="fas fa-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pre-Milled Inventory: INV20240827002</h6>
                                        <p>Child Details Here</p>
                                        <button class="btn btn-success btn-sm" data-toggle="collapse" data-target="#preMilledDetails" aria-expanded="false" aria-controls="preMilledDetails">Show Children</button>
                                        <div id="preMilledDetails" class="collapse mt-2">
                                            <!-- Milled Inventory Level -->
                                            <div class="timeline-item">
                                                <div class="timeline-icon">
                                                    <i class="fas fa-circle"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6>Raw Material: Milled Inventory: INV20241011002</h6>
                                                    <p>Child Details Here</p>
                                                    <button class="btn btn-success btn-sm" data-toggle="collapse" data-target="#milledDetails1" aria-expanded="false" aria-controls="milledDetails1">Show Children</button>
                                                    <div id="milledDetails1" class="collapse mt-2">
                                                        <h6>Raw Material: Milled Inventory: INV20241003001</h6>
                                                        <p>Child Details Here</p>
                                                    </div>
                                                </div>
                                            </div> <!-- End of Milled Inventory -->
                                        </div>
                                    </div>
                                </div> <!-- End of Pre-Milled Inventory -->
                            </div>
                        </div>
                    </div> <!-- End of Delivery -->
                </div>
            </div>
        </div> <!-- End of Order -->
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    // Handle form submission without reloading the page
    $("#get_root_details").on("submit", function(e) {
        e.preventDefault();  // Prevent the page reload
        // You can add any logic here if needed, like validating inputs
    });

    // Toggle collapse functionality for expand buttons
    $(document).ready(function () {
        $('.btn').on('click', function() {
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });
    });
</script>
@endpush

<!-- CSS -->
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
        margin-top: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #007bff;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 50px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 10px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #007bff;
        text-align: center;
        line-height: 20px;
        color: white;
    }

    .timeline-content {
        padding: 10px 20px;
        background: #f9f9f9;
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 5px 10px; /* Smaller padding for compact buttons */
    }

    .timeline-content h6, .timeline-content h5 {
        margin-top: 0;
    }
</style>
