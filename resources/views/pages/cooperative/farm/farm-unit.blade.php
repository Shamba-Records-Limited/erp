@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Farm Management']['farm_units'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addFarmUnit"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCrop"><span class="mdi mdi-plus"></span>Add Farm Unit
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmUnit">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Farm Unit</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.farm-unit.add') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="unit">Unit name</label>
                                        <input type="text" name="unit"
                                               class="form-control {{ $errors->has('unit') ? ' is-invalid' : '' }}"
                                               placeholder="Sacks per Acre"
                                               id="start_date">

                                        @if ($errors->has('unit'))
                                            <span class="help-block text-danger">
                                        <strong>{{ $errors->first('unit')  }}</strong>
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
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Farm Unit</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency @endphp
                            @foreach($farm_units as $key => $f)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $f->name }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farm Management']['farm_units'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$f->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$f->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$f->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$f->id}}">
                                                            Edit {{$f->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.farm-unit.edit', $f->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="edit_name_{{$f->id}}">Unit Name</label>
                                                                    <input type="text" name="edit_name"
                                                                           class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                           id="edit_name_{{$f->id}}"
                                                                           value="{{ $f->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
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
