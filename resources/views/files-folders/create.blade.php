@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row h5">Add new</div>
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
                                        <input type="radio" name="radio" value="0" id="upload-file-radio" onclick="showUploadFile()">
                                    </div>
                                    <div class="col-11">
                                        <label for="upload-file-radio">Upload file</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="radio" name="radio" value="2" id="create-folder-radio" onclick="showCreateFolder()">
                                    </div>
                                    <div class="col-11">
                                        <label for="create-folder-radio">Crete folder</label>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="upload-file-container" class="d-none">
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" placeholder="Enter name for file..." name="file_name">
                                    </div>
                                </div>
                            </div>
                            <div id="create-folder-container" class="d-none">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Enter name for folder..." name="folder_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="btn btn-outline-success d-none" id="submit-btn">Add</button>
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
        let fileUploadDiv=document.getElementById('upload-file-container');
        let folderCreateDiv=document.getElementById('create-folder-container');
        let btn=document.getElementById('submit-btn');
        let form=document.getElementById('add-form');

        function showUploadFile(){
            form.action=`/files`;
            btn.classList.remove('d-none');
            if(fileUploadDiv.classList.contains('d-none'))
                fileUploadDiv.classList.remove('d-none');
            if(!folderCreateDiv.classList.contains('d-none'))
                folderCreateDiv.classList.add('d-none');
        }

        function showCreateFolder(){
            form.action=`/folders`;
            btn.classList.remove('d-none');
            if(folderCreateDiv.classList.contains('d-none'))
                folderCreateDiv.classList.remove('d-none');
            if(!fileUploadDiv.classList.contains('d-none'))
                fileUploadDiv.classList.add('d-none');
        }

    </script>
@endsection
