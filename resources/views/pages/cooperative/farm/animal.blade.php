@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['create']))
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


                            <form action="{{ route('cooperative.animal.add') }}" method="post">
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
                                               id="animal_type" placeholder="Sheep" value="{{ old('animal_type')}}"
                                               required>

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


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer_id">Farmer</label>
                                        <select name="farmer_id" id="farmer_id"
                                                class=" form-control select2bs4 {{ $errors->has('farmer_id') ? ' is-invalid' : '' }}">
                                            @foreach($farmers as $user)
                                                <option value="{{$user->id}}">
                                                    {{ ucwords( strtolower($user->first_name).' '.strtolower($user->other_names)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer_id'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('farmer_id')  }}</strong>
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
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                        href="{{ route('cooperative.farm.animals.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                        href="{{ route('cooperative.farm.animals.download','xlsx') }}"
                        style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                        href="{{ route('cooperative.farm.animals.download', 'pdf') }}"
                        style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Approved Livestock/Poultry</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Tag</th>
                                <th>Breed</th>
                                <th>Farmer</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($animals as $key => $cow)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$cow->name }}</td>
                                    <td>{{ ucwords(strtolower($cow->animal_type)) }}</td>
                                    <td>{{$cow->tag_name }}</td>
                                    <td>{{$cow->breed->name }}</td>
                                    <td>{{ ucwords(strtolower($cow->farmer->user->first_name).' '.strtolower($cow->farmer->user->other_names)) }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$cow->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$cow->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$cow->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$cow->id}}">
                                                            Edit {{$cow->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.animal.edit', $cow->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="edit_name_{{$cow->id}}">
                                                                        Name</label>
                                                                    <input type="text" name="edit_name"
                                                                           class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                           id="edit_name_{{$cow->id}}"
                                                                           value="{{ $cow->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_animal_type_{{$cow->id}}">Animal
                                                                        Type</label>
                                                                    <input type="text" name="edit_animal_type"
                                                                           class="form-control {{ $errors->has('edit_animal_type') ? ' is-invalid' : '' }}"
                                                                           id="edit_animal_type_{{$cow->id}}"
                                                                           placeholder="Sheep"
                                                                           value="{{ $cow->animal_type}}" required>

                                                                    @if ($errors->has('edit_animal_type'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_animal_type')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="edit_tag_name_{{$cow->id}}">Tag
                                                                        Name</label>
                                                                    <input type="text" name="edit_tag_name"
                                                                           class="form-control {{ $errors->has('edit_tag_name') ? ' is-invalid' : '' }}"
                                                                           id="edit_tag_name_{{$cow->id}}"
                                                                           value="{{ $cow->tag_name}}">

                                                                    @if ($errors->has('edit_tag_name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_tag_name')  }}</strong>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="edit_breed_id_{{$cow->id}}">Breed</label>
                                                                    <select name="edit_breed_id"
                                                                            id="edit_breed_id_{{$cow->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_breed_id') ? ' is-invalid' : '' }}">
                                                                        @foreach($breeds as $breed)
                                                                            <option value="{{$breed->id}}" {{$breed->id == $cow->breed_id ? 'selected': '' }}> {{ $breed->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_breed_id'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_breed_id')  }}</strong>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="farmer_id_{{$cow->id}}">Farmer</label>
                                                                    <select name="edit_farmer_id"
                                                                            id="farmer_id_{{$cow->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_farmer_id') ? ' is-invalid' : '' }}">
                                                                        @foreach($farmers as $user)
                                                                            <option value="{{$user->id}}" {{ $user->id == $cow->farmer_id ? 'selected' : ''  }}>
                                                                                {{ ucwords( strtolower($user->first_name).' '.strtolower($user->other_names)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farmer_id'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_farmer_id')  }}</strong>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save
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

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Livestock/Poultry</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Tag</th>
                                <th>Breed</th>
                                <th>Farmer</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($other_animals as $key => $cow)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$cow->name }}</td>
                                    <td>{{ ucwords(strtolower($cow->animal_type)) }}</td>
                                    <td>{{$cow->tag_name }}</td>
                                    <td>{{$cow->breed->name }}</td>
                                    <td>{{ ucwords(strtolower($cow->farmer->user->first_name).' '.strtolower($cow->farmer->user->other_names)) }}</td>
                                    <td>
                                        @if($cow->approval_status == \App\Cow::APPROVAL_STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending</badge>
                                        @elseif($cow->approval_status == \App\Cow::APPROVAL_STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved</badge>
                                        @elseif($cow->approval_status == \App\Cow::APPROVAL_STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected</badge>
                                        @endif
                                    </td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farm Management']['livestock_poultry'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModalOther_{{$cow->id}}"><span
                                                        class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModalOther_{{$cow->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabelOther_{{$cow->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabelOther_{{$cow->id}}">
                                                            Edit {{$cow->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.animal.edit', $cow->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="other_edit_name_other_{{$cow->id}}">
                                                                        Name</label>
                                                                    <input type="text" name="edit_name"
                                                                           class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                           id="other_edit_name_{{$cow->id}}"
                                                                           value="{{ $cow->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="other_edit_animal_type_{{$cow->id}}">Animal
                                                                        Type</label>
                                                                    <input type="text" name="edit_animal_type"
                                                                           class="form-control {{ $errors->has('edit_animal_type') ? ' is-invalid' : '' }}"
                                                                           id="other_edit_animal_type_{{$cow->id}}"
                                                                           placeholder="Sheep"
                                                                           value="{{ $cow->animal_type}}" required>

                                                                    @if ($errors->has('edit_animal_type'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_animal_type')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="other_edit_tag_name_{{$cow->id}}">Tag
                                                                        Name</label>
                                                                    <input type="text" name="edit_tag_name"
                                                                           class="form-control {{ $errors->has('edit_tag_name') ? ' is-invalid' : '' }}"
                                                                           id="other_edit_tag_name_{{$cow->id}}"
                                                                           value="{{ $cow->tag_name}}">

                                                                    @if ($errors->has('edit_tag_name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_tag_name')  }}</strong>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="other_edit_breed_id_{{$cow->id}}">Breed</label>
                                                                    <select name="edit_breed_id"
                                                                            id="other_edit_breed_id_{{$cow->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_breed_id') ? ' is-invalid' : '' }}">
                                                                        @foreach($breeds as $breed)
                                                                            <option value="{{$breed->id}}" {{$breed->id == $cow->breed_id ? 'selected': '' }}> {{ $breed->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_breed_id'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_breed_id')  }}</strong>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="other_farmer_id_{{$cow->id}}">Farmer</label>
                                                                    <select name="edit_farmer_id"
                                                                            id="other_farmer_id_{{$cow->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_farmer_id') ? ' is-invalid' : '' }}">
                                                                        @foreach($farmers as $user)
                                                                            <option value="{{$user->id}}" {{ $user->id == $cow->farmer_id ? 'selected' : ''  }}>
                                                                                {{ ucwords( strtolower($user->first_name).' '.strtolower($user->other_names)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farmer_id'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_farmer_id')  }}</strong>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_approval_status_{{$cow->id}}">Approval
                                                                        Status</label>
                                                                    <select name="edit_approval_status"
                                                                            id="edit_approval_status_{{$cow->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_approval_status') ? ' is-invalid' : '' }}">

                                                                        <option value="{{ \App\Cow::APPROVAL_STATUS_REJECTED }}" {{ $cow->approval_status == \App\Cow::APPROVAL_STATUS_REJECTED ? 'selected' : ''  }}>
                                                                            Rejected
                                                                        </option>

                                                                        <option value="{{ \App\Cow::APPROVAL_STATUS_APPROVED }}" {{ $cow->approval_status == \App\Cow::APPROVAL_STATUS_APPROVED ? 'selected' : ''  }}>
                                                                            Approved
                                                                        </option>
                                                                        <option value="{{ \App\Cow::APPROVAL_STATUS_PENDING }}" {{ $cow->approval_status == \App\Cow::APPROVAL_STATUS_PENDING ? 'selected' : ''  }}>
                                                                            Pending
                                                                        </option>
                                                                    </select>
                                                                    @if ($errors->has('edit_farmer_id'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_farmer_id')  }}</strong>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save
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
