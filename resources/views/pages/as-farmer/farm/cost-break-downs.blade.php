@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addDiseaseCategory"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addDiseaseCategory">
                        <span class="mdi mdi-plus"></span>Add Cost
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addDiseaseCategory">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add a new cost</h4>
                            </div>
                        </div>


                        <form action="{{ route('farmer.farm.tracker.cost-break-down.add', $tracker->id) }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="item">Item</label>
                                    <input type="text" name="item"
                                           class="form-control {{ $errors->has('item') ? ' is-invalid' : '' }}"
                                           id="item" placeholder="Seeds" value="{{ old('item')}}" required>

                                    @if ($errors->has('item'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('item')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="1000" value="{{ old('amount')}}" required>

                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <label for="RouteName"></label>
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
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
                    <h4 class="card-title">Cost breakdown for
                        {{ ucwords( strtolower($tracker->farmer_crop->farmer->user->first_name).' '.strtolower($tracker->farmer_crop->farmer->user->other_names)) }} :
                        {{$tracker->farmer_crop->type == 1  ? ucwords(strtolower($tracker->farmer_crop->crop->variety)) : ucwords(strtolower($tracker->farmer_crop->livestock->breed->name)) }}
                        {{ $tracker->farmer_crop->type == 1 ?  ($tracker->farmer_crop->crop->product_id ? ucwords(strtolower($tracker->farmer_crop->crop->product->name)) : '-') :  ucwords(strtolower($tracker->farmer_crop->livestock->name.', '.$tracker->farmer_crop->livestock->animal_type)) }} :
                        {{ ucwords(strtolower($tracker->stage->name)) }}
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_cost = 0;
                            @endphp
                            @foreach($costing as $key => $cost)
                                @php  $total_cost += $cost->amount; @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$cost->item }}</td>
                                    <td>{{ $currency.' '.number_format($cost->amount, 2) }}</td>
                                    <td>
                                        <form action="{{ route('farmer.farm.tracker.cost-break-down.delete',$cost->id) }}" method="post">
                                            @csrf
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$cost->id}}"><span
                                                        class="mdi mdi-file-edit"></span></button>

                                            <button type="submit" class="btn btn-danger btn-rounded" data-toggle="modal">
                                                <span class="mdi mdi-trash-can"></span>
                                            </button>
                                        </form>


                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$cost->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$cost->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$cost->id}}">
                                                            Edit cost of {{$cost->item}} </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('farmer.farm.tracker.cost-break-down.edit', [$cost->id])}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="item{{$cost->id}}">Name</label>
                                                                    <input type="text" name="item"
                                                                           class="form-control {{ $errors->has('item') ? ' is-invalid' : '' }}"
                                                                           id="item{{$cost->id}}"
                                                                           placeholder="Seed"
                                                                           value="{{ $cost->item }}" required>

                                                                    @if ($errors->has('item'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('item')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="amount{{$cost->id}}">Amount</label>
                                                                    <input type="text" name="amount"
                                                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                                           id="amount{{$cost->id}}"
                                                                           placeholder="1000"
                                                                           value="{{ $cost->amount }}" required>

                                                                    @if ($errors->has('amount'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('amount')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="2">{{ $currency.' '.number_format($total_cost,2)}}</th>
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
