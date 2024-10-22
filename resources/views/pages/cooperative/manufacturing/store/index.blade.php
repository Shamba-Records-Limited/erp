@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addStoreAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addStoreAccordion"><span class="mdi mdi-plus"></span>Add
                            Store
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addStoreAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4> Add a Store</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.manufacturing.store.add') }}"
                                  method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="name" placeholder="ABC" value="{{ old('name')}}">

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('name')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="location">Location</label>
                                        <input type="text" name="location"
                                               class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                               id="location" placeholder="ABC"
                                               value="{{ old('location')}}">

                                        @if ($errors->has('location'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('location')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Add
                                        </button>
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
                    @if(has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{route('manufacturing.store.download','csv')}}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('manufacturing.store.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('manufacturing.store.download', env('PDF_FORMAT')) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Stores</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($stores as $key => $store)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('manufacturing.data-by-store', $store->id) }}">
                                            {{$store->name}}</a>
                                    </td>
                                    <td>{{$store->location}} </td>

                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$store->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$store->id}}"
                                             tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$store->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="modalLabel_{{$store->id}}">
                                                            Edit {{$store->name}}</h5>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('cooperative.manufacturing.store.edit', $store->id) }}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="name_{{$store->id}}">Name</label>
                                                                    <input type="text"
                                                                           name="edit_name"
                                                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                           id="name_{{$store->id}}"
                                                                           value="{{ $store->name }}"
                                                                           required>

                                                                    @if ($errors->has('name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name')  }}</strong>
                                                                        </span>
                                                                    @endif

                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="location_{{$store->id}}">Location</label>
                                                                    <input type="text"
                                                                           name="edit_location"
                                                                           class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                                                           id="location_{{$store->id}}"
                                                                           value="{{ $store->location }}"
                                                                           required>
                                                                    @if ($errors->has('location'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('location')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit"
                                                                    class="btn btn-primary">Save
                                                                changes
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
