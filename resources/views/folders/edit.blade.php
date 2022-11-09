@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row h5">Edit folder</div>
                    </div>

                    <div class="card-body">
                        @error('folder_name')
                        <div class="row alert alert-danger text-center">
                            <div class="col-12">{{$message}}</div>
                        </div>
                        @enderror

                        <form action="{{route('folders.update',['folder'=>$folder])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-3">
                                <div class="col-10">
                                    <input type="text" class="form-control" placeholder="Enter name for folder..." name="folder_name" value="{{$folder->users_name}}">
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-outline-success float-end">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
