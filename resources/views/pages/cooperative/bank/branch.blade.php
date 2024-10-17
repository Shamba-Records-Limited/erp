@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addBankBranchAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addBankBranchAccordion"><span class="mdi mdi-plus"></span>Add Bank Branch
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addBankBranchAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Register Bank Branch</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.bank_branch.add') }}" method="post">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bank_id">Bank</label>
                                    <select name="bank_id" id="bank_id"
                                            class=" form-control select2bs4 {{ $errors->has('bank_id') ? ' is-invalid' : '' }}">
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}"> {{ $bank->name }}</option>
                                        @endforeach

                                        @if ($errors->has('bank_id'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('bank_id')  }}</strong>
                                </span>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bankName">Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="bankName" placeholder="ABC" value="{{ old('name')}}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="code">Code</label>
                                    <input type="text" name="code"
                                           class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                           id="code" placeholder="CODEB" value="{{ old('code')}}">

                                    @if ($errors->has('code'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="address">Address</label>
                                    <input type="text" name="address"
                                           class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                           value="{{ old('address')}}" id="address" placeholder="Nairobi, Kenyattah Ave"
                                           required>
                                    @if ($errors->has('address'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('address')  }}</strong>
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
                    <h4 class="card-title">Registered Banks</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Bank</th>
                                <th>Address</th>
                                <th>Code</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bank_branches as $key => $branch)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$branch->name }}</td>
                                    <td>{{$branch->bank->name }}</td>
                                    <td>{{$branch->address }}</td>
                                    <td>{{$branch->code }}</td>
                                    <td></td>
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