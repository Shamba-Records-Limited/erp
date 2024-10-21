@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Shamba Traceability</h1>

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

    <!-- Hierarchical Data Structure -->
    <!-- Order -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Order: B20240703-1</h5>
            <button class="btn btn-success" data-toggle="collapse" data-target="#orderDetails" aria-expanded="false" aria-controls="orderDetails">Show Children</button>
            <div id="orderDetails" class="collapse">
                <div class="card card-body mt-3">
                    <h6>Delivery:</h6>
                    <p>Child Details Here</p>
                    <button class="btn btn-success" data-toggle="collapse" data-target="#deliveryDetails" aria-expanded="false" aria-controls="deliveryDetails">Show Children</button>
                    <div id="deliveryDetails" class="collapse mt-2">
                        <h6>Pre-Milled Inventory :INV20240827002</h6>
                        <p>Child Details Here</p>
                        <button class="btn btn-success" data-toggle="collapse" data-target="#preMilledDetails" aria-expanded="false" aria-controls="preMilledDetails">Show Children</button>
                        <div id="preMilledDetails" class="collapse mt-2">
                            <h6>Raw Material: Milled Inventory :INV20241011002</h6>
                            <p>Child Details Here</p>
                            <button class="btn btn-success" data-toggle="collapse" data-target="#milledDetails1" aria-expanded="false" aria-controls="milledDetails1">Show Children</button>
                            <div id="milledDetails1" class="collapse mt-2">
                                <h6>Raw Material: Milled Inventory :INV20241003001</h6>
                                <p>Child Details Here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
