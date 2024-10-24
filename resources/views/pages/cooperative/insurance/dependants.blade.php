@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ ucwords(strtolower($subscription->farmer->user->first_name.' '.$subscription->farmer->user->other_names)) }} Subscription Policy No. {{ sprintf('%03d', $subscription->id) }} Dependants</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Relationship</th>
                                <th>ID/Birth certificate no.</th>
                                <th>Date of birth</th>
                                <th>Age</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($subscription->dependants as $key => $d)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ sprintf('%03d', $d->subscription_id).'-'.$d->no }}</td>
                                    <td>{{ ucwords(strtolower($d->name)) }}</td>
                                    <td>
                                        @if($d->relationship == \App\InsuranceDependant::RELATIONSHIP_SPOUSE)
                                            {{ 'Spouse' }}
                                        @elseif($d->relationship == \App\InsuranceDependant::RELATIONSHIP_CHILD)
                                            {{ 'Child' }}
                                        @endif
                                    </td>
                                    <td>{{ $d->idno }}</td>
                                    <td>{{ $d->dob }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->dob)->age }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-rounded btn-sm" data-toggle="modal"
                                                data-target="#editModal_{{$d->id}}">
                                           <span class="mdi mdi-account-edit">Edit</span>
                                        </button>
                                        <div class="modal fade" id="editModal_{{$d->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$d->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$d->id}}">
                                                            Edit Dependant
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.subscription.dependant.edit',$d->id ) }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="name_{{$d->id}}">Dependant Names</label>
                                                                        <input type="text" name="name" id="name_{{$d->id}}" class="form-control" value="{{ $d->name }}">
                                                                        @if ($errors->has('name'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('name')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="relationship_{{$d->id}}">Relationship</label>
                                                                        <select name="relationship" id="relationship_{{$d->id}}"
                                                                                class=" form-control form-select {{ $errors->has('relationship') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Dependant---</option>
                                                                            <option value="1" {{ $d->relationship == 1 ? 'selected' : '' }}>Spouse</option>
                                                                            <option value="2" {{ $d->relationship == 2 ? 'selected' : '' }}>Child</option>
                                                                        </select>
                                                                        @if ($errors->has('relationship'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('relationship')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="idno_{{$d->id}}">ID/Birth certificate No.</label>
                                                                        <input type="text" name="idno" id="idno_{{$d->id}}" class="form-control" value="{{ $d->idno }}">
                                                                        @if ($errors->has('idno'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('idno')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="dob_{{$d->id}}">Date of Birth</label>
                                                                        <input type="date" name="dob" id="dob_{{$d->id}}" class="form-control" value="{{ $d->dob }}">
                                                                        @if ($errors->has('dob'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('dob')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
