@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush

@section('content')
@php
$ticket_labels = config('enums.ticket_labels');
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Ticket Number Card -->
            <div class="card text-center mb-4 shadow-sm border-primary">
                <div class="card-body p-4">
                    <h5 class="card-title text-uppercase text-primary">Ticket Number</h5>
                    <h2 class="font-weight-bold text-dark">#{{ $ticket->number }}</h2>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card shadow-lg border-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title">Create a Support Ticket</h2>
                        <span class="badge badge-warning">Draft</span>
                    </div>
                    <p class="card-subtitle text-muted mb-4">Please fill in the details of your issue below:</p>

                    <form action="{{ route('farmer.support.add_ticket') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="form-error" class="alert alert-danger" style="display:none"></div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="subject">Subject</label>
                                <input id="subject" name="subject" type="text" placeholder="Enter subject here" class="form-control" value="{{ $ticket->title }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="labels">Labels</label>
                                <select name="labels" id="labels" class="form-control form-select">
                                    <option value="" disabled selected>Select a label</option>
                                    @foreach($ticket_labels as $label)
                                        <option value="{{ $label }}" @if(!is_null($ticket->labels) && $ticket->labels === $label) selected @endif>{{ ucwords($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="module">Module</label>
                                <input id="module" name="module" type="text" placeholder="Enter module here" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="submodule">Submodule</label>
                                <input id="submodule" name="submodule" type="text" placeholder="Enter submodule here" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="link">Link</label>
                                <input id="link" name="link" type="url" placeholder="Enter URL here" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Upload Image</label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Enter detailed description here" class="form-control" rows="5">{{ $ticket->description }}</textarea>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <button type="button" class="btn btn-primary" onclick="publishForm()">Publish</button>
                            <!-- <button type="button" class="btn btn-danger" onclick="discardTicket()">Discard Ticket Draft</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endpush

@push('custom-scripts')
<script>
function submitForm() {
    let formData = new FormData();
    formData.append('ticket_number', "{{ $ticket->number }}");
    formData.append('labels', $('#labels').val());
    formData.append('subject', $('#subject').val());
    formData.append('module', $('#module').val());
    formData.append('submodule', $('#submodule').val());
    formData.append('link', $('#link').val());
    formData.append('description', $('#description').val());
    formData.append('_token', "{{ csrf_token() }}");

    if ($('#image')[0].files.length > 0) {
        formData.append('image', $('#image')[0].files[0]);
    }

    $.ajax({
        url: "{{ route('farmer.support.add_ticket') }}",
        type: "POST",
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#form-error').hide();
            $(".invalid-feedback").html("");
            $(".is-invalid").removeClass("is-invalid");
            $(".form-group").removeClass("has-error");
        },
        success: function(data) {
            console.log(data);
        },
        error: function(data) {
            const resp = data.responseJSON;
            if (resp.errors) {
                $('#form-error').html(resp.message).show();
                for (let error in resp.errors) {
                    $('#' + error).addClass('is-invalid');
                    $('#' + error).focus();
                    $('#' + error).closest('.form-group').addClass('has-error');
                    $('#' + error).closest('.form-group').find('.invalid-feedback').html(resp.errors[error][0]);
                }
            }
        }
    });
}

function publishForm() {
    let c = confirm("Are you sure? This will publish your draft changes.");
    if (c) {
        let formData = new FormData();
        formData.append('number', "{{ $ticket->number }}");
        formData.append('subject', $('#subject').val());
        formData.append('module', $('#module').val());
        formData.append('submodule', $('#submodule').val());
        formData.append('link', $('#link').val());
        formData.append('description', $('#description').val());
        formData.append('labels', $('#labels').val());
        formData.append('_token', "{{ csrf_token() }}");

        if ($('#image')[0].files.length > 0) {
            formData.append('image', $('#image')[0].files[0]);
        }

        $.ajax({
            url: "{{ route('farmer.support.publish_ticket', $ticket->number) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    window.location.href = "{{ route('farmer.support.show') }}";
                } else {
                    $('#form-error').html(data.message).show();
                }
            },
            error: function(data) {
                const resp = data.responseJSON;
                if (resp && resp.message) {
                    $('#form-error').html(resp.message).show();
                } else {
                    $('#form-error').html("An unexpected error occurred.").show();
                }
            }
        });
    }
}

</script>
@endpush

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.card-subtitle {
    font-size: 1rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.form-group {
    margin-bottom: 1.5rem;
}

.select2-container .select2-selection--single {
    height: 38px;
}
</style>