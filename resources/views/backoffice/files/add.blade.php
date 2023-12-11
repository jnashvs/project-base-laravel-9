@extends('backoffice.layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Files</li>
    </ol>
    <h5 class="font-weight-bolder mb-0">Files Manager</h5>
</nav>
@stop

@section('content')
<dropzone-component />
@endsection