@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['services'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addvetService"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addvetService"><span class="mdi mdi-plus"></span>Add Vet Service
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addvetService">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Vet Service</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.vet.service.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="service_name">Service Name</label>
                                        <input type="text" name="service_name"
                                               class="form-control {{ $errors->has('service_name') ? ' is-invalid' : '' }}"
                                               id="first_name" placeholder="John" value="{{ old('service_name')}}">

                                        @if ($errors->has('service_name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('service_name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Service Type</label>
                                        <select name="type" id="type"
                                                class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Type---</option>
                                            @foreach(config('enums.vet_service_types')[0] as $type)
                                                <option value="{{$type}}"> {{$type}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('type')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="service_description">Description</label>
                                        <textarea name="service_description"
                                                  class="form-control {{ $errors->has('service_description') ? ' is-invalid' : '' }}"
                                                  id="service_description"
                                                  rows="4">{{ old('service_description')}}</textarea>

                                        @if ($errors->has('service_description'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('service_description')  }}</strong>
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
                    <h4 class="card-title">Vet Services</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $index = 0; @endphp
                            @foreach($vet_services as $service)
                                @if(strtolower($service->type) == strtolower(config('enums.vet_service_types')[0][0]))
                                    <tr>
                                        <td>{{++$index }}</td>
                                        <td>{{$service->name }}</td>
                                        <td>{{$service->description }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Extension Services</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $index = 0; @endphp
                            @foreach($vet_services as $service)
                                @if(strtolower($service->type) == strtolower(config('enums.vet_service_types')[0][1]))
                                    <tr>
                                        <td>{{++$index }}</td>
                                        <td>{{$service->name }}</td>
                                        <td>{{$service->description }}</td>
                                    </tr>
                                @endif
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
