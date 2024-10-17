@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['valuation'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceValuation"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceValuation">
                            <span class="mdi mdi-plus"></span>Add Valuation
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceValuation">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Valuation</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.valuation.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Farmer---</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->id}}">{{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('farmer')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Type</label>
                                        <input type="text" name="type"
                                               class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                               id="type" placeholder="Valuation A" value="{{ old('type')}}" required>

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="70000" value="{{ old('amount')}}" required>

                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Description</label>
                                        <textarea type="text" name="description"
                                                  class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                  id="description" placeholder="description"
                                                  required> {{ old('description')}} </textarea>

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
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
                    <h4 class="card-title">Valuations Done</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency; @endphp
                            @foreach($valuations as $key => $valuation)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($valuation->farmer->user->first_name.' '.$valuation->farmer->user->other_names)) }}</td>
                                    <td>{{$valuation->type }}</td>
                                    <td>{{$currency.' '.number_format($valuation->amount,2) }}</td>
                                    <td>{{$valuation->description }}</td>

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
