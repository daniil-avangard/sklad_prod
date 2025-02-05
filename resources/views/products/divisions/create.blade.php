@extends('layouts.base')

@push('styles-plugins')
    <link href="/plugins/dropify/css/dropify.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Добавление подразделения',
        'route' => 'products.divisions.create',
        'breadcrumbs' => $product,
        'back_route' => 'products.show',
        'back_route_param' => $product,
    ])

    @include('products.info.header')

    {{-- <x-form action="{{ route('products.divisions.addDivision', $product) }}" method="POST"> --}}
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="division_id">Подразделение</label>
                            <select class="form-select" id="division_id" name="division_id">
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>

        <a href="{{ route('products.divisions.addAllDivisions', $product) }}" class="btn btn-primary">Добавить все</a>
    {{-- </x-form> --}}
@endsection
