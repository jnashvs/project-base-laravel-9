@extends('backoffice.layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.file-types') }}">File Manager</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Files</li>
    </ol>
    <h5 class="font-weight-bolder mb-0">Files Manager</h5>
</nav>
@stop

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h6>File Types</h6>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn bg-gradient-dark mb-0" href="{{ route('admin.filetypes.show') }}"><i
                                class="fas fa-plus"></i> Add File Types</a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Título
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Directorio</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Extensão
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tamanho
                                    max.</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Criado
                                    Em</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 w-10px">
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($fileTypes->count() == 0)
                            <tr>
                                <td colspan="8">No records.</td>
                            </tr>
                            @endif

                            @foreach ($fileTypes as $item)
                            <tr>
                                <td>
                                    <span class="text-secondary text-xs font-weight-bold">{{ $item->getId()}}</span>
                                </td>
                                <td><span class="text-secondary text-xs font-weight-bold">{{ $item->getTitle() }}</span>
                                </td>
                                <td><span class="text-secondary text-xs font-weight-bold">{{ $item->getDirectory()
                                        }}</span></td>
                                <td><span class="text-secondary text-xs font-weight-bold">{{ $item->getExtensions()
                                        }}</span></td>
                                <td><span class="text-secondary text-xs font-weight-bold">{{ $item->getMaxFileSize()
                                        }}</span></td>
                                <td><span class="text-secondary text-xs font-weight-bold">{{ $item->getCreatedAt()
                                        }}</span></td>
                                <td class="align-middle">
                                    <div class="d-flex">
                                        <a href="{{ route('admin.edit-file-types', $item->getId())}}"
                                            data-bs-toggle="tooltip" data-bs-original-title="Edit File Type">
                                            <i class="fas fa-edit text-secondary"></i>
                                        </a>
                                        @include('backoffice.partials._delete-button', ['route'=>
                                        route('admin.delete-file-types', ['id'=> $item->getId(), 'directory'=>
                                        $item->getDirectory() ])])
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