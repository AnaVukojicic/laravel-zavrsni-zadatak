@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>Folder {{$folder->users_name}}</h4></div>
                    <div class="card-body table-responsive">
                        <table class="table-hover table table-borderless" id="table-files">
                            <thead class="border-bottom">
                            <tr>
                                <th>Open</th>
                                <th>Name</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $level = 0 ?>
                            @foreach($subfolders as $folder)
                                <tr id="row{{$folder->id}}">
                                    <td>
                                        <a class="text-decoration-none text-dark" href="{{route('show-shared',['folder'=>$folder])}}">
                                            <i class="fa fa-plus-circle fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button id="load-rest-{{$folder->id}}"
                                                onclick="loadRestForShared({{$folder->id}},{{$level+1}})"
                                                class="border-0 bg-white not-loaded">
                                            <i class="fa fa-angle-right"></i>
                                        </button>
                                        <i class="fa fa-folder"></i> {{$folder->users_name}}
                                    </td>
                                    <td>File Folder</td>
                                </tr>
                            @endforeach
                            @foreach($subfiles as $file)
                                <tr id="file{{$file->id}}">
                                    <td>
                                        <a class="text-decoration-none text-dark"
                                           href="{{route('download-file',['file'=>$file])}}">
                                            <i class="fa fa-download fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>{{$file->users_name}}.{{$file->extension}}</td>
                                    <td>{{$file->extension}} File</td>
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
@section('scripts')
    <script src="/js/loadForShared.js"></script>
@endsection

