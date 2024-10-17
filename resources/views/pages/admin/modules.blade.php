@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addModule"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addModule">
                        <span class="mdi mdi-plus"></span>Add Module
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addModule">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add Module</h4>
                            </div>
                        </div>


                        <form action="{{ route('module.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="name">Module</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="name" placeholder="Configuration" value="{{ old('name')}}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

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
                                                    <button type="button" class="close" data-dismiss="modal"
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
                                                            <input type="text" name="item" class="form-control"
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
                                    <input type="hidden" name="moduleSubmodules" class="form-control"
                                           id="moduleSubmodules"
                                           value="{{ old('moduleSubmodules')}}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <label for="moduleBtn"></label>
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
                    <h4 class="card-title">Registered Modules</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($modules as $key => $module)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$module->name }}</td>
                                    <td>
                                        <form action="{{ route('sub-modules') }}" method="get">
                                            <input type="hidden" name="module" value="{{$module->id}}">
                                            <button type="button" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal"
                                                    data-target="#editModal_{{$module->id}}"><span
                                                        class="mdi mdi-file-edit"></span>
                                            </button>

                                            <button type="submit" class="btn btn-info btn-rounded btn-sm" data-toggle="modal">
                                                <span class="mdi mdi-eye"></span>
                                            </button>

                                            {{--  modals edit start--}}
                                            <div class="modal fade" id="editModal_{{$module->id}}" tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="modalLabel_{{$module->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$module->id}}">
                                                                Edit {{$module->name}} Module</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('module.edit', $module->id)}}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="editName{{$module->id}}">Name</label>
                                                                        <input type="text" name="edit_name"
                                                                               class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                               id="editName{{$module->id}}"
                                                                               placeholder="Manager"
                                                                               value="{{ $module->name }}" required>
                                                                        @if ($errors->has('edit_name'))
                                                                            <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_name')  }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        {{--  modal end   --}}
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
