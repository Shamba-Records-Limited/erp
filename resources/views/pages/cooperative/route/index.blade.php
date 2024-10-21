@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['routes'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addRouteAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addRouteAccordion">
                            <span class="mdi mdi-plus"></span>Add Route
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addRouteAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Route</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.route.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="RouteName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="RouteName" placeholder="Nairobi" value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for="RouteName"></label>
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
                    @if(has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.routes.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.routes.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.routes.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Routes</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($routes as $key => $route)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$route->name }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['routes'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded btn-sm" data-toggle="modal"
                                                    data-target="#editModal_{{$route->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                            <a href="{{ route('cooperative.farmers.show', ["route" => $route->id]) }}" class="btn btn-sm btn-primary btn-rounded" >
                                                Farmers
                                            </a>

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$route->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$route->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$route->id}}">
                                                            Edit {{$route->name}} Route</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.route.edit', $route->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="name_edit_{{$route->id}}">Name</label>
                                                                    <input type="text" name="name_edit"
                                                                           class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                           id="name_edit_{{$route->id}}"
                                                                           placeholder="AA"
                                                                           value="{{ $route->name }}" required>
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
