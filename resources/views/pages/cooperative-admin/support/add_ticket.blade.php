@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
@php
$ticket_labels = config('enums.ticket_labels');
@endphp
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-title">Add Support Ticket</div>
            <span class="badge badge-warning">Draft</span>
        </div>
        <div class="card-subtitle">Specify any issue you would like help with below.</div>
        <div class="my-2 mb-4">Ticket No: {{$ticket->number}}</div>
        <form action="{{route('cooperative-admin.support.add_ticket')}}" method="POST">
            <div id="form-error" class="alert alert-danger" style="display:none"></div>
            <div class="form-group">
                <label for="labels">Labels</label>
                <select name="labels[]" multiple="multiple" id="labels" class="form-control select2bs4" placeholder="Select Label(s)" onchange="submitForm()">
                    @foreach($ticket_labels as $label)
                    <option value="{{$label}}" @if(!is_null($ticket->labels) && in_array($label, json_decode($ticket->labels))) selected @endif> {{ucwords($label)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="#title">Title</label>
                <input id="title" name="title" type="text" placeholder="Enter title here" class="form-control" onblur="submitForm()" value="{{$ticket->title}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="#description">Description</label>
                <textarea id="description" name="description" placeholder="Enter description here" class="form-control" onblur="submitForm()">{{$ticket->description}}</textarea>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="publishForm()">Publish</button>
                <button type="button" class="btn btn-secondary" onclick="discardTicket()">Discard Ticket Draft</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function submitForm() {
        $.ajax({
            url: "{{route('cooperative-admin.support.add_ticket')}}",
            type: "POST",
            data: {
                ticket_number: "{{ $ticket->number }}",
                labels: $('#labels').val(),
                title: $('#title').val(),
                description: $('#description').val(),
                _token: "{{csrf_token()}}"
            },
            dataType: 'json',
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
                resp = data.responseJSON;
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
        })
    }

    function publishForm() {
        let c = confirm("Are you sure? This will publish your draft changes.")
        if (c) {
            $.ajax({
                url: "{{route('cooperative-admin.support.publish_ticket', $ticket->id)}}",
                type: "POST",
                data: {
                    number: "{{$ticket->number}}",
                    title: $('#title').val(),
                    description: $('#description').val(),
                    labels: $('#labels').val(),
                    _token: "{{csrf_token()}}"
                },
                beforeSend: function() {
                    $('#form-error').hide();
                    $(".invalid-feedback").html("");
                    $(".is-invalid").removeClass("is-invalid");
                    $(".form-group").removeClass("has-error");
                },

                success: function(data) {
                    window.location.href = "{{route('cooperative-admin.support.show')}}";
                },
                error: function(data) {
                    resp = data.responseJSON;
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
    }

    function discardTicket() {
        let c = confirm("Are you sure? This will discard your draft changes.")
        if (c) {
            $.ajax({
                url: "{{route('cooperative-admin.support.delete_ticket', $ticket->id)}}",
                type: "DELETE",
                data: {
                    _token: "{{csrf_token()}}"
                },
                success: function(data) {
                    window.location.href = "{{route('cooperative-admin.support.show')}}";
                },
                error: function(data) {
                    alert("error");
                }
            });

        }
    }
</script>
@endpush