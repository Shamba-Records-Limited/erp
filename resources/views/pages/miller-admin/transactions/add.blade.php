@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Add Transaction</div>
        <form method="POST" action="{{route('miller-admin.transactions.add')}}">
            @csrf
            <div id="for_single_payment" class="form-group">
                <label for="">Cooperative</label>
                <select class="form-control select2bs4" id="cooperative_id" name="cooperative_id" onchange="retrieveLotSelector()">
                    <option value="" disabled selected>-- SELECT COOPERATIVE --</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{$cooperative->id}}">{{$cooperative->name}}</option>
                    @endforeach
                </select>
            </div>
            <div id="for_bulk_payment" class="form-group">
                <label for="">Lot</label>
                <select class="form-control select2bs4" multiple name="lot_ids[]" id="lot_ids" onchange="retrieveLotWeights()">
                    <option value="">All Lots</option>
                    <option disabled>Select Miller First</option>
                </select>
            </div>
            <div>
                <label for="pricing">Pricing Per Kg</label>
                <input class="form-control" type="number" name="pricing" id="pricing" value="0" onchange="calculateAmount()" />
            </div>
            <div class="d-flex justify-content-between">
                <div>Weight: <span class="text-info" id="lot_weight">0</span><span class="text-info"> KG</span></div>
            </div>
            <div>
                <label for="amount">Amount</label>
                <input class="form-control" type="number" name="amount" id="amount" value="0" readonly/>
                <p class="help-block">This is amount payable to all selected items</p>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" style="height:100% !important;"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function retrieveLotSelector() {
        let cooperative_id = $("#cooperative_id").val();

        $.ajax({
            url: `/miller-admin/transactions/add/miller-selector/${cooperative_id}`,
            type: "GET",
            success: function(data) {
                let my_elem = $('#lot_ids').first();
                // console.log($(document));
                // $(my_elem).replaceChildren(data)

                $(my_elem).empty()
                $(my_elem).append(data)
            },
            error: function(data) {
                alert("unexpected error");
            }
        });
    }

    function retrieveLotWeights() {
        var selectedLots = $("#lot_ids").val()

        $.ajax({
            url: `/miller-admin/transactions/add/retrieve-lot-weights`,
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                selectedLots
            },
            success: function(resp) {
                $("#lot_weight").text(resp.lot_weights)
                calculateAmount();
            },
            error: function(errResp) {
                alert("unexpected error");
            }
        })
    }


    function calculateAmount() {
        var qty = parseFloat($("#lot_weight").text())
        var price = parseFloat($("#pricing").val())

        alert("calculating...")

        if (qty == 0 || price == 0) {
            $("#amount").val(0)
            return
        }

        $("#amount").val(qty * price);
    }
</script>
@endpush