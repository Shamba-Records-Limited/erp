@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Products</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $key => $product)
                            <tr>
                                <td>{{++$key }}</td>
                                <td><a href="{{route('cooperative-admin.products.detail', $product->id)}}">{{$product->name }}</a></td>
                                <td>{{$product->category_name}}</td>
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
