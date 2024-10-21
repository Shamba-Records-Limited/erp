@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addCowAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addCowAccordion"><span class="mdi mdi-plus"></span>Add Livestock/Poultry
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addCowAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Register Livestock/Poultry</h4>
                            </div>
                        </div>


                        <form action="{{ route('farm.livestock.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="name">Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="name" placeholder="ABC" value="{{ old('name')}}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="animal_type">Livestock/Poultry Type</label>
                                    <input type="text" name="animal_type"
                                           class="form-control {{ $errors->has('animal_type') ? ' is-invalid' : '' }}"
                                           id="animal_type" placeholder="Sheep" value="{{ old('animal_type')}}" required>

                                    @if ($errors->has('animal_type'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('animal_type')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="tag_name">Tag Name</label>
                                    <input type="text" name="tag_name"
                                           class="form-control  {{ $errors->has('tag_name') ? ' is-invalid' : '' }}"
                                           id="tag_name" placeholder="ZD-23" value="{{ old('tag_name')}}">

                                    @if ($errors->has('tag_name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('tag_name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="breed_id">Breed</label>
                                    <select name="breed_id" id="breed_id"
                                            class=" form-control select2bs4 {{ $errors->has('breed_id') ? ' is-invalid' : '' }}">
                                        @foreach($breeds as $breed)
                                            <option value="{{$breed->id}}"> {{ $breed->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('breed_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('breed_id')  }}</strong>
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
                    <h4 class="card-title">Registered Cows</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Tag</th>
                                <th>Breed</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($livestock as $key => $l)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$l->name }}</td>
                                    <td>{{ ucwords(strtolower($l->animal_type)) }}</td>
                                    <td>{{$l->tag_name }}</td>
                                    <td>{{$l->breed->name }}</td>
                                    <td>
                                        @if($l->approval_status == \App\Cow::APPROVAL_STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending</badge>
                                        @elseif($l->approval_status == \App\Cow::APPROVAL_STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved</badge>
                                        @elseif($l->approval_status == \App\Cow::APPROVAL_STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected</badge>
                                        @endif
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
