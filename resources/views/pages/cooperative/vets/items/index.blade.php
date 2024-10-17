@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['items'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addVetItems"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVetItems"><span class="mdi mdi-plus"></span>Add Vet Items
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addVetItems">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Vet Items</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.vet.item.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="item_name">Item Name</label>
                                        <input type="text" name="item_name"
                                               class="form-control {{ $errors->has('item_name') ? ' is-invalid' : '' }}"
                                               id="first_name" placeholder="Spray" value="{{ old('item_name')}}"
                                               required>

                                        @if ($errors->has('item_name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('item_name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="unit_measure">Unit Measure</label>
                                        <select name="unit_measure" id="unit_measure"
                                                class=" form-control select2bs4 {{ $errors->has('unit_measure') ? ' is-invalid' : '' }}">
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}"> {{ $unit->name }}</option>
                                            @endforeach

                                            @if ($errors->has('unit_measure'))
                                                <span class="help-block text-danger">
                                        <strong>{{ $errors->first('unit_measure')  }}</strong>
                                    </span>
                                            @endif
                                        </select>
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
                                        <label for="buying_price">Unit Buying Price</label>
                                        <input type="text" name="buying_price"
                                               class="form-control {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                               id="buying_price" placeholder="1000" value="{{ old('buying_price')}}"
                                               required>

                                        @if ($errors->has('buying_price'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('buying_price')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="selling_price">Unit Selling Price</label>
                                        <input type="text" name="selling_price"
                                               class="form-control {{ $errors->has('selling_price') ? ' is-invalid' : '' }}"
                                               id="selling_price" placeholder="1200" value="{{ old('selling_price')}}"
                                               required>

                                        @if ($errors->has('selling_price'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('selling_price')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['items'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.vet.items.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.vet.items.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.vet.items.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Vet Items</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>unit Measure</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <th>Available Quantity</th>
                                <th>Sold Quantity</th>
                                <th>Margin</th>
                            </tr>
                            </thead>
                            @php $currency = \Illuminate\Support\Facades\Auth::user()->cooperative->currency @endphp
                            <tbody>
                            @foreach($vet_items as $key => $item)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$item->name }}</td>
                                    <td>{{$item->unit->name }}</td>
                                    <td>{{ $currency }} {{ number_format($item->bp,2,'.',',') }}</td>
                                    <td>{{ $currency }} {{ number_format($item->sp,2,'.',',') }}</td>
                                    <td>{{ number_format($item->quantity,2,'.',',') }} {{$item->unit->name }} </td>
                                    <td>{{ number_format($item->sold_quantity,2,'.',',') }} {{$item->unit->name }} </td>
                                    <td>{{ number_format(($item->sp - $item->bp) ,2,'.',',') }}
                                        per {{$item->unit->name }} </td>
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
