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
                <select class="form-control select2bs4" multiple name="lot_ids[]" id="lot_ids">
                    <option value="">All Lots</option>
                    <option disabled>Select Miller First</option>
                </select>
            </div>
            <div>
                <label for="amount">Amount</label>
                <input class="form-control" type="number" name="amount" id="amount" />
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
</script>
@endpush