@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Disease Management']['disease_cases'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCowAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCowAccordion"><span class="mdi mdi-plus"></span>Add Reported Case
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCowAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Reported Cases</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.disease.case.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="disease">Disease</label>
                                        <select name="disease" id="disease"
                                                class=" form-control select2bs4 {{ $errors->has('disease') ? ' is-invalid' : '' }}">
                                            <option value="">---- Select Disease----</option>
                                            @foreach($diseases as $disease)
                                                <option value="{{$disease->id}}"> {{ $disease->name.' ('.$disease->disease_category->name.')' }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('disease'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('disease')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}">
                                            <option value="">---- Select Farmer ----</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->id}}"> {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farmer')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="symptoms">Symptoms</label>
                                        <input type="text" name="symptoms"
                                               class="form-control {{ $errors->has('symptoms') ? ' is-invalid' : '' }}"
                                               id="symptoms" placeholder="Running Nose" value="{{ old('symptoms')}}"
                                               required>

                                        @if ($errors->has('symptoms'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('symptoms')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"
                                                class=" form-control select2bs4 {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                            <option value="">---- Select Status ----</option>
                                            @foreach(config('enums.disease_status')[0] as $status)
                                                <option value="{{$status}}"> {{ $status }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('status')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
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
                    @if(has_right_permission(config('enums.system_modules')['Disease Management']['disease_cases'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.disease.reported_cases.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.disease.reported_cases.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.disease.reported_cases.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Reported Cases</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Disease</th>
                                <th>Symptoms</th>
                                <th>Status</th>
                                <th>Booked</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reported_cases as $key => $case)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($case->farmer->first_name.' '.$case->farmer->other_names)) }}</td>
                                    <td>{{$case->disease->name.' ('.$case->disease->disease_category->name.')' }}</td>
                                    <td>{{$case->symptoms }}</td>
                                    <td>{{$case->status }}</td>
                                    <td> @if($case->booked)
                                            <label class="badge badge-success text-white">Yes</label>
                                        @else
                                            <label class="badge badge-danger">No</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Disease Management']['disease_cases'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-sm btn-info btn-rounded"
                                                    data-toggle="modal" data-target="#editModal_{{$case->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif



                                        @if($case->booked)
                                            @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['bookings'], config('enums.system_permissions')['view']))
                                                <a href="{{ route('cooperative.vet.bookings.show') }}"
                                                   class="btn btn-sm btn-primary btn-rounded text-white">
                                                    View Bookings
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('cooperative.disease.case.book-vet', $case->id) }}"
                                               class="btn btn-sm btn-warning btn-rounded text-white"> Book Vet </a>
                                        @endif

                                        {{-- modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$case->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$case->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$case->id}}">
                                                            Edit Case</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.disease.case.edit', $case->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="edit_disease_{{$case->id}}">Disease</label>
                                                                    <select name="edit_disease"
                                                                            id="edit_disease_{{$case->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_disease') ? ' is-invalid' : '' }}">
                                                                        @foreach($diseases as $disease)
                                                                            <option value="{{$disease->id}}" {{ $case->disease_id == $disease->id ? 'selected' : '' }}> {{ $disease->name.' ('.$disease->disease_category->name.')' }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                    @if ($errors->has('edit_disease'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_disease')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="edit_farmer_{{$case->id}}">Farmer</label>
                                                                    <select name="edit_farmer"
                                                                            id="edit_farmer_{{$case->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_farmer') ? ' is-invalid' : '' }}">
                                                                        @foreach($farmers as $farmer)
                                                                            <option value="{{$farmer->id}}" {{ $case->farmer_id == $farmer->id ? 'selected' : '' }}> {{ ucwords(strtolower($farmer->first_name.' '.$farmer->other_names)) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farmer'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_farmer')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_symptoms_{{$case->id}}">Symptoms</label>
                                                                    <input type="text" name="edit_symptoms"
                                                                           class="form-control {{ $errors->has('edit_symptoms') ? ' is-invalid' : '' }}"
                                                                           id="edit_symptoms_{{$case->id}}"
                                                                           placeholder="Running Nose"
                                                                           value="{{$case->symptoms}}" required>

                                                                    @if ($errors->has('symptoms'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('symptoms')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_status_{{$case->id}}">Status</label>
                                                                    <select name="edit_status"
                                                                            id="edit_status_{{$case->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_status') ? ' is-invalid' : '' }}">edit_
                                                                        @foreach(config('enums.disease_status')[0] as $status)
                                                                            <option value="{{$status}}" {{ $case->status == $status  ? 'selected' : '' }}> {{ $status }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_status'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_status')  }}</strong>
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
                                        {{-- modal end--}}
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
