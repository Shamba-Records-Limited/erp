@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Tracking Tree</div>
        <div>
            <!-- root -->
            <div class="node">
                <div class="border rounded p-2">
                    <input type="hidden" name="level" class="level" value="0">
                    <form id="get_root_details">
                        <div class="form-group">
                            <label for="root_type">Root Type</label>
                            <select class="form-control select2bs4 node_type" name="root_type" id="root_type">
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
                            <select class="form-control select2bs4 node_type" name="direction" id="direction">
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
                <div class="pt-3 ml-3 position-relative pl-2">
                    <!-- vertical bar -->
                    <div class="position-absolute h-100 w-10 bg-black" style="top:0;left:0;height: 100%; width: 5px; background: black"></div>
                    <!-- /vertical bar -->
                    <div class="node_children">
                        <!-- child -->
                        <div class="position-relative pl-3">
                            <!-- horizontal bar -->
                            <div class="position-absolute" style="top:50%;left:0;height: 2px; width: 15px; background: black"></div>
                            <!-- /horizontal bar -->
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between">
                                    <div>Child Type: Identifier</div>
                                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#child_details">View Details</button>
                                </div>
                                <div id="child_details">
                                    Child Details Here
                                </div>
                                <button class="btn btn-outline-primary mt-2 show-children">Show Children</button>
                                <!-- child details -->
                                <!-- /child details -->
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
        let root_type = $("#root_type").val() || ''
        if (root_type == '') {
            return
        }

        // submit form
        $.ajax({
            method: 'GET',
            url: `/miller-admin/tracking-tree/root-identifier/${root_type}`,
            error: function(data) {

            },
            success: function(data) {
                $("#identifier").closest(".form-group").remove();
                $("#root_type").closest(".form-group").after(data)
            }
        })
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