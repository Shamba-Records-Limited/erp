@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <card-body class="card-title">Cooperative</card-body>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function deleteCoop(id) {
        shouldDelete =  confirm("Are you sure you want to delete this cooperative?")
        if (!shouldDelete){
            return
        }

        window.location = "/admin/cooperative/setup/delete/"+id
    }
</script>
@endpush