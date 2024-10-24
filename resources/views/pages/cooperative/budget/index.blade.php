@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Accounting']['budget'], config('enums.system_permissions')['create']))
        
        <form method="post" action="{{ route('cooperative.accounting.budget.store') }}">
            @csrf()
            <div class="row">
                <div class="col-lg-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Budget</h4>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="type">Budget Type:</label>
                                        <select id="type" name="type"
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                            value="{{ old('type') }}"
                                            required>
                                            <option value="">-- select --</option>
                                            <option value="MONTHLY" @if($type == 'monthly') selected @endif>Monthly</option>
                                            <option value="QUARTERLY" @if($type == 'quarterly') selected @endif>Quarterly</option>
                                            <option value="YEARLY" @if($type == 'yearly') selected @endif>Yearly</option>
                                        </select>

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year">Budget Year:</label>
                                        <input id="year" name="year"
                                            class="form-control {{ $errors->has('year') ? ' is-invalid' : '' }}"
                                            value="{{ old('year', $year) }}" 
                                            placeholder="2024"
                                            required />

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">.</label>
                                        <button type="submit" 
                                            class="btn btn-primary btn-fw btn-block"
                                            style="padding: 11px 0;">
                                            Update Budget
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hove d">
                                    <thead>
                                        <tr>
                                            <th style="width: 300px;">Account</th>
                                            @foreach($periods as $period)
                                                <th>{{ $period }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Revenue</th>
                                        </tr> 
                                        @foreach($ledgers['Revenue'] as $revenue)
                                            <tr>
                                                <td>{{ $revenue['name'] }}</td>
                                                @foreach($periods as $period)
                                                    <td>
                                                        <input class="form-control" style="width:120px;"
                                                            name="amount[{{ $period }}][{{ $revenue['id'] }}]"
                                                            value="{{ $budgetAmounts[$revenue['id']][$period] }}" />
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="2">Expenses</th>
                                        </tr>
                                        @foreach($ledgers['Expenses'] as $expense)
                                            <tr>
                                                <td>{{ $expense['name'] }}</td>
                                                @foreach($periods as $period)
                                                    <td>
                                                        <input class="form-control" style="width:120px;"
                                                            name="amount[{{ $period }}][{{ $expense['id'] }}]"
                                                            value="{{ $budgetAmounts[$expense['id']][$period] }}" />
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    @endif

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script>
        $(function () {

            function updateBudget(type, year) {
                const url = new URL(window.location);
                const location = `${url.protocol}//${url.host}${url.pathname}?type=${type}&year=${year}`;
                window.location = location;
            }

            $('select#type').on('change', function (e) {
                const type = e.target.value.toLowerCase();
                const year = document.querySelector('input#year').value != '' ? 
                    document.querySelector('input#year').value : 
                    (new Date()).getFullYear();
                updateBudget(type, year);
            });

            $('input#year').on('change', function(e) {
                const type = document.querySelector('select#type').value != '' ? document.querySelector('select#type').value.toLowerCase() : 'monthly';
                const year = e.target.value;
                updateBudget(type, year);
            });            
        });
    </script>
@endpush
