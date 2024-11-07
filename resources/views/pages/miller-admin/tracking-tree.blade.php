@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<style>
    /* Form and Container Styling */
    .form-container {
        background-color: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 0.25rem;
        padding: 20px;
        width: 24rem;
        margin-right: 20px;
    }

    /* Timeline Styling */
    .timeline-container {
        min-height: 100vh;
        width: 100%;
        padding: 20px;
        background-color: #f3f4f6;
    }

    .timeline {
        width: 80%;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }

    .timeline ul {
        list-style: none;
        padding: 0;
    }

    .timeline ul li {
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .timeline ul li:last-child {
        margin-bottom: 0;
    }

    .timeline-content h1 {
        font-weight: 500;
        font-size: 20px;
        color: #333;
    }

    .timeline-content .date {
        font-size: 14px;
        font-weight: 300;
        color: #888;
        margin-bottom: 10px;
    }

    @media only screen and (min-width: 768px) {
        .timeline:before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background-color: gray;
        }

        .timeline ul li {
            width: 45%;
            position: relative;
            margin-bottom: 50px;
        }

        .timeline ul li:nth-child(odd) {
            float: left;
            clear: right;
            transform: translateX(-30px);
            border-radius: 20px 0px 20px 20px;
        }

        .timeline ul li:nth-child(even) {
            float: right;
            clear: left;
            transform: translateX(30px);
            border-radius: 0px 20px 20px 20px;
        }

        .timeline ul li::before {
            content: "";
            position: absolute;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background-color: gray;
            top: 0;
        }

        .timeline ul li:nth-child(odd)::before {
            transform: translate(50%, -50%);
            right: -30px;
        }

        .timeline ul li:nth-child(even)::before {
            transform: translate(-50%, -50%);
            left: -30px;
        }

        .timeline-content .date {
            position: absolute;
            top: -30px;
        }
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="card-title">Tracking Tree</div>
        <div class="container my-5">
            <div class="row">
                <!-- Root Type Card -->
                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header text-center bg-gradient-success text-white">
                            <h5 class="font-weight-bold mb-0">Root Type</h5>
                        </div>
                        <div class="card-body text-center">
                            <form id="get_root_details">
                                <div class="form-group">
                                    <select class="form-control form-select node_type" name="root_type" id="root_type">
                                        <option value="">-- SELECT ROOT TYPE --</option>
                                        <option value="collection">Collection</option>
                                        <option value="lot">Lot</option>
                                        <option value="final_product">Final Product</option>
                                    </select>
                                    <span class="help-block text-danger">
                                        <strong id="root_type_error"></strong>
                                    </span>
                                </div>
                                <button class="btn btn-primary btn-fw btn-sm show-children mt-3">Show Children</button>

                                <!-- <button class="btn btn-outline-primary mt-3">Submit</button> -->
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tree Direction Card -->
                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header text-center bg-gradient-success text-white">
                            <h5 class="font-weight-bold mb-0">Tree Direction</h5>
                        </div>
                        <div class="card-body text-center">
                            <form id="tree_direction_form">
                                <div class="form-group">
                                    <select class="form-control form-select node_type" name="direction" id="direction">
                                        <option value="to_source">To Source</option>
                                        <option value="to_final_product">To Final Product</option>
                                    </select>
                                    <span class="help-block text-danger">
                                        <strong id="direction_error"></strong>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


                
            </div>

            <!-- Right side timeline structure -->
            <div class="timeline-container">
                <div class="timeline">
                    <ul id="tree-structure">
                        <!-- Timeline structure will be dynamically generated here -->
                    </ul>
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
    let csrf = "{{ csrf_token() }}";

    $('#root_type').on('change', function(e) {
        let root_type = $("#root_type").val() || '';
        if (root_type === '') {
            return;
        }

        // Fetch root identifier details based on selected root type
        $.ajax({
            method: 'GET',
            url: `/miller-admin/tracking-tree/root-identifier/${root_type}`,
            error: function(data) {
                console.error("Error fetching root identifier:", data);
            },
            success: function(data) {
                $("#identifier").closest(".form-group").remove();
                $("#root_type").closest(".form-group").after(data);
            }
        });
    });

    $("#get_root_details").on('submit', function(e) {
        e.preventDefault();
        let data = {
            _token: "{{ csrf_token() }}",
            root_type: $("#root_type").val() || '',
            identifier: $("#identifier").val() || '',
        };

        $("#root_type").removeClass("is-invalid");
        $("#root_type_error").html("");
        $("#identifier").removeClass("is-invalid");
        $("#identifier_error").html("");

        let has_error = false;
        if (data.root_type === '') {
            $("#root_type").addClass("is-invalid");
            $("#root_type_error").html("This field is required");
            has_error = true;
        }

        if (data.identifier === '') {
            $("#identifier").addClass("is-invalid");
            $("#identifier_error").html("This field is required");
            has_error = true;
        }

        if (has_error) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "/miller-admin/tracking-tree/root-details",
            data,
            error: function(data) {
                console.error("Error submitting root details:", data);
            },
            success: function(data) {
                console.log("Root details submitted successfully:", data);
            }
        });
    });

    $("body").on("click", ".show-children", function(e) {
        let direction = $("#direction").val();
        if (direction === "") {
            return;
        }

        let node_type_elem = $(e.target).closest(".node").find(".node_type")[0];
        let node_type = $(node_type_elem).val();
        if (node_type === "") {
            alert("Unable to find node_type");
            return;
        }

        let node_identity_elem = $(e.target).closest(".node").find(".node_identity")[0];
        let node_identity = $(node_identity_elem).val();
        if (node_identity === "") {
            alert("Unable to find node_identity");
            return;
        }

        $.ajax({
            method: 'POST',
            url: "/miller-admin/tracking-tree/node-children",
            data: {
                _token: "{{ csrf_token() }}",
                node_type,
                node_identity,
                direction
            },
            error: function(data) {
                console.error("Error fetching children nodes:", data);
            },
            success: function(data) {
                let listItem = `<li>
                                    <div class="timeline-content">
                                        <h1>${data.title}</h1>
                                        <p>${data.details}</p>
                                        <button class="btn btn-primary btn-sm show-children mt-2">Show Children</button>
                                    </div>
                                </li>`;
                $("#tree-structure").append(listItem);
            }
        });
    });
</script>
@endpush
