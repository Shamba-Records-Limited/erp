@extends('layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addFarmerAccordion"
                            aria-expanded="@if ($errors->count() > 0 or request()->from or request()->to )
                             true @else false @endif"
                            aria-controls="addFarmerAccordion">
                        <span class="mdi mdi-plus"></span>Filter Collections
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmerAccordion">
                        <form action="{{ route('cooperative.collections.farmer.view', $farmer_id) }}" method="get">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Filter Details</h6>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="from">From</label>
                                    <input type="date" name="from"
                                           class="form-control {{ $errors->has('From') ? ' is-invalid' : '' }}"
                                           id="from" value="{{ request()->from}}">

                                    @if ($errors->has('from'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('from')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="to">To</label>
                                    <input type="date" name="to"
                                           class="form-control {{ $errors->has('To') ? ' is-invalid' : '' }}"
                                           id="to" value="{{ request()->to}}">

                                    @if ($errors->has('to'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('to')  }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Filter</button>
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
                    @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.collections.farmer.download',['csv',$farmer_id]) }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.collections.farmer.download',['xlsx',$farmer_id]) }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>

                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.collections.farmer.download',['pdf',$farmer_id]) }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Farmer Payment Statements</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch No.</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Units</th>
                                <th>Quality</th>
                                <th>Date Collected</th>
                                <th>Agent</th>
                                <th>Comments</th>
                                <th>Collection No.</th>
                                <th>Available Quantity</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_collected = 0;
                                $total_available = 0;
                            @endphp
                            @foreach($farmer_collections as $key => $collection)
                                @php
                                    $total_collected += $collection->quantity;
                                    $total_available += $collection->available_quantity;
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{$collection->batch_no}}</td>
                                    <td>{{ucwords(strtolower($collection->farmer->user->first_name.' '.$collection->farmer->user->other_names))}}</td>
                                    <td>{{$collection->product->name}}</td>
                                    <td>{{$collection->quantity}}</td>
                                    <td>{{$collection->product->unit->name}}</td>
                                    <td>{{$collection->collection_quality_standard != null ? $collection->collection_quality_standard->name : 'Was Good'}}</td>
                                    <td>{{$collection->date_collected}}</td>
                                    <td>{{$collection->agent != null ? ucwords(strtolower($collection->agent->first_name.' '.$collection->agent->other_names)) : ''}}</td>
                                    <td>{{ $collection->comments }}</td>
                                    <td>{{ $collection->collection_number }}</td>
                                    <td>{{ $collection->available_quantity }}</td>
                                    <td>
                                        <a href="{{route('cooperative.collection.receipt.download',$collection->id)}}"
                                           class="btn btn-primary btn-rounded btn-sm">
                                            <span class="mdi mdi-printer"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="7">{{ number_format($total_collected)}}</th>
                                <th colspan="2">{{ number_format($total_available)}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
