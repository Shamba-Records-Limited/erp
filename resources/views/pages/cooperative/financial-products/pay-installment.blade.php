@extends('layout.master')

@push('plugin-styles')
    <style>
        @keyframes bg-color-change {
            0% {
                background-color: #FFEBEE;
            }
            50% {
                background-color: #FFCDD2;
            }
            100% {
                background-color: #EF9A9A;
            }
        }

        .bg-color-range {
            animation: bg-color-change 1s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Repay Loan Installment</h4>
                            </div>
                        </div>


                        <form method="post" action="{{ route('admin.loan.pay-installment')}}">
                            @csrf
                            <div class="form-row">
                                    <input name="installment_id" value="{{ $installment->id }}" hidden/>
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="2000" value="{{ old('amount')}}" required>


                                    <span class="help-block text-danger" id="show-limit">
                                        <strong>{{ $errors->first('amount')  }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-6">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Submit</button>
                                </div>
                            </div>
                            
                        </form>
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
        
    </script>
@endpush
