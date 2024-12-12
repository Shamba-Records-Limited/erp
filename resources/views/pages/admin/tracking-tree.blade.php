@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<style>
    .form-container {
    background-color: #f8f9fa; /* Light background for the form */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.child_node_container {
    background-color: #e9ecef; /* Light background for the child node container */
    border: 1px solid #ced4da; /* Border for the child node container */
    border-radius: 0.25rem; /* Rounded corners */
    padding: 15px; /* Padding for the child node container */
}

.node_children {
    margin-top: 10px; /* Space between child nodes */
}

/* Main Container */
.card {
    margin: 20px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

</style>
<div class="card">
    <div class="card-body">
        <div class="card-title">Tracking Tree</div>
        <div>
            <!-- root -->
            <div class="node">
                <div class="d-flex">
                    <div class="form-container border rounded p-3" style="width:24rem; margin-right: 20px; height:600px;"> <!-- Added flex and margin -->
                        <input type="hidden" name="level" class="level" value="0">
                        <form id="get_root_details">
                            <div class="form-group">
                                <label for="cooperative">Cooperative Name</label>
                                <select class="form-control form-select node_type" name="root_type" id="root_type">
                                    <option value="">-- Choose Cooperative  --</option>
                                    @foreach($cooperatives as $coop)
                                    <option value="{{$coop->id}}">{{$coop->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cooperative">Miller Name</label>
                                <select class="form-control form-select node_type" name="root_type" id="root_type">
                                <option value="">-- Choose Miller  --</option>
                                    @foreach($millers as $miller)
                                    <option value="{{$miller->id}}">{{$miller->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                        </form>
                        <!-- root details -->
                        <div id="root_details_wrap mt-2">
                            <div id="root_details_top" class="border rounded p-2">
                                <div class="d-flex justify-content-between">
                                    <div>Root Details</div>
                                    <button class="mt-4 ml-2 btn btn-info btn-rounded" data-toggle="collapse" data-target="#root_details">View Details</button>
                                </div>
                                <div id="root_details">
                                    Details Here
                                </div>
                            </div>
                        </div>
                        <!-- /root details -->
                        <button class="btn btn-primary btn-fw btn-sm show-children mt-4">Show Children</button>
                    </div>


                    <div class="cbg-dark"> <!-- Added flex -->
                        <div class="node_children container-fluid">
                            <!-- child -->
                        <div class="row" >

                                <div class="col-md-12 d-flex flex-column align-items-center justify-content-center">
                                    <!-- CARD I -->
                                   <!-- <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Child Type: Identifier</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                        <button class="mt-4 ml-2 btn btn-info btn-rounded" data-toggle="collapse" data-target="#child_details">View Details</button>
                                    </div>
                                    <div id="child_details">
                                        Child Details Here
                                    </div>
                                    <button class="btn btn-primary btn-fw btn-sm show-children">Show Children</button>
                                    </div> -->
                                    <!-- END CARD I -->


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
            url: `/admin/tracking-tree/root-identifier/${root_type}`,
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
            url: "/admin/tracking-tree/root-details",
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
            url: "/admin/tracking-tree/node-children",
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
