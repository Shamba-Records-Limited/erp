@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Tracking Tree</div>
            <div class="container my-5">
                <div class="row justify-content-center">
                    <!-- Root Type Card -->
                    <div class="col-md-3 col-12 mb-4">
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
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder for dynamically generated card -->
                    <div class="col-md-3 col-12 mb-4" id="dynamic-card-container"></div>

                    <!-- Tree Direction Card -->
                    <div class="col-md-3 col-12 mb-4">
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
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary">Submit</button>
                </div>
            </div>
        <div>
            <!-- root -->
            <div class="node">
                <div class="border rounded p-2">
                    <input type="hidden" name="level" class="level" value="0">
                    <form id="get_root_details">
                        <div class="form-group">
                            <label for="root_type">Root Type</label>
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

                        <div class="form-group">
                            <label for="tree_direction">Tree Direction</label>
                            <select class="form-control form-select node_type" name="direction" id="direction">
                                <option value="to_source">To Source</option>
                                <option value="to_final_product">To Final Product</option>
                            </select>

                            <span class="help-block text-danger">
                                <strong id="direction_error"></strong>
                            </span>
                        </div>
                        <button class="btn btn-outline-primary">Submit</button>
                    </form>
                    <!-- root details -->
                    <div id="root_details_wrap mt-2">
                        <div id="root_details_top" class="border rounded p-2">
                            <div class="d-flex justify-content-between">
                                <div>Root Details</div>
                                <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#root_details">View Details</button>
                            </div>
                            <div id="root_details">
                                Details Here
                            </div>
                        </div>
                    </div>
                    <!-- /root details -->
                    <button class="btn btn-outline-primary mt-2 show-children">Show Children</button>
                </div>
                <div class="pt-3 ml-3 position-relative" style="padding-left: 20px;">
                    <!-- vertical bar -->
                    <div style="position: absolute; left: 10px; top: 0; height: 100%; width: 5px; background: green; border-radius: 10px;"></div>
                    <!-- /vertical bar -->
                    <div class="node_children">
                        <!-- child -->
                        <div class="position-relative" style="padding-left: 30px;">
                            <!-- horizontal bar -->
                            <div style="position: absolute; top: 50%; left: 0; height: 3px; width: 15px; background: green;"></div>
                            <!-- /horizontal bar -->
                            <div style="position: absolute; left: 3px; top: 0px; width: 15px; height: 15px; background-color: white; border: 3px solid green; border-radius: 50%;"></div>
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between">
                                    <div>Child Type: Identifier</div>
                                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#child_details">View Details</button>
                                </div>
                                <div id="child_details">
                                    Child Details Here
                                </div>
                                <button class="btn btn-outline-primary mt-2 show-children">Show Children</button>
                            </div>
                        </div>
                        <!-- /child -->
                    </div>
                </div>
            </div>
            <!-- /root -->
        </div>
    </div>
</div>

@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    let csrf = "{{csrf_token()}}";

    $('#root_type').on('change', function(e) {
        let root_type = $(this).val() || '';
        
        // Clear the dynamically generated card container if no root type is selected
        if (root_type === '') {
            $("#dynamic-card-container").empty();
            return;
        }

        // Dynamically generate a new card based on the selected root type
        let dynamicCardHtml = `
            <div class="card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient-warning text-white">
                    <h5 class="font-weight-bold mb-0">${root_type}</h5>
                </div>
                <div class="card-body text-center">
                    <form id="${root_type}_form">
                        <div class="form-group">
                            <label for="${root_type}_select">${root_type} Options</label>
                            <select class="form-control form-select" name="${root_type}_select" id="${root_type}_select">
                                <option value="">-- SELECT ${root_type.toUpperCase()} --</option>
                            </select>
                            <span class="help-block text-danger">
                                <strong id="${root_type}_error"></strong>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // Insert the generated card into the placeholder container
        $("#dynamic-card-container").html(dynamicCardHtml);

        // Fetch the relevant options for the selected root type
        $.ajax({
            method: 'GET',
            url: `/miller-admin/tracking-tree/root-identifier/${root_type}`,
            success: function(data) {
                // Assuming `data` is an array of options (e.g., [{id: 1, name: 'Option 1'}, {id: 2, name: 'Option 2'}])
                let optionsHtml = data.map(option => `<option value="${option.id}">${option.name}</option>`).join('');
                
                // Insert the options into the select element of the dynamically created card
                $(`#${root_type}_select`).append(optionsHtml);
            },
            error: function(xhr, status, error) {
                console.error("Failed to fetch options:", error);
            }
        });
    });

    $("#get_root_details").on('submit', function(e) {
        e.preventDefault();
        // validate form
        let data = {
            _token: "{{ csrf_token() }}",
            root_type: $("#root_type").val() || '',
            identifier: $("#identifier").val() || '',
        }

        $("#root_type").removeClass("is-invalid");
        $("#root_type_error").html("");
        $("#identifier").removeClass("is-invalid");
        $("#identifier_error").html("");

        let has_error = false;
        if (data.root_type == '') {
            $("#root_type").addClass("is-invalid");
            $("#root_type_error").html("This field is required");
            has_error = true
        }

        if (data.identifier == '') {
            $("#identifier").addClass("is-invalid");
            $("#identifier_error").html("This field is required");
            has_error = true
        }

        if (has_error) {
            return
        }

        // submit form
        $.ajax({
            method: 'POST',
            url: "/miller-admin/tracking-tree/root-details",
            data,
            error: function(data) {

            },
            success: function(data) {

            }
        })
    })

    $("body").on("click", ".show-children", function(e) {
        let direction = $("#direction").val();
        if (direction == "") {
            return
        }

        let node_type_elem = $(e.target).closest(".node").find(".node_type")[0]
        let node_type = $(node_type_elem).val()
        if (node_type == "") {
            alert("unable to find node_type")
            return
        }

        let node_identity_elem = $(e.target).closest(".node").find(".node_identity")[0]
        let node_identity = $(node_identity_elem).val()
        if (node_identity == "") {
            alert("unable to find node_identity")
            return
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

            },
            success: function(data) {
                let node_children_elem = $(e.target).closest(".node").find(".node_children");
                console.log(node_children_elem);
                $(node_children_elem).empty();
                $(node_children_elem).append(data);
            }
        })
    })
</script>
@endpush
