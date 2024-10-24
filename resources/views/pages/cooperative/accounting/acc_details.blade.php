@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Accounting']['charts_of_account'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addLedger"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addLedgerAccordion"><span class="mdi mdi-plus"></span>Add Ledger
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addLedger">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Ledger</h4>
                                </div>
                            </div>
                            <form action="{{ route('cooperative.accounting.add-ledger') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                        <label for="parent_ledger_id">Parent Ledger</label>
                                        <select name="parent_ledger" id="parent_ledger"
                                                class=" form-control form-select {{ $errors->has('parent_ledger') ? ' is-invalid' : '' }}"
                                                onchange="getNextLedgerAccount()">
                                            <option value="" selected>---Select Parent Ledger---</option>
                                            @foreach($parent_ledgers as $pl)
                                                <option value={{$pl->id}}>{{$pl->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('parent_ledger'))
                                            <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('parent_ledger')  }}</strong>
                                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                        <label for="name">Ledger Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="name" placeholder="ABC" value="{{ old('name')}}" required>
                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('name')  }}</strong>
                                                    </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                        <label for="account_type">Account Type</label>
                                        <select name="account_type" id="account_type"
                                                class=" form-control form-select {{ $errors->has('account_type') ? ' is-invalid' : '' }}">
                                            <option value="">---Name---</option>
                                            <option value="current">Current</option>
                                            <option value="long term">Long Term</option>
                                        </select>

                                        @if ($errors->has('account_type'))
                                            <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('account_type')  }}</strong>
                                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                        <label for="ledger_code">Ledger Code</label>
                                        <input type="text" name="ledger_code"
                                               class="form-control {{ $errors->has('ledger_code') ? ' is-invalid' : '' }}"
                                               id="ledger_code" placeholder="10000"
                                               value="{{ old('ledger_code')}}" readonly required>
                                        @if ($errors->has('ledger_code'))
                                            <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('ledger_code')  }}</strong>
                                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                        <label for="classification">Account Classification</label>
                                        <select name="classification" id="classification"
                                                class=" form-control form-select {{ $errors->has('classification') ? ' is-invalid' : '' }}">
                                            <option value="">---Classification---</option>
                                            <option value="ACCOUNT_RECEIVALBLES">Account Receivables</option>
                                            <option value="ACCOUNT_PAYABLES">Account Payables</option>
                                        </select>

                                        @if ($errors->has('classification'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('classification')  }}</strong>
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
                    @if(has_right_permission(config('enums.system_modules')['Accounting']['charts_of_account'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                        href="{{ route('cooperative.accounting.charts_of_account.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                        href="{{ route('cooperative.accounting.charts_of_account.download','xlsx') }}"
                        style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                        href="{{ route('cooperative.accounting.charts_of_account.download', 'pdf') }}"
                        style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h2 class="card-title">Accounting Details</h2>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-ledgers" role="tabpanel"
                             aria-labelledby="pills-ledgers-tab">
                            <div class="table-responsive">
                                <table class="table table-hover dt">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Account Type</th>
                                        <th>Parent Ledger</th>
                                        <th>Ledger Code</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ledgers as $key => $l)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ucwords($l->name)}}</td>
                                            <td>{{ucwords($l->type)}}</td>
                                            <td>{{ucwords($l->parent_ledger->name)}}</td>
                                            <td>{{ucwords($l->ledger_code)}}</td>
                                            <td>
                                                <form action="{{route('cooperative.accounting.delete-ledger', $l->id)}}"
                                                      method="post">
                                                    @csrf
                                                    @if(has_right_permission(config('enums.system_modules')['Accounting']['charts_of_account'], config('enums.system_permissions')['delete']))
                                                    <button type="button" @if($l->cooperative_id == null )disabled
                                                            @endif class="btn btn-info btn-rounded" data-toggle="modal"
                                                            data-target="#editModal_{{$l->id}}">
                                                        <span class="mdi mdi-file-edit"></span>
                                                    </button>
                                                    @endif

                                                    @if(has_right_permission(config('enums.system_modules')['Accounting']['charts_of_account'], config('enums.system_permissions')['edit']))
                                                    <button type="submit" @if($l->cooperative_id == null )disabled
                                                            @endif class="btn btn-danger btn-rounded">
                                                        <span class="mdi mdi-trash-can-outline"></span>
                                                    </button>
                                                    @endif
                                                </form>

                                                {{--                      modals edit start--}}
                                                <div class="modal fade" id="editModal_{{$l->id}}" tabindex="-1"
                                                     role="dialog"
                                                     aria-labelledby="modalLabel_{{$l->id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalLabel_{{$l->id}}">
                                                                    Edit {{$l->name}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{route('cooperative.accounting.edit-ledger', $l->id)}}"
                                                                  method="post">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="name_edit_{{$l->id}}">Name</label>
                                                                            <input type="text" name="name_edit"
                                                                                   class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                                   id="name_edit_{{$l->id}}"
                                                                                   placeholder="AA"
                                                                                   value="{{ $l->name }}" required>
                                                                            @if ($errors->has('name_edit'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="account_type_edit_{{$l->id}}">Account
                                                                                Type</label>
                                                                            <select name="account_type_edit"
                                                                                    id="account_type_edit_{{$l->id}}"
                                                                                    class="form-control form-select {{ $errors->has('account_type_edit') ? ' is-invalid' : '' }}">
                                                                                <option value="current" {{$l->type == 'current' ? 'selected' : null }}>
                                                                                    Current
                                                                                </option>
                                                                                <option value="long term" {{$l->type == 'long term' ? 'selected' : null }}>
                                                                                    Long Term
                                                                                </option>
                                                                            </select>
                                                                            @if ($errors->has('account_type_edit'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('account_type_edit')  }}</strong>
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
        </div>
    </div>
@endsection

@push('plugin-scripts')

@endpush

@push('custom-scripts')
    <script>

        function getNextLedgerAccount() {
            const ledger_id = $("#parent_ledger").val()
            if (ledger_id) {
                let url = "{{ route('cooperative.accounting.get_the_next_ledger_code', ':ledger_id') }}"
                url = url.replace(':ledger_id', ledger_id);
                axios.post(url).then(res => {
                    $("#ledger_code").val(res.data);

                })
            }
        }
    </script>
@endpush
