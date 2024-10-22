@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div>Branch detail</div>
<div class="card">
    <div class="card-body">
        <h4 class="card-subtitle">Branch Detail</h4>
        <h3 class="card-title">Branch Name</h3>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#" class="nav-link {{ $tab == 'manager'?'active':'' }}">Manager</a>
            </li>
        </ul>

        @if($tab == 'manager')
        <div>
            <form action="{{route('cooperative-admin.branches.set_manager', $branch->id )}}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <label for="manager_id">Manager</label>
                        <select name="manager_id" id="manager_id" class="form-control select2bs4 {{ $errors->has('manager_id') ? ' is-invalid' : '' }}" required>
                            <option value="">-- Select Manager --</option>
                            @foreach($employees as $employee)
                            <option value="{{$employee->user_id}}" @if($employee->user_id == $branch->manager_id) selected @endif>{{$employee->username}} - {{$employee->first_name}} {{$employee->other_names}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('manager_id'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('manager_id')  }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <button type="submit" class="btn btn-primary btn-fw btn-block">Update Manager</button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush