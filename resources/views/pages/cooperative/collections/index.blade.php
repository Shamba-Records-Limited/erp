@extends('layout.master')

@push('plugin-styles')

@endpush


@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['create']))
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right mr-2"
                                data-toggle="collapse" data-target="#addCollection"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCollection"><span class="mdi mdi-plus"></span>Add
                            Collection
                        </button>
                    @endif

                    <div class="collapse @if ($errors->count() > 0) show @endif "
                         id="addCollection">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Collect Produce</h4>
                            </div>
                        </div>

                        <collect-component></collect-component>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h2 class="card-title">Recent Collections</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch No.</th>
                                <th>Collection Id</th>
                                <th>Farmer</th>
                                <th>Member No</th>
                                <th>Produce</th>
                                <th>Quality</th>
                                <th>Quantity</th>
                                <th>Available Quantity</th>
                                <th>Date</th>
                                <th>Agent</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $total_quality = 0; $total_available_quality = 0;@endphp
                            @foreach($recent_collections as $key => $item)
                                @php
                                    $total_quality += $item->quantity;
                                    $total_available_quality += $item->available_quantity;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('cooperative.collections.product.show', [$item->product_id, 'batch_no' => $item->batch_no]) }}"
                                        >
                                            {{ $item->batch_no }}
                                        </a>

                                    </td>
                                    <td>{{ $item->collection_number }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $item->farmer->user->id) }}">{{ ucwords(strtolower($item->farmer->user->first_name.' '.$item->farmer->user->other_names)) }}</a>
                                    </td>
                                    <td>{{$item->farmer->member_no }}</td>
                                    <td>{{$item->product->name }}</td>
                                    <td>{{$item->collection_quality_standard != null ? $item->collection_quality_standard->name : 'Was Good' }}</td>
                                    <td>{{ number_format($item->quantity, 2) }} {{$item->product->unit->name }} </td>
                                    <td>{{ number_format($item->available_quantity, 2) }} {{$item->product->unit->name }} </td>
                                    <td>{{ \Carbon\Carbon::create($item->date_collected)->format('Y-m-d, l').' '.config('enums.collection_time')[$item->collection_time]}}</td>
                                    <td>{{ $item->agent ? $item->agent->first_name : '' }} </td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['edit']))
                                            <button type="button"
                                                    class="btn btn-info btn-sm btn-rounded"
                                                    data-toggle="modal"
                                                    data-target="#edit-{{ $item->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                            <!-- modal edit -->
                                            <div class="modal fade" id="edit-{{$item->id}}"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="editCollectioln"
                                                 aria-modal="true">
                                                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editCollectioln">Edit
                                                                Collection</h5>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
        <span aria-hidden="true"
              class="text-danger">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                  action="{{ route('cooperative.collection.update', $item->id)}}">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <input type="hidden"
                                                                           name="product"
                                                                           value="{{$item->product_id}}">

                                                                    <div class="form-group col-12">
                                                                        <label>Quantity</label>
                                                                        <input type="text"
                                                                               name="quantity"
                                                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                                                               placeholder="10.5"
                                                                               value="{{ $item->quantity }}"
                                                                               required>

                                                                        @if ($errors->has('quantity'))
                                                                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('quantity') }}</strong>
                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label>Date
                                                                            Collected</label>
                                                                        <input type="date"
                                                                               name="date"
                                                                               class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}"
                                                                               value="{{ $item->date_collected }}"
                                                                               required>

                                                                        @if ($errors->has('date'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('date')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="agent_{{$item->id}}">Agents</label>
                                                                        <select name="agent"
                                                                                id="agent_{{$item->id}}"
                                                                                class="form-control select2bs4 {{ $errors->has('agent') ? ' is-invalid' : '' }}">
                                                                            @foreach( $agents as $agent)
                                                                                <option value="{{$agent->id}}">
                                                                                    {{ ucwords(strtolower($agent->first_name.' '.$agent->other_names)) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @if ($errors->has('product'))
                                                                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('product')  }}</strong>
                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label>Comment</label>
                                                                        <textarea type="text"
                                                                                  name="comments"
                                                                                  class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                                                                  placeholder="Description"
                                                                                  value="{{ $item->comments }}">
                        </textarea>

                                                                        @if ($errors->has('comments'))
                                                                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('comments')  }}</strong>
                    </span>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            class="btn btn-primary">
                                                                        Save
                                                                        changes
                                                                    </button>

                                                                    <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ./ modal edit -->
                                        @endif
                                        @if(has_right_permission(config('enums.system_modules')['Collections']['collect'], config('enums.system_permissions')['download']))
                                            <a href="{{route('cooperative.collection.receipt.download',$item->id)}}"
                                               class="btn btn-primary btn-rounded btn-sm">
                                                <span class="mdi mdi-printer"></span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7">Total</th>
                                <th colspan="1">{{ number_format($total_quality,2)  }}</th>
                                <th colspan="4">{{ number_format($total_available_quality,2) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if (request()->product) true @else false @endif"
                            aria-controls="filterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(request()->product) show @endif "
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Collections</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.collections.show') }}"
                              method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_product">Product</label>
                                    <select name="product" id="filter_product"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}"
                                                    {{$product->id == request()->product ? 'selected' : ''}}>
                                                {{$product->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <a href="{{route('cooperative.collections.show') }}"
                                       type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
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
                        <form action="{{ route('cooperative.collections.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collections.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collections.download', 'pdf') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>
                    @endif

                    <h2 class="card-title">Collections</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Buying Price</th>
                                <th>Quantity Supplied</th>
                                <th>Total Value</th>
                                <th>Available Quantity</th>
                                <th>Available Total Value</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_value = 0;
                                $total_available_quantity_value = 0;
                                $available_quantity = 0;
                                $total_quantity = 0;
                            @endphp
                            @foreach($collections as $key => $item)
                                @php
                                    $total_value += $item->total_value;
                                    $total_available_quantity_value += $item->available_quantity_value;
                                    $available_quantity += $item->available_quantity;
                                    $total_quantity += $item->quantity;
                                @endphp
                                <tr @if($item->threshold >=  $item->available_quantity)
                                        class="bg-danger text-white"
                                        @endif>
                                    <td>{{++$key }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{$currency.' '.number_format($item->buying_price) }}</td>
                                    <td>{{ number_format($item->quantity) }} {{$item->unit}}</td>
                                    <td>{{ $currency.' '.number_format($item->total_value, 2)}}</td>
                                    <td>{{ number_format($item->available_quantity) }} {{$item->unit}}</td>
                                    <td>{{ $currency.' '.number_format($item->available_quantity_value, 2)}}</td>
                                    <td>
                                        <a href="{{ route('cooperative.collections.product.show', $item->id) }}"
                                           class="btn btn-sm btn-rounded btn-info">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{number_format($total_quantity,2)}}</th>
                                <th colspan="1">{{$currency.' '.number_format($total_value,2)}}</th>
                                <th colspan="1">{{number_format($available_quantity,2)}}</th>
                                <th colspan="2">{{$currency.' '.number_format($total_available_quantity_value,2)}}</th>
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
