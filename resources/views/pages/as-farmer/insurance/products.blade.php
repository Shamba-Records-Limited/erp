@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Product Premiums</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Premium</th>
                                <th>Interest</th>
                                <th>Benefits</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency @endphp
                            @foreach($products as $key => $product)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->type == \App\InsuranceProduct::TYPE_SERVICE  ? 'Service' : 'Saving' }}</td>
                                    <td>{{ $currency.' '.number_format($product->premium, 2, '.', ',') }}</td>
                                    <td> {{ number_format($product->interest, 2) }} </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#viewModal_{{$product->id}}">
                                            <span class="mdi mdi-file-edit">
                                                view benefits
                                            </span>
                                        </button>
                                        <div class="modal fade" id="viewModal_{{$product->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$product->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$product->id}}">
                                                            View {{$product->name}} Insurance Product Benefits</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <ul class="list-wrapper">
                                                            @foreach($product->benefits as $b)
                                                                <li class="pt-2"> {{$b->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
