@extends('layouts.app')

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Accounting']['accounting_rules'], config('enums.system_permissions')['create']))
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addAccountingRule"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addAccountingRuleAccordion"><span class="mdi mdi-plus"></span>Add Accounting
                        Rule
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addAccountingRule">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add Accounting Rule</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.accounting.rule.add') }}" method="post">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                    <label for="name">Rule Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="name" placeholder="Office Expenses" value="{{ old('name')}}" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                    <label for="debit_ledger">Debit Ledger</label>
                                    <select name="debit_ledger" id="debit_ledger"
                                            class=" form-control select2bs4 {{ $errors->has('debit_ledger') ? ' is-invalid' : '' }}">
                                        <option value="" selected>---Select Debit Ledger---</option>
                                        @foreach($ledgers as $l)
                                            <option value={{$l->id}}>{{$l->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('debit_ledger'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('debit_ledger')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                    <label for="credit_ledger">Credit Ledger</label>
                                    <select name="credit_ledger" id="credit_ledger"
                                            class=" form-control select2bs4 {{ $errors->has('credit_ledger') ? ' is-invalid' : '' }}">
                                        <option value="" selected>---Select Credit Ledger---</option>
                                        @foreach($ledgers as $l)
                                            <option value={{$l->id}}>{{$l->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('credit_ledger'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('credit_ledger')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                    <label for="description">Description</label>
                                    <textarea type="text" name="description"
                                              class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                              id="description"
                                              placeholder="description...">{{ old('description')}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-info btn-fw btn-block">Add</button>
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
        <div class="col-lg-12 col-md-6 col-12 col-sm-3 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Accounting Rules</h2>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Debit Ledger</th>
                                <th>Credit Ledger</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rules as $key => $r)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ucwords($r->name)}}</td>
                                    <td>{{ucwords($r->debit_ledger->name)}}</td>
                                    <td>{{ucwords($r->credit_ledger->name)}}</td>
                                    <td>{{ucwords($r->description)}}</td>
                                    <td>
                                        <form action="{{route('cooperative.accounting.rule.delete', $r->id)}}"
                                              method="post">
                                            @csrf

                                            @if(has_right_permission(config('enums.system_modules')['Accounting']['accounting_rules'], config('enums.system_permissions')['delete']))
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$r->id}}"><span
                                                        class="mdi mdi-file-edit"></span>
                                            </button>
                                            @endif

                                            @if(has_right_permission(config('enums.system_modules')['Accounting']['accounting_rules'], config('enums.system_permissions')['delete']))
                                                <button type="submit" class="btn btn-danger btn-rounded"><span
                                                            class="mdi mdi-trash-can-outline"></span>
                                                </button>
                                            @endif
                                        </form>

                                        {{--                      modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$r->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$r->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$r->id}}">
                                                            Edit {{$r->name}} Rule</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.accounting.rule.edit', $r->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="name">Rule Name</label>
                                                                    <input type="text" name="edit_name"
                                                                           class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                           id="edit_name" placeholder="Office Expenses"
                                                                           value="{{$r->name}}" required>
                                                                    @if ($errors->has('edit_name'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_name')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_debit_ledger_{{$r->id}}">Debit
                                                                        Ledger</label>
                                                                    <select name="edit_debit_ledger"
                                                                            id="edit_debit_ledger_{{$r->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_debit_ledger') ? ' is-invalid' : '' }}">
                                                                        @foreach($ledgers as $l)
                                                                            <option value={{$l->id}}  {{ $r->debit_ledger_id == $l->id ? 'selected' : null }}>{{$l->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_debit_ledger'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_debit_ledger')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_credit_ledger_{{$r->id}}">Credit
                                                                        Ledger</label>
                                                                    <select name="edit_credit_ledger"
                                                                            id="edit_credit_ledger_{{$r->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_credit_ledger') ? ' is-invalid' : '' }}">
                                                                        @foreach($ledgers as $l)
                                                                            <option value={{$l->id}}  {{ $r->credit_ledger_id == $l->id ? 'selected' : null }}>{{$l->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_credit_ledger'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_credit_ledger')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_description{{$r->id}}">Description</label>
                                                                    <textarea type="text" name="edit_description"
                                                                              class="form-control {{ $errors->has('edit_description') ? ' is-invalid' : '' }}"
                                                                              id="edit_description{{$r->id}}"
                                                                              placeholder="description...">{{$r->description}}</textarea>
                                                                    @if ($errors->has('edit_description'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_description')  }}</strong>
                                                                        </span>
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
                                        {{--                      modal end--}}
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
