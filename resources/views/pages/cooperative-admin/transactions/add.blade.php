@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Add Transaction</div>
        <form method="POST" action="{{route('cooperative-admin.transactions.add')}}">
            @csrf
            <div id="for_single_payment" class="form-group">
                <label for="">Farmer</label>
                <select class="form-control select2bs4" id="farmer_id" name="farmer_id" onchange="retrieveCollectionsSelector()">
                    <option value="" disabled selected>-- SELECT FARMER --</option>
                    @foreach($farmers as $farmer)
                    <option value="{{$farmer->id}}">{{$farmer->username}}</option>
                    @endforeach
                </select>
            </div>
            <div id="for_bulk_payment" class="form-group">
                <label for="">Collections</label>
                <select class="form-control select2bs4" multiple name="collection_ids[]" id="collection_ids">
                    <option value="">All Collections</option>
                    <option disabled>Select Farmer First</option>
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
    function retrieveCollectionsSelector() {
        let farmer_id = $("#farmer_id").val();

        $.ajax({
            url: `/cooperative-admin/transactions/add/collection-selector/${farmer_id}`,
            type: "GET",
            success: function(data) {
                let my_elem = $('#collection_ids').first();
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