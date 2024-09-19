@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Профиль',
        'route' => 'users.edit',
        'breadcrumbs' => $user->surname . ' ' . $user->first_name . ' ' . $user->middle_name,
    ])
    @include('users.admin.head')

    @include('users.admin.nav')

    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">

                @include('users.admin.settings')

            </div>

        </div><!--end tab-content-->
    </div><!--end col-->
    </div><!--end row-->
@endsection
