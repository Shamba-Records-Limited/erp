@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addVehicleTypeAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVehicleTypeAccordion"><span
                                    class="mdi mdi-plus"></span>Add Transport Provider
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addVehicleTypeAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Transport Provider</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.logistics.transporters.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="unitName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="unitName" value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" name="phone_number"
                                               class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
                                               id="phone" value="{{ old('phone_number')}}" required>

                                        @if ($errors->has('phone_number'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('phone_number')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-12 col-md-12 col-12">
                                        <label for="location">Location</label>
                                        <textarea name="location" cols="2"
                                                  class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                                  id="location" value="{{ old('location')}}" required></textarea>

                                        @if ($errors->has('location'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('location')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Transport Providers</h4>

                    <div class="mb-5">

                        <form action="{{ route('cooperative.logistics.transporters') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="csv" />
                            <button type="submit"
                                class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.transporters') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="xlsx" />
                            <button type="submit"
                                class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.transporters') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="pdf" />
                            <button type="submit"
                                class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transporters as $transporter)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.logistics.transporters.show', $transporter->id) }}">{{ $transporter->name }}</a>
                                    </td>
                                    <td>{{ $transporter->phone_number }}</td>
                                    <td>{{ $transporter->location }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$transporter->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$transporter->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$transporter->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$transporter->id}}">
                                                            Edit {{$transporter->name}}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.logistics.transporters.update', $transporter->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="name_edit_{{$transporter->id}}">Name</label>
                                                                    <input type="text" name="name_edit"
                                                                           class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                           id="name_edit_{{$transporter->id}}"
                                                                           placeholder="Van"
                                                                           value="{{ $transporter->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="phone_number_edit_{{$transporter->id}}">Phone
                                                                        Number</label>
                                                                    <input type="text" name="phone_number_edit"
                                                                           class="form-control {{ $errors->has('phone_number_edit') ? ' is-invalid' : '' }}"
                                                                           id="phone_number_edit_{{$transporter->id}}"
                                                                           value="{{ $transporter->phone_number }}"
                                                                           required>
                                                                    @if ($errors->has('phone_number_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('phone_number_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="location_{{$transporter->id}}">Location</label>
                                                                    <textarea name="location_edit"
                                                                              class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                              id="location_{{$transporter->id}}"
                                                                              placeholder="Van"
                                                                              value="{{ $transporter->name }}"
                                                                              required>{{ $transporter->location }}</textarea>
                                                                    @if ($errors->has('location_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('location_edit')  }}</strong>
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
