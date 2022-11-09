@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 h4">Admin panel</div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>Id</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Storage in GB</td>
                                    <td>Change</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->max_storage_gb}} GB</td>
                                        <td>
                                            <button onclick="setModalData({{$user->id}},{{$user->max_storage_gb}})" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editStorageModal">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="modal fade" id="editStorageModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change available storage</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body table-responsive">
                                        <form id="admin-modal-form" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <label for="max_storage">Enter new storage size in GB:</label>
                                            <input type="number" step="0.01" class="form-control" id="max_storage" name="max_storage">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success" onclick="submitModalForm()">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function setModalData(id,value){
            document.getElementById('admin-modal-form').action='/admin/'+id+'/update';
            document.getElementById('max_storage').value=value;
        }

        function submitModalForm(){
            document.getElementById('admin-modal-form').submit();
        }
    </script>
@endsection
