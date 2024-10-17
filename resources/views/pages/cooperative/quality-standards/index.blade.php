@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addStandardAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addUnitAccordion"><span
                                class="mdi mdi-plus"></span>Add Standard
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addStandardAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add Standard</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.quality-standard.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="stdName">Standard Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="stdName" placeholder="Good" value="{{ old('name')}}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
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
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('cooperative.quality-standard.download', 'csv') }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('cooperative.quality-standard.download','xlsx') }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <a class="btn btn-sm btn-success float-right text-white"
                       href="{{ route('cooperative.quality-standard.download', 'pdf') }}"
                       style="margin-right: -8px!important;">
                        <i class="mdi mdi-download"></i> PDF
                    </a>

                    <h4 class="card-title">Registered Collection Quality Standards</h4>
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
                            @foreach($standards as $key => $std)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$std->name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#editModal_{{$std->id}}"><span
                                                    class="mdi mdi-file-edit"></span></button>

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$std->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$std->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$std->id}}">
                                                            Edit {{$std->name}} Standard</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.quality-standard.edit', $std->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="name_edit_{{$std->id}}">Name</label>
                                                                    <input type="text" name="name_edit"
                                                                           class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                           id="name_edit_{{$std->id}}"
                                                                           placeholder="AA"
                                                                           value="{{ $std->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
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
