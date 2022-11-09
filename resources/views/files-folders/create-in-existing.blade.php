@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row h5">Add new to {{$folder->users_name}}</div>
                    </div>

                    <div class="card-body">
                        @foreach(['folder_name','file_name','file'] as $err)
                            @error($err)
                            <div class="row alert alert-danger text-center">
                                <div class="col-12">{{$message}}</div>
                            </div>
                            @enderror
                        @endforeach

                        <form id="add-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <fieldset class="border p-1">
                                <legend class=" float-none w-auto p-2 h5">Choose type</legend>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="radio" name="radio" value="0" id="file-radio" onclick="showFileAdd()">
                                    </div>
                                    <div class="col-11">
                                        <label for="file-radio">File</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="radio" name="radio" value="1" id="folder-radio" onclick="showFolderAdd()">
                                    </div>
                                    <div class="col-11">
                                        <label for="folder-radio">Folder</label>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="add-file-container" class="d-none">
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" placeholder="Enter name for file..." name="file_name">
                                    </div>
                                </div>
                            </div>
                            <div id="add-folder-container" class="d-none">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Enter name for folder..." name="folder_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none mt-3" id="submit-btn">
                                <div class="col-2">
                                    <button class="btn btn-outline-success">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let fileDiv=document.getElementById('add-file-container');
        let folderDiv=document.getElementById('add-folder-container');
        let btn=document.getElementById('submit-btn');
        let radioFile=document.getElementById('file-radio');
        let radioFolder=document.getElementById('folder-radio');
        let form=document.getElementById('add-form')
        let folderId={{$folder->id}};

        function showFileAdd(){
            form.action='/files/store-to-existing/'+folderId;
            btn.classList.remove('d-none');
            if(fileDiv.classList.contains('d-none'))
                fileDiv.classList.remove('d-none');
            if(!folderDiv.classList.contains('d-none'))
                folderDiv.classList.add('d-none');
        }

        function showFolderAdd(){
            form.action='/folders/store-to-existing/'+folderId;
            btn.classList.remove('d-none');
            if(folderDiv.classList.contains('d-none'))
                folderDiv.classList.remove('d-none');
            if(!fileDiv.classList.contains('d-none'))
                fileDiv.classList.add('d-none');
        }

    </script>
@endsection
