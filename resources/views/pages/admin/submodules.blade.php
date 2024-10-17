@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        @if(request()->get('module'))
            <div class="col-lg-6 col-md-12 col-12 grid-margin stretch-card">
                @else
                    <div class="col-12 grid-margin stretch-card">
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="collapse  show">
                                    <div class="row mt-5">
                                        <div class="col-lg-12 grid-margin stretch-card col-12">
                                            <h4>Filter By Module</h4>
                                        </div>
                                    </div>
                                    <form action="{{ route('sub-modules') }}" method="get">
                                        <div class="form-row">
                                            <div class="form-group col-lg-6 col-md-10 col-12">
                                                <label for="module">Module</label>
                                                <select name="module" id="module"
                                                        class=" form-control select2bs4">
                                                    @foreach($modules as $module)
                                                        <option value="{{$module->id}}" {{ request()->get('module')  == $module->id ? 'selected' : '' }}>{{$module->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-lg-6 col-md-10 col-12">
                                                <label for="moduleBtn"></label>
                                                <button type="submit" class="btn btn-primary btn-fw btn-block">Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(request()->get('module'))
                        @php
                            $theModule = \App\SystemModule::findOrFail(request()->get('module'));
                        @endphp
                        <div class="col-lg-6 col-md-12 col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="collapse  show">
                                        <div class="row mt-5">
                                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                                <h4>Add Sub Modules for {{ $theModule->name }}</h4>
                                            </div>
                                        </div>
                                        <form action="{{ route('sub-modules.add') }}" method="post">
                                            @csrf
                                            <div class="form-row">
                                                <div class="form-group col-lg-3 col-md-6 col-12">

                                                    <button type="button" class="mt-4 ml-2 btn btn-info btn-rounded"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Click to add submodules">
                                                        <span data-toggle="modal" data-target="#costModal">Add Sub Modules</span>
                                                    </button>

                                                    {{--  modals edit start--}}
                                                    <div class="modal fade" id="costModal" tabindex="-1" role="dialog"
                                                         aria-labelledby="costModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="costModalLabel">
                                                                        System Sub Modules
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <table class="table table-hover table-striped"
                                                                                   id="submodules">
                                                                                <thead class="thead-dark">
                                                                                <th>Sub modules</th>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="form-row mt-2">
                                                                        <div class="form-group col-12">
                                                                            <label for="item">Sub Module Name</label>
                                                                            <input type="text" name="item"
                                                                                   class="form-control"
                                                                                   id="item" placeholder="Dashboard">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary"
                                                                            onclick="submodulesModal()">
                                                                        Add
                                                                    </button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--  modal end   --}}
                                                    <input type="hidden" name="moduleSubmodules" id="moduleSubmodules"
                                                           class="form-control"
                                                           value="{{ old('moduleSubmodules')}}">
                                                    <input type="hidden" name="module" class="form-control"
                                                           value="{{ request()->get('module')}}">

                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-lg-6 col-md-6 col-12">
                                                    <label for="moduleBtn"></label>
                                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add
                                                        Sub Modules
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Registered Modules</h4>
                            <div class="table-responsive">
                                <table class="table table-hover dt">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Module</th>
                                        <th>Submodule</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($submodules as $key => $submodule)
                                        <tr>
                                            <td> {{++$key}}</td>
                                            <td>{{$submodule->module->name}}</td>
                                            <td>{{$submodule->name}}</td>
                                            <td></td>
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
                    var submodulesObj = []
                    const submodulesModal = () => {
                        const item = $("#item").val()
                        if (item) {
                            const tableBody = document.getElementById('submodules').getElementsByTagName('tbody')[0]

                            const newRow = tableBody.insertRow()
                            const itemCell = newRow.insertCell(0)
                            itemCell.innerHTML = item

                            const obj = {"item": item}
                            submodulesObj.push(obj)

                            $("#moduleSubmodules").val(JSON.stringify(submodulesObj));
                            $("#item").val("");
                        }
                    }
                </script>
    @endpush
