@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 h4">File explorer</div>
                            <div class="col-6">
                                <a href="{{route('add-new')}}" class="btn btn-outline-primary float-end">+Add new</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table-hover table table-borderless" id="table-files">
                            <thead class="border-bottom">
                            <tr>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Open/Download</th>
                                <th>Name</th>
                                <th>Size in KB</th>
                                <th>Type</th>
                                <th>Share</th>
                                <th>Shared list</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $level = 0 ?>
                            @foreach($rootFolders as $folder)
                                <tr id="row{{$folder->id}}">
                                    <td>
                                        <a href="{{route('folders.edit',['folder'=>$folder])}}"
                                           class="text-decoration-none text-dark">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form id="delete-form-{{$folder->id}}"
                                              action="{{route('folders.destroy',['folder'=>$folder])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a style="cursor: pointer;" class="text-decoration-none text-dark"
                                               onclick="deleteFileFolder({{$folder->id}})">
                                                <i class="fa fa-trash fa-2x"></i>
                                            </a>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="text-decoration-none text-dark"
                                           href="{{route('folders.show',['folder'=>$folder])}}">
                                            <i class="fa fa-plus-circle fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button id="load-rest-{{$folder->id}}"
                                                onclick="loadRest({{$folder->id}},{{$level+1}})"
                                                class="border-0 bg-white not-loaded">
                                            <i class="fa fa-angle-right"></i>
                                        </button>
                                        <i class="fa fa-folder"></i> {{$folder->users_name}}
                                    </td>
                                    <td>{{$folder->size_of_folder_in_kb}}</td>
                                    <td>File Folder</td>
                                    <td>
                                        <a href="{{route('folders.set-share-folder',['folder'=>$folder])}}"
                                           class="btn btn-sm btn-warning">
                                            Share folder
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#sharedListModal"
                                                onclick="getSetData({{$folder->id}},'folder')">
                                            See list
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($rootFiles as $file)
                                <tr id="file{{$file->id}}">
                                    <td>
                                        <a href="{{route('files.edit',['file'=>$file])}}"
                                           class="text-decoration-none text-dark">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form id="delete-form-{{$file->id}}"
                                              action="{{route('files.destroy',['file'=>$file])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a style="cursor: pointer;" class="text-decoration-none text-dark"
                                               onclick="deleteFileFolder({{$file->id}})">
                                                <i class="fa fa-trash fa-2x"></i>
                                            </a>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="text-decoration-none text-dark"
                                           href="{{route('download-file',['file'=>$file])}}">
                                            <i class="fa fa-download fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>{{$file->users_name}}.{{$file->extension}}</td>
                                    <td>{{$file->size_in_kb}}</td>
                                    <td>{{$file->extension}} File</td>
                                    <td>
                                        <a href="{{route('files.set-share-file',['file'=>$file])}}"
                                           class="btn btn-sm btn-warning">
                                            Share file
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#sharedListModal"
                                                onclick="getSetData({{$file->id}},'file')">
                                            See list
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @include('files-folders.shared-list-modal')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>let csrf =@json(csrf_token());</script>
    <script src="/js/loadRestFunctions.js"></script>
@endsection

