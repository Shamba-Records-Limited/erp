@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Financial Products']['current_savings'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addSavingAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addSavingAccordion">Saving
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addSavingAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Save</h4>
                                </div>
                            </div>


                            <form method="post" action="{{ route('financial_products.savings.add')}}">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer" required
                                                class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}">
                                            <option value=""> Select Type</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{ $farmer->id }}" {{ $farmer->id == old('farmer') ? 'selected' : '' }}>
                                                    {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('farmer')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="period">Type</label>
                                        <select name="type" id="type" required
                                                class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value=""> Select Type</option>
                                            @foreach($saving_types as $type)
                                                <option value="{{ $type->id }}" {{ $type->id == old('type') ? 'selected' : '' }}>
                                                    {{ $type->type }} ({{$type->period}}Ms)
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="number" name="amount" min="{{ 10 }}"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
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

    @if(has_right_permission(config('enums.system_modules')['Financial Products']['current_savings'], config('enums.system_permissions')['edit']))
        @if($matured_savings->count() > 0)
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                    data-toggle="collapse"
                                    data-target="#withDrawFromSavingAccordion"
                                    aria-expanded="@if ($errors->has('saving_type')) true @else false @endif"
                                    aria-controls="withDrawFromSavingAccordion">Withdraw
                            </button>
                            <div class="collapse @if ($errors->has('saving_type')) show @endif "
                                 id="withDrawFromSavingAccordion">
                                <div class="row mt-5">
                                    <div class="col-lg-12 grid-margin stretch-card col-12">
                                        <h4>Withdraw</h4>
                                    </div>
                                </div>


                                <form method="post" action="{{ route('financial_products.savings.withdraw')}}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-lg-4 col-md-6 col-12">
                                            <label for="w-farmer">Farmer</label>
                                            <select name="farmer" id="w-farmer" required
                                                    class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                                    onchange="getMaturedSavingTypes()">
                                                <option value=""> Select Type</option>
                                                @foreach($farmers as $farmer)
                                                    <option value="{{ $farmer->id }}"> {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('farmer'))
                                                <span class="help-block text-danger">
                                            <strong>{{ $errors->first('farmer')  }}</strong>
                                        </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-4 col-md-6 col-12">
                                            <label for="w-saving_type">Saving Type</label>
                                            <select name="saving_type" id="w-saving_type" required
                                                    class=" form-control form-select {{ $errors->has('saving_type') ? ' is-invalid' : '' }}">
                                            </select>
                                            @if ($errors->has('saving_type'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('saving_type')  }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <button type="submit" class="btn btn-primary btn-fw btn-block">Withdraw
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
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['current_savings'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('download.savings.report', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('download.savings.report', 'xlsx') }}" style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('download.savings.report', env('PDF_FORMAT')) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Savings</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Saving ID</th>
                                <th>Farmer</th>
                                <th>Saving Type</th>
                                <th>Amount</th>
                                <th>Interest Rate</th>
                                <th>Interest</th>
                                <th>Total Amount</th>
                                <th>Start Date</th>
                                <th>Maturity Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_full_amount = 0;
                                $total_interest = 0;
                                $total_loan_amount = 0;
                            @endphp
                            @foreach($savings as $key => $saving)
                                @php
                                    $interest =   ($saving->interest_rate*$saving->amount)/100;
                                    $total_amount = $interest + $saving->amount;
                                    $total_full_amount +=$total_amount;
                                   $total_interest += $interest;
                                   $total_loan_amount += $saving->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('financial_products.savings.statement', $saving->id)}}">{{sprintf("%03d", $saving->id) }} </a>
                                    </td>
                                    <td>{{ucwords(strtolower($saving->first_name .' '.$saving->other_names)) }}</td>
                                    <td>{{$saving->saving_type }}</td>
                                    <td>{{ $currency.' '.number_format($saving->amount) }}</td>
                                    <td>{{$saving->interest_rate.'%' }}</td>
                                    <td>{{ $currency.' '.number_format($interest) }}</td>
                                    <td>{{$currency.' '.number_format($total_amount) }}</td>
                                    <td>{{$saving->date_started }}</td>
                                    <td>{{$saving->maturity_date }}</td>
                                    <td>@if($saving->status == \App\SavingAccount::STATUS_ACTIVE)
                                            <div class="badge badge-success ml-2 text-white"> Active</div>
                                        @else
                                            <div class="badge badge-info ml-2 text-white"> withdrawn</div>
                                        @endif</td>
                                    <td><a href="{{route('financial_products.savings.statement', $saving->id)}}">Installments</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Totals</th>
                                <th colspan="2">{{ $currency.' '.number_format($total_loan_amount) }}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_interest) }}</th>
                                <th colspan="5">{{ $currency.' '.number_format($total_full_amount) }}</th>
                            </tr>
                            </tfoot>
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
    <script>
        const getMaturedSavingTypes = () => {
            $("#w-saving_type").empty();
            const farmerId = $('#w-farmer').val();
            let url = '{{ route('financial_products.matured-savings',":farmerId") }}';
            url = url.replace(':farmerId', farmerId);
            let htmlCode = '';
            axios.post(url).then(res => {
                const data = res.data
                htmlCode += `<option value="">---Select Saving Type---</option>`;
                data.forEach(d => {
                    htmlCode += `<option value="${d.id}">${d.type}</option>`;
                });
                $("#w-saving_type").append(htmlCode);

            }).catch(() => {
                htmlCode += `<option value="">---Select Saving Type---</option>`;
                $("#w-saving_type").append(htmlCode);
            })
        }

    </script>
@endpush
