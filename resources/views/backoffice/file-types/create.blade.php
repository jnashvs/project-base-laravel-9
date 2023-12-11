@extends('backoffice.layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.file-types') }}">File Types</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create File Type</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">File Type Management</h5>
    </nav>
@stop

@section('content')

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header px-3">
                <h5 class="mb-0">{{ __('Add File Type') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">
                <filetypes-component cancelurl="{{ route('admin.file-types') }}"></filetypes-component>
            </div>
        </div>
    </div>

@stop