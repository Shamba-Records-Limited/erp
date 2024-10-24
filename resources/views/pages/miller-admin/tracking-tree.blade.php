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

    <!-- Collapse Button at the Top -->
    <button class="btn btn-primary" id="collapseBtn" data-toggle="collapse" data-target="#timelineContent" aria-expanded="true" aria-controls="timelineContent">
        Click to collapse
    </button>

    <!-- Collapsible Timeline Content -->
    <div id="timelineContent" class="collapse show">
        <div class="timeline">
            
            <!-- Timeline Item: Collection Stage -->
            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="timeline-content">
                    <h6>Stage: Collection</h6>
                    <p>The coffee beans are collected from the farms and are now ready to be transported to the processing plant.</p>
                    <button class="btn btn-secondary" data-toggle="collapse" data-target="#deliveryStage" aria-expanded="false">Next Step</button>
                </div>
            </div>

            <!-- Timeline Item: Delivery Stage -->
            <div id="deliveryStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Delivery</h6>
                        <p>The coffee beans have been delivered to the milling plant for processing.</p>
                        <button class="btn btn-secondary" data-toggle="collapse" data-target="#preMilledStage" aria-expanded="false">Next Step</button>
                    </div>
                </div>
            </div>

            <!-- Timeline Item: Pre-Milled Inventory -->
            <div id="preMilledStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Pre-Milled Inventory</h6>
                        <p>The coffee beans are stored in inventory, awaiting the milling process to begin.</p>
                        <button class="btn btn-secondary" data-toggle="collapse" data-target="#millingStage" aria-expanded="false">Next Step</button>
                    </div>
                </div>
            </div>

            <!-- Timeline Item: Milling Process -->
            <div id="millingStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Milling Process</h6>
                        <p>The coffee beans are being milled to remove the outer husk, producing green coffee beans.</p>
                        <button class="btn btn-secondary" data-toggle="collapse" data-target="#qualityControlStage" aria-expanded="false">Next Step</button>
                    </div>
                </div>
            </div>

            <!-- Timeline Item: Quality Control -->
            <div id="qualityControlStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Quality Control</h6>
                        <p>The milled coffee beans are undergoing quality control to ensure they meet export or roasting standards.</p>
                        <button class="btn btn-secondary" data-toggle="collapse" data-target="#exportStage" aria-expanded="false">Next Step</button>
                    </div>
                </div>
            </div>

            <!-- Timeline Item: Export Readiness -->
            <div id="exportStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Export Readiness</h6>
                        <p>The coffee beans have passed quality control and are ready to be exported or sent to roasters.</p>
                        <button class="btn btn-secondary" data-toggle="collapse" data-target="#finalProductStage" aria-expanded="false">Next Step</button>
                    </div>
                </div>
            </div>

            <!-- Timeline Item: Final Product -->
            <div id="finalProductStage" class="collapse">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>Stage: Final Product</h6>
                        <p>The coffee beans have been roasted, packaged, and are now ready for distribution to retailers and consumers.</p>
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

    // Toggle collapse button text between "Click to collapse" and "Click to expand"
    $('#collapseBtn').click(function () {
        if ($('#timelineContent').hasClass('show')) {
            $(this).text('Click to expand');
        } else {
            $(this).text('Click to collapse');
        }
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
        left: 35px; /* Center line adjustment */
        top: 0;
        bottom: 0;
        width: 2px;
        background: #007bff;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 60px; /* Increase padding for icon */
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 20px; /* Adjust the position of the icon */
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

    .location {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .timestamp {
        font-size: 0.9rem;
        color: #666;
    }

    .status {
        font-size: 0.9rem;
        color: #333;
    }

    .timeline-content p {
        margin: 5px 0;
    }

    .btn-link {
        font-size: 1rem;
        font-weight: bold;
        text-decoration: none;
        color: #007bff;
    }

    .btn-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .btn-secondary {
        margin-top: 10px;
    }
</style>
