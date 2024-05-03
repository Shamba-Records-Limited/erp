@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm" data-toggle="collapse"
                            data-target="#withdrawAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="withdrawAccordion">Withdraw From Wallet
                    </button>
                    <button type="button" class="btn btn-warning btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#depositAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="depositAccordion">Deposit
                    </button>
                    <!-- withdraw -->
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="withdrawAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Withdraw From Wallet</h4>
                            </div>
                        </div>


                        <form action="{{ route('farmer.wallet.transactions.withdraw')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone"
                                           class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                           id="phone" placeholder="254XXXXXXXXX" value="{{ old('phone')}}">

                                    @if ($errors->has('phone'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('phone')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Withdraw</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- ?.end withdraw -->
                    <!-- deposit -->
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="depositAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Deposit to Wallet</h4>
                            </div>
                        </div>


                        <form action="{{ route('farmer.wallet.transactions.deposit')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount"
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
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Deposit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /end deposit -->
                </div>
            </div>
        </div>
    </div>

{{--    type
amount
reference
source
description
phone--}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Transactions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Receipt No</th>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Initiator</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wallet_transactions as $key => $trx)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($trx->amount, 2, '.', ',') }}</td>
                                    <td> {{ $trx->reference }}</td>
                                    <td> {{ \Carbon\Carbon::parse($trx->date)->format('d M, Y') }}</td>
                                    <td> {{ $trx->source }}</td>
                                    @php
                                    $user = \App\User::find($trx->initiator_id);
                                        $names = '';
                                    if($user)
                                    {
                                        $names = ucwords(strtolower($user->first_name.' '.$user->other_names));
                                    }
                                    @endphp
                                    <td> {{ $names }}</td>
                                    <td>
                                        @if($trx->status == 2)
                                            {{ 'Pending' }}
                                        @elseif($trx->status == 1)
                                            {{ 'Success'}}
                                        @elseif($trx->status == intval(0))
                                            {{ 'Processed'}}
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
