@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Disease Management']['diseases'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCowAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCowAccordion"><span class="mdi mdi-plus"></span>Add Disease
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCowAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Disease</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.disease.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Disease Name</label>
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
                                        <label for="category_id">Category</label>
                                        <select name="category_id" id="category_id"
                                                class=" form-control select2bs4 {{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                                            @foreach($disease_categories as $category)
                                                <option value="{{$category->id}}"> {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category_id'))
                                            <span class="help-block text-danger">
                                                <strong>
                                                    {{ $errors->first('category_id')  }}
                                                </strong>
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
                    <h4 class="card-title">Registered Diseases</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($diseases as $key => $disease)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$disease->name }}</td>
                                    <td>{{$disease->disease_category->name }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Disease Management']['diseases'], config('enums.system_permissions')['edit']))
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#editModal_{{$disease->id}}">
                                            <span class="mdi mdi-file-edit"></span>
                                        </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$disease->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$disease->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$disease->id}}">
                                                            Edit {{$disease->name}} Disease</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.disease.edit', $disease->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="diseaseName{{$disease->id}}">Name</label>
                                                                    <input type="text" name="disease_name"
                                                                           class="form-control {{ $errors->has('disease_name') ? ' is-invalid' : '' }}"
                                                                           id="diseaseName{{$disease->id}}"
                                                                           placeholder="ABC"
                                                                           value="{{ $disease->name }}" required>

                                                                    @if ($errors->has('disease_name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('disease_name')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="disease_category_id{{$disease->id}}">Category</label>
                                                                    <select name="disease_category_id"
                                                                            id="disease_category_id{{$disease->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('disease_category_id') ? ' is-invalid' : '' }}">
                                                                        @foreach($disease_categories as $category)
                                                                            <option value="{{$category->id}}"
                                                                                    {{ $category->id == $disease->disease_category_id ? 'selected' : null }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach

                                                                        @if ($errors->has('disease_category_id'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('disease_category_id')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </select>
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
