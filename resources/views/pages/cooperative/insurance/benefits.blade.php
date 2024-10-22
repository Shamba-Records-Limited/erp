@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_benefits'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceBenefit"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceBenefit">
                            <span class="mdi mdi-plus"></span>Add Benefit
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceBenefit">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Benefit</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.benefit.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="name" placeholder="Benefit A" value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block" id="submit-btn">
                                            Add
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
                    <h4 class="card-title">Registered Insurance Benefits</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($benefits as $key => $benefit)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$benefit->name }}</td>

                                    {{--                                    <td>--}}
                                    {{--                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"--}}
                                    {{--                                                data-target="#editModal_{{$category->id}}"><span--}}
                                    {{--                                                    class="mdi mdi-file-edit"></span></button>--}}

                                    {{--  modals edit start--}}
                                    {{--                                        <div class="modal fade" id="editModal_{{$category->id}}" tabindex="-1"--}}
                                    {{--                                             role="dialog"--}}
                                    {{--                                             aria-labelledby="modalLabel_{{$category->id}}" aria-hidden="true">--}}
                                    {{--                                            <div class="modal-dialog modal-dialog-centered" role="document">--}}
                                    {{--                                                <div class="modal-content">--}}
                                    {{--                                                    <div class="modal-header">--}}
                                    {{--                                                        <h5 class="modal-title" id="modalLabel_{{$category->id}}">--}}
                                    {{--                                                            Edit {{$category->name}} Category</h5>--}}
                                    {{--                                                        <button type="button" class="close" data-dismiss="modal"--}}
                                    {{--                                                                aria-label="Close">--}}
                                    {{--                                                            <span aria-hidden="true">&times;</span>--}}
                                    {{--                                                        </button>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <form action="{{route('cooperative.disease-category.edit', $category->id)}}"--}}
                                    {{--                                                          method="post">--}}
                                    {{--                                                        <div class="modal-body">--}}
                                    {{--                                                            @csrf--}}
                                    {{--                                                            <div class="form-row">--}}
                                    {{--                                                                <div class="form-group col-12">--}}
                                    {{--                                                                    <label for="categoryName{{$category->id}}">Name</label>--}}
                                    {{--                                                                    <input type="text" name="category_name"--}}
                                    {{--                                                                           class="form-control {{ $errors->has('category_name') ? ' is-invalid' : '' }}"--}}
                                    {{--                                                                           id="categoryName{{$category->id}}"--}}
                                    {{--                                                                           placeholder="ABC"--}}
                                    {{--                                                                           value="{{ $category->name }}" required>--}}

                                    {{--                                                                    @if ($errors->has('category_name'))--}}
                                    {{--                                                                        <span class="help-block text-danger">--}}
                                    {{--                                                                            <strong>{{ $errors->first('category_name')  }}</strong>--}}
                                    {{--                                                                        </span>--}}
                                    {{--                                                                    @endif--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                        <div class="modal-footer">--}}
                                    {{--                                                            <button type="button" class="btn btn-secondary"--}}
                                    {{--                                                                    data-dismiss="modal">Close--}}
                                    {{--                                                            </button>--}}
                                    {{--                                                            <button type="submit" class="btn btn-primary">Save changes--}}
                                    {{--                                                            </button>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </form>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--  modal end   --}}
                                    {{--                                    </td>--}}
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
