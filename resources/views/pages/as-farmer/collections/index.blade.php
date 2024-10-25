@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right mr-2" data-toggle="collapse"
                            data-target="#addVetItems"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addVetItems"><span class="mdi mdi-plus"></span>Submit Collection
                    </button>

                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addVetItems">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Submit Collections</h4>
                            </div>
                        </div>
                        <form action="{{ route('farmer.collection.add') }}" method="post">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                        <option value=""> {{ '- Select Product -'}}</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}"> {{ $product->name }} ( in {{ $product->unit }}) </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('product'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('product')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="standard_id">Quality Standard</label>
                                    <select name="standard_id" id="standard_id"
                                            class=" form-control select2bs4 {{ $errors->has('standard_id') ? ' is-invalid' : '' }}">
                                        <option value=""> {{ '- Select Product -'}}</option>
                                        @foreach($quality_standards as $qs)
                                            <option value="{{$qs->id}}"> {{ $qs->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('standard_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('standard_id')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" name="quantity"
                                           class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                           id="quantity" placeholder="10.5" value="{{ old('quantity')}}" required>

                                    @if ($errors->has('quantity'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('quantity')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Date Collected</label>
                                    <input type="date" name="date"
                                           class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}"
                                           id="date" placeholder="1000" value="{{ old('date')}}" required>

                                    @if ($errors->has('date'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('date')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="comments">Comments</label>
                                    <textarea type="text" name="comments"
                                              class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              id="comments" placeholder="Description">
                                        {{ old('comments')}}
                                           </textarea>

                                    @if ($errors->has('comments'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('comments')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <label for=""></label>
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Submit</button>
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

{{--                    <a class="btn btn-sm btn-info float-right text-white"--}}
{{--                       href="{{ route('cooperative.collections.download', 'csv') }}">--}}
{{--                        <i class="mdi mdi-download"></i> CSV--}}
{{--                    </a>--}}

{{--                    <a class="btn btn-sm btn-github float-right text-white"--}}
{{--                       href="{{ route('cooperative.collections.download','xlsx') }}"--}}
{{--                       style="margin-right: -5px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> Excel--}}
{{--                    </a>--}}

                    <h2 class="card-title">Collections</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch No.</th>
                                <th>Member No</th>
                                <th>Produce</th>
                                <th>Standard Quality</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th>Date</th>
                                <th>Status</th>
{{--                                <th></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($collections as $key => $item)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $item->batch_no }}</td>
                                    <td>{{$item->farmer->member_no }}</td>
                                    <td>{{$item->product->name }}</td>
                                    <td>{{$item->collection_quality_standard != null ? $item->collection_quality_standard->name : 'Was Good' }}</td>
                                    <td>{{ number_format($item->quantity,2,'.',',') }} {{$item->product->unit->name }}   </td>
                                    <td>{{ number_format($item->available_quantity,2,'.',',') }} {{$item->product->unit->name }}   </td>
                                    <td>{{ \Carbon\Carbon::create($item->date_collected)->format('Y-m-d') }} </td>
                                    <td>
                                        @if($item->submission_status == \App\Collection::SUBMISSION_STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending</badge>
                                        @elseif($item->submission_status == \App\Collection::SUBMISSION_STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved</badge>
                                        @elseif($item->submission_status == \App\Collection::SUBMISSION_STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected</badge>
                                        @endif
                                    </td>
{{--                                    <td>--}}

{{--                                        <button type="button" class="btn btn-primary" data-toggle="modal"--}}
{{--                                                data-target="#print-{{ $item->id}}">Print--}}
{{--                                        </button>--}}
{{--                                    </td>--}}
                                </tr>

                                <!-- modal print -->
                                <div class="modal fade" id="print-{{$item->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="editCollectioln" aria-modal="true">
                                    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                                         role="document">
                                        <div class="modal-content">
                                            <!-- <div class="modal-header">
                                                <h5 class="modal-title" id="editCollectioln">Print Collection Receipt</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" class="text-danger">×</span>
                                                </button>
                                            </div> -->
                                            <div id="reportPrinting" class="modal-body" style="background:white">
                                                <div class="form-row">
                                                    <div align="center" class="form-group col-lg-12 col-md-12 col-12">
                                                        <img src="{{ asset(Auth::user()->cooperative->logo)}}"
                                                             width="70"/>
                                                        <h3 align="center">{{ Auth::user()->cooperative->name}}</h3>
                                                        <h5 align="center">{{ Auth::user()->cooperative->address}}
                                                            , {{ Auth::user()->cooperative->location}}</h5>
                                                        <h4 align="center">Collection Receipt
                                                            <b>#{{$item->collection_number}}</b></h4>
                                                    </div>
                                                    <div align="center" class="col-lg-12 col-md-12 col-12">
                                                        <small>Issued On: {{ now()->format('d M Y')}}</small>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="farmer_id">Farmer ID:</label>
                                                        <l>
                                                            {{ $item->farmer->id_no }}
                                                        </l>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="product">Product:</label>
                                                        <l>
                                                            {{ $item->product->name }}
                                                            (in {{ $item->product->unit->name }})
                                                        </l>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="farmer_id">Farmer Name:</label>
                                                        <l>
                                                            {{ $item->farmer->user->first_name }}
                                                            {{ $item->farmer->user->other_names }}
                                                        </l>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="quantity">Quantity:</label>
                                                        <l>
                                                            {{ $item->quantity }}
                                                        </l>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12">
                                                        <label for="date">Date Collected:</label>
                                                        <l>
                                                            {{ \Carbon\Carbon::create($item->date_collected)->format('d M Y') }}
                                                        </l>
                                                    </div>
                                                    <hr/>
                                                    <small align="center">Disclaimer: This receipt was generated
                                                        digitally by {{ Auth::user()->cooperative->name}} as evidence of
                                                        payment made for collection of farm product mentioned.</small>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" data-dismiss="modal" aria-label="Close"
                                                        class="btn btn-danger">Close
                                                </button>
                                                <button type="button" onClick="printReport()"
                                                        class="btn btn-primary btn-fw">Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./ modal print -->
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
    <script type="text/javascript">
        function printReport() {
            var prtContent = document.getElementById("reportPrinting");
            var WinPrint = window.open();
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.write("<link rel='stylesheet' href='style.css' type='text/css' media='print'/>");
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }

    </script>
@endpush
