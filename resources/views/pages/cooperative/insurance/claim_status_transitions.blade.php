@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transition for claim for policy
                        <a href="{{ route('cooperative.insurance.subscription.installments', $claim->subscription->id) }}">#{{ sprintf('%03d', $claim->subscription->id) }} </a>
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transitions as $key => $t)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        @if($t->status == \App\InsuranceClaim::STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending</badge>
                                        @elseif($t->status == \App\InsuranceClaim::STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved</badge>
                                        @elseif($t->status == \App\InsuranceClaim::STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected</badge>
                                        @elseif($t->status == \App\InsuranceClaim::STATUS_SETTLED)
                                            <badge class="badge badge-info text-white">Settled</badge>
                                        @endif
                                    </td>
                                    <td> {{$t->comment}}</td>
                                    <td> {{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d') }}</td>
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
