@extends('layouts.app')

@push('plugin-styles')

@endpush

@php
    $currency = Auth::user()->cooperative->currency;
    $total_amount = 0;
    $canDownload = has_right_permission(config('enums.system_modules')['Collections']['bulk_payment'],config('enums.system_permissions')['edit']);
@endphp

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($canDownload)
                        <form action="{{ route('cooperative.collection.bulk-payment-farmers.download',[$batch,'csv']) }}"
                              method="get">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collection.bulk-payment-farmers.download',[$batch,'xlsx']) }}"
                              method="get">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>
                        <form action="{{ route('cooperative.collection.bulk-payment-farmers.download', [$batch,'pdf']) }}"
                              method="get">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>
                    @endif


                    <h2 class="card-title"> Bulk Payment# {{$batch}}</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Member No.</th>
                                <th>Phone</th>
                                <th>Bank</th>
                                <th>Account No.</th>
                                <th>Payment Mode</th>
                                <th>Internal Ref No.</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bulk_payment_farmers as $key => $item)
                                @php
                                    $total_amount += $item->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $item->user_id) }}">
                                            {{ ucwords(strtolower($item->name)) }}
                                        </a>
                                    </td>
                                    <td>{{$item->member_no }}</td>
                                    <td>{{$item->phone_no }}</td>
                                    <td>{{$item->bank.', '.$item->branch }}</td>
                                    <td>{{$item->bank_account }}</td>
                                    <td>{{config('enums.bulk_payment_modes')[$item->mode]}}</td>
                                    <td>{{$item->reference}}</td>
                                    <td>{{$currency.' '.number_format($item->amount,2)}}</td>
                                    <td>
                                        <a href="{{route('farmer-receipt',$item->id)}}"
                                           class="btn btn-primary btn-rounded btn-sm">
                                            <span class="mdi mdi-printer"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>

                            <tr>
                                <th colspan="8">Total</th>
                                <th colspan="2">{{ $currency.' '.number_format($total_amount,2) }}</th>
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
@endpush
