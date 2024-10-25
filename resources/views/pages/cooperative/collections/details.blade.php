@extends('layouts.app')

@push('plugin-styles')

@endpush

@php
    $total_quality=0;
    $total_available_quality = 0;
    $user = Auth::user();
@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter Collections
                    </button>
                    <div class="collapse
                         @if(
                            request()->batch_no
                            or request()->farmer
                            or request()->quality
                            or request()->date
                            or request()->agent
                        )
                             show @endif"
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Collections</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.collections.product.show', $productId) }}"
                              method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="farmer">Farmers</label>
                                    <select name="farmer" id="farmer"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($farmers as $farmer)
                                            <option value="{{$farmer->farmer->id}}"
                                                    {{ $farmer->farmer->id == request()->farmer ? 'selected' : ''}}>
                                                {{$farmer->farmer->member_no}}
                                                - {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="batch_no">Batch No.</label>
                                    <input type="text" name="batch_no"
                                           class="form-control"
                                           id="batch_no" placeholder="C20240117"
                                           value="{{ request()->batch_no}}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="quality">Quality Standards</label>
                                    <select name="quality" id="quality"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($quality_stds as $std)
                                            <option value="{{$std->id}}"
                                                    {{ $std->id == request()->quality ? 'selected' : ''}}>
                                                {{ $std->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Date</label>
                                    <input type="text" name="date"
                                           class="form-control"
                                           id="date"
                                           value="{{ request()->date }}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="agent">Agent</label>
                                    <select name="agent" id="agent"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($agents as $agent)
                                            <option value="{{$agent->id}}"
                                                    {{ $agent->id == request()->agent ? 'selected' : ''}}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.collections.product.show', $productId) }}"
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
                        <form action="{{ route('cooperative.collection.product.download',[$productId,'csv']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collection.product.download',[$productId,'xlsx']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>
                        <form action="{{ route('cooperative.collection.product.download',[$productId,'pdf']) }}"
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
                            @foreach($collections as $key => $item)
                                @php
                                    $total_quality += $item->quantity;
                                    $total_available_quality += $item->available_quantity;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('cooperative.collections.product.show', [$productId, 'batch_no' => $item->batch_no]) }}"
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
                                    <td>{{ number_format($item->quantity) }} {{$item->product->unit->name }} </td>
                                    <td>{{ number_format($item->available_quantity) }} {{$item->product->unit->name }} </td>
                                    <td>{{ \Carbon\Carbon::create($item->date_collected)->format('Y-m-d, l').' '.config('enums.collection_time')[$item->collection_time]}} </td>
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
                                                                           value="{{$productId}}">

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
                                                                                class="form-control
                          select2bs4 {{ $errors->has('agent') ? ' is-invalid' : '' }}">
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
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script type="text/javascript">
      dateRangePickerFormats("date");
    </script>
@endpush
