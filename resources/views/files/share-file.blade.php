@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 h4">Share {{$file->users_name}}.{{$file->extension}}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form action="{{route('files.share-file',['file'=>$file])}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        @if($usersToChoose->isEmpty())
                                            <p class="h5">There is no more users to share file with!</p>
                                        @else
                                            <label for="select-list">Choose users to share with:</label>
                                            <select class="form-control" name="users[]" multiple="multiple" size="3" id="select-list">
                                                @foreach($usersToChoose as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="row @if($usersToChoose->isEmpty()) d-none @endif">
                                    <div class="col-12 mt-1">
                                        <button class="float-end btn btn-outline-success">Share</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

