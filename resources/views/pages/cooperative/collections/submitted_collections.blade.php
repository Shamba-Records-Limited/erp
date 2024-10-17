@extends('layout.master')

@push('plugin-styles')

@endpush

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
                            or request()->product
                            or request()->status
                        )
                             show @endif"
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Product</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.submitted.collections') }}"
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
                                                {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}
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
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}"
                                                    {{ $product->id == request()->product ? 'selected' : ''}}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="status">Status</label>
                                    <select name="status" id="status"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach(config('enums.collection_submission_statuses') as $key => $v)
                                            <option value="{{$key}}"
                                                    {{ $key == request()->status ? 'selected' : ''}}>
                                                {{ $v}}
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
                                    <a href="{{route('cooperative.submitted.collections') }}"
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

                    @if(has_right_permission(config('enums.system_modules')['Collections']['submitted_collection'],config('enums.system_permissions')['download']))
                        <form action="{{ route('cooperative.submitted.collection.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.submitted.collection.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>
                        <form action="{{ route('cooperative.submitted.collection.download','pdf') }}"
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


                    <h2 class="card-title">Submitted Collections</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch No.</th>
                                <th>Farmer</th>
                                <th>Member No</th>
                                <th>Produce</th>
                                <th>Standard Quality</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($collections as $key => $item)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $item->batch_no }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $item->farmer->user->id) }}">
                                            {{ ucwords(strtolower($item->farmer->user->first_name.' '.$item->farmer->user->other_names)) }}
                                        </a>
                                    </td>
                                    <td>{{$item->farmer->member_no }}</td>
                                    <td>{{$item->product->name }}</td>
                                    <td>{{$item->collection_quality_standard != null ? $item->collection_quality_standard->name : 'Was Good' }}</td>
                                    <td>{{ number_format($item->available_quantity) }} {{$item->product->unit->name }}   </td>
                                    <td>{{\Carbon\Carbon::create($item->date_collected)->format('Y-m-d, l').' '.config('enums.collection_time')[$item->collection_time]}}</td>
                                    <td>
                                        @if($item->submission_status == \App\Collection::SUBMISSION_STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending
                                            </badge>
                                        @elseif($item->submission_status == \App\Collection::SUBMISSION_STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved
                                            </badge>
                                        @elseif($item->submission_status == \App\Collection::SUBMISSION_STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected
                                            </badge>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($item->submission_status != \App\Collection::SUBMISSION_STATUS_REJECTED) &&
                                            has_right_permission(config('enums.system_modules')['Collections']['submitted_collection'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info"
                                                    data-toggle="modal"
                                                    data-target="#editCollection{{ $item->id}}">
                                                Update
                                            </button>
                                        @endif
                                        <!-- modal edit -->
                                        <div class="modal fade" id="editCollection{{ $item->id}}"
                                             tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="editCollection{{ $item->id}}"
                                             aria-modal="true">
                                            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Status</h5>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true"
                                                                  class="text-danger">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                              action="{{ route('cooperative.submitted.collection.update', $item->id)}}">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="status">Status</label>
                                                                    <select name="status"
                                                                            id="product_{{$item->id}}"
                                                                            class=" form-control
                                                                      select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                                                        <option value="">--Select
                                                                            Status--
                                                                        </option>
                                                                        <option value="{{ \App\Collection::SUBMISSION_STATUS_REJECTED }}" {{ \App\Collection::SUBMISSION_STATUS_REJECTED == $item->submission_status ? 'selected' : '' }}>
                                                                            Reject
                                                                        </option>
                                                                        <option value="{{ \App\Collection::SUBMISSION_STATUS_APPROVED }}" {{ \App\Collection::SUBMISSION_STATUS_APPROVED == $item->submission_status ? 'selected' : '' }}>
                                                                            Approve
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-lg-6 col-md-3 col-12">
                                                                    <button type="submit"
                                                                            class="btn btn-primary btn-fw btn-block">
                                                                        Submit
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ./ modal edit -->
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
    <script>
      dateRangePickerFormats("date");
    </script>
@endpush
