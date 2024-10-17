@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addBankAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addBankAccordion"><span class="mdi mdi-plus"></span>Add Bank
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addBankAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Register Bank</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.bank.add') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="bankName">Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="bankName" placeholder="ABC" value="{{ old('name')}}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="contact_no">Contact No.</label>
                                    <input type="text" name="contact_no"
                                           class="form-control  {{ $errors->has('contact_no') ? ' is-invalid' : '' }}"
                                           id="contact_no" placeholder="07..." value="{{ old('contact_no')}}">

                                    @if ($errors->has('contact_no'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('contact_no')  }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="swift_code">Swift Code</label>
                                    <input type="text" name="swift_code"
                                           class="form-control  {{ $errors->has('swift_code') ? ' is-invalid' : '' }}"
                                           value="{{ old('swift_code')}}" id="swift_code" placeholder="CNN"
                                           required>
                                    @if ($errors->has('swift_code'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('swift_code')  }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Banks</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Swift Code</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banks as $key => $bank)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$bank->name }}</td>
                                    <td>{{$bank->contact_no }}</td>
                                    <td>{{$bank->swift_code }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#editModal_{{$bank->id}}"><span
                                                    class="mdi mdi-file-edit"></span></button>

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$bank->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$bank->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$bank->id}}">
                                                            Edit {{$bank->name}} Bank</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.bank.edit', $bank->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="bankName{{$bank->id}}">Name</label>
                                                                    <input type="text" name="name"
                                                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                           id="bankName{{$bank->id}}" placeholder="ABC"
                                                                           value="{{ $bank->name }}" required>

                                                                    @if ($errors->has('name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="contact_no{{$bank->id}}">Contact No.</label>
                                                                    <input type="text" name="contact_no"
                                                                           class="form-control  {{ $errors->has('contact_no') ? ' is-invalid' : '' }}"
                                                                           id="contact_no{{$bank->id}}" placeholder="07..."
                                                                           value="{{ $bank->contact_no }}">

                                                                    @if ($errors->has('contact_no'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('contact_no')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="swift_code{{$bank->id}}">Swift Code</label>
                                                                    <input type="text" name="swift_code"
                                                                           class="form-control  {{ $errors->has('swift_code') ? ' is-invalid' : '' }}"
                                                                           value="{{ $bank->swift_code}}"
                                                                           id="swift_code{{$bank->id}}" placeholder="CNN"
                                                                           required>
                                                                    @if ($errors->has('swift_code'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('swift_code')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush