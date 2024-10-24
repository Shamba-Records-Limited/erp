@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion">
                            <span class="mdi mdi-plus"></span>Add Produced Product
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Produced Product</h4>
                                </div>
                            </div>

                            @if($errors)
                                <div>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>
                                                 <span class="help-block text-danger">
                                                    <strong>{{ $error }}</strong>
                                                </span>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('cooperative.manufacturing.production.add') }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <label for="product">Product</label>
                                        <select name="product" id="product"
                                                class="form-control form-select {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value=""> {{ '- Select Product -'}}</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" {{$product->id == old('product') ? 'selected' : ''}}> {{ $product->name }}
                                                    (in {{ $product->unit->name }})
                                                </option>
                                            @endforeach

                                            @if ($errors->has('product'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('product')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <label for="quantity"> Quantity Produced</label>
                                        <input type="text" name="quantity"
                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                               id="quantity" placeholder="1"
                                               value="{{ old('quantity')}}">


                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('quantity')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <label for="will_expire">Will Expire</label>
                                        <select name="will_expire" id="will_expire"
                                                class="form-control form-select {{ $errors->has('will_expire') ? ' is-invalid' : '' }}"
                                                onchange="showExpireField()"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums')["will_expire"][0] as $k=>$v)
                                                <option value="{{$k}}" {{$k == old('will_expire') ? 'selected' : ''}}>{{$v}}</option>
                                            @endforeach

                                            @if ($errors->has('will_expire'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('will_expire')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12 d-none" id="expireInput">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" name="expiry_date"
                                               class="form-control {{ $errors->has('expiry_date') ? ' is-invalid' : '' }}"
                                               id="expiry_date" placeholder="1"
                                               value="{{ old('expiry_date')}}">

                                        @if ($errors->has('expiry_date'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('expiry_date')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <label for="store">Store</label>
                                        <select name="store" id="store"
                                                class="form-control form-select {{ $errors->has('store') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($stores as $store)
                                                <option value="{{$store->id}}"> {{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('store'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('store')  }}</strong>
                                                </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <button type="button"
                                                class="btn btn-primary btn-fw btn-block"
                                                onclick="showRawMaterialsSection()">Next>>
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="materials" id="materials">
                                @if ($errors->has('materials'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('materials')  }}</strong>
                                    </span>
                                @endif
                                <div class="mt-2 d-none" id="raw-materials-section">
                                    <hr/>
                                    <div class="form-row">
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="material">Raw Material</label>
                                            <select name="material" id="material"
                                                    class="form-control form-select {{ $errors->has('materials') ? ' is-invalid' : '' }}">
                                                <option value=""></option>
                                                @foreach($materials as $material)
                                                    <option value="{{$material}}"> {{ $material->product_id ? $material->product->name : $material->name }}
                                                        ( in {{ $material->unit->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="raw_quantity">Quantity required</label>
                                            <input type="text" name="raw_quantity"
                                                   class="form-control {{ $errors->has('raw_quantity') ? ' is-invalid' : '' }}"
                                                   id="quantityRequired" placeholder="1"
                                                   value="{{ old('raw_quantity')}}">

                                        </div>
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for=""></label>
                                            <button type="button"
                                                    class="btn btn-primary btn-fw btn-block mt-3"
                                                    onclick="addRawMaterial()">Add Raw Material
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <!-- generated table -->
                                        <div id="generated-table">
                                        </div>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-6 col-12 d-none"
                                         id="submitBtn">
                                        <button type="submit" class="btn btn-info btn-fw btn-block">
                                            Save
                                        </button>
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.manufacturing.production.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.manufacturing.production.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.manufacturing.production.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Produced Products</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Available Quantity</th>
                                <th>Unit Selling Price</th>
                                <th>Value</th>
                                <th>Unit Production Cost</th>
                                <th>Margin</th>
                                <th>Store</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['delete']);
                                $canViewRawMaterials = has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['view']);
                                $currency = Auth::user()->cooperative->currency;
                                $total_selling_price = 0;
                                $total_unit_production_cost = 0;
                                $total_margin =0;
                                $total_value =0;
                                $total_quantity = 0;
                            @endphp
                            @foreach($productions as $key => $prod)

                                @php
                                    $total_selling_price += $prod->final_selling_price;
                                    $total_unit_production_cost += $prod->production_cost;
                                    $total_margin += ($total_selling_price-$total_unit_production_cost);
                                    $total_value += ($prod->final_selling_price * $prod->available_quantity);
                                    $total_quantity += $prod->available_quantity;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$prod->product }} </td>
                                    <td>{{$prod->available_quantity }} {{$prod->units }}</td>
                                    <td>{{$currency}} {{ number_format($prod->final_selling_price)}}</td>
                                    <td>
                                        {{$currency}}
                                        {{ number_format($prod->final_selling_price * $prod->available_quantity)}}
                                    </td>
                                    <td>{{$currency}} {{ number_format($prod->production_cost,2) }}</td>
                                    <td>{{$currency}} {{number_format(($prod->final_selling_price-$prod->production_cost),2) }}</td>
                                    <td>{{$prod->store }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm btn-rounded" href="{{ route('cooperative.manufacturing.production-history', $prod->id) }}">Production
                                            History</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2"> Total</th>
                                <th colspan="1"> {{ number_format($total_quantity) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_selling_price) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_value) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_unit_production_cost,2) }}</th>
                                <th colspan="3"> {{ $currency.' '.number_format($total_margin,2) }}</th>
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
    <script>

      var rawMaterialsDataTable = [];
      var rawMaterialsObj = {};
      var rawMaterialInputFieldData = []
      const showRawMaterialsSection = () => {
        $('#raw-materials-section').removeClass('d-none')
      }

      const addRawMaterial = () => {
        const quantity = $('#quantityRequired').val();
        const rawMaterial = JSON.parse($('#material').val());

        if (quantity === '' || rawMaterial === '') {
          alert('Select Raw material and quantity to proceed')
        } else {
          $('#submitBtn').removeClass('d-none')
          //check if item is in object
          if (!rawMaterialsObj.hasOwnProperty(rawMaterial.id)) {
            rawMaterial["the_quantity"] = Number(quantity)
            rawMaterialsObj[rawMaterial.id] = rawMaterial
            const productName = rawMaterial.product_id ? rawMaterial.product.name : rawMaterial.name
            const tableObject = {
              "name": productName,
              "quantity": Number(quantity),
              "id": rawMaterial.id,
              "units": rawMaterial.unit.name
            }
            rawMaterialsDataTable.push(tableObject)
            rawMaterialsData(rawMaterialsDataTable)
            rawMaterialInputFieldData.push(rawMaterialsObj[rawMaterial.id])
            $('#materials').val(JSON.stringify(rawMaterialInputFieldData))
          }

        }
      }

      const rawMaterialsData = (data) => {

        var div = document.getElementById("generated-table");
        if (div.hasChildNodes() > 0) {
          div.innerHTML = "";
        }
        var table = document.createElement("table");
        table.classList.add('table', 'table-hover', 'table-bordered');
        const row = document.createElement("tr");

        // Create cells for each piece of data
        const idCell = document.createElement("th");
        idCell.textContent = '#';

        //Material  Cell
        const materialCell = document.createElement("th");
        materialCell.textContent = 'Raw Material';

        //quantity Cell
        const quantityCell = document.createElement("th");
        quantityCell.textContent = 'Quantity';

        const actionCell = document.createElement("th");
        actionCell.textContent = '';

        row.appendChild(idCell);
        row.appendChild(materialCell);
        row.appendChild(quantityCell);
        row.appendChild(actionCell);
        table.appendChild(row);

        data.forEach((v, i) => {
          const row = document.createElement("tr");
          // Create cells for each piece of data
          const idCell = document.createElement("td");
          idCell.textContent = ++i;

          const nameCell = document.createElement("td");
          nameCell.textContent = v.name

          const quantityCell = document.createElement("td");
          quantityCell.textContent = v.quantity + ' ' + v.units;

          const actionCell = document.createElement("td")
          const btn = document.createElement('button')
          btn.classList.add('btn', 'btn-sm', 'btn-rounded', 'deleteMaterial', 'p-2', 'btn-danger');
          btn.addEventListener('click', (e) => {
            e.preventDefault()
            removeRawMaterial(v)
          })
          const spanCan = document.createElement("span")
          //  <span class="mdi mdi-trash-can"></span>
          spanCan.classList.add('mdi', 'mdi-trash-can');
          btn.appendChild(spanCan)
          actionCell.appendChild(btn)

          row.appendChild(idCell);
          row.appendChild(nameCell);
          row.appendChild(quantityCell);
          row.appendChild(actionCell);

          table.appendChild(row);
          div.appendChild(table);
        })
      }

      const removeRawMaterial = (objectToDelete) => {
        const indexOfItem = rawMaterialsDataTable.indexOf(objectToDelete)
        rawMaterialsDataTable.splice(indexOfItem, 1)
        const indexOfObjectToDeleteFromForm = rawMaterialInputFieldData.indexOf(
            rawMaterialsObj[rawMaterialsObj.id]);
        rawMaterialInputFieldData.splice(indexOfObjectToDeleteFromForm, 1)
        delete rawMaterialsObj[objectToDelete.id];
        rawMaterialsData(rawMaterialsDataTable)
        $('#materials').val(JSON.stringify(rawMaterialInputFieldData))
      }

      const showExpireField = () => {
        const inputVal = $('#will_expire').val();

        if(inputVal != ''){
          if(Number(inputVal) === 1){
            $('#expireInput').removeClass('d-none')
          }else{
            $('#expireInput').addClass('d-none')
          }

        }else{
          $('#expireInput').addClass('d-none')
        }
      }
    </script>
@endpush
