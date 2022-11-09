@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row h5">Edit file</div>
                    </div>

                    <div class="card-body">
                        @error('file_name')
                        <div class="row alert alert-danger text-center">
                            <div class="col-12">{{$message}}</div>
                        </div>
                        @enderror

                        <form action="{{route('files.update',['file'=>$file])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-3">
                                <div class="col-10">
                                    <input type="text" class="form-control" placeholder="Enter name for file..." name="file_name" value="{{$file->users_name}}">
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
