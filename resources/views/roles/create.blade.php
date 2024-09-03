@extends('layouts.base')

@section('content')
@include('includes.breadcrumb', [
    'title' => 'Создание роли', 
    'route' => 'roles.create', 
    'breadcrumbs' => 'Роли',
])
@endsection
