@extends('adminlte::page')

@section('content')
    <div id="create_success" class="pt-4"></div>
    <div id="edit_success"></div>

    <div class="mb-3">
        <button class="btn-sm btn-primary" data-toggle="collapse" data-target="#createCollapse"><i class="fas fa-plus"></i> New
            User</button>
    </div>

    <div class="collapse multi-collapse" id="createCollapse">
        <div class="container">
            <div id="create_failed" class="pt-4"></div>
            <x-adminlte-card class="mt-4" title="Add New User">
                <x-adminlte-input id="create_name" name="create_name" label="Name" igroup-size="sm" />
                <x-adminlte-input id="create_email" name="create_email" label="Email" igroup-size="sm" />
                <x-adminlte-input id="create_password" name="create_password" label="Password" igroup-size="sm" />
                <x-adminlte-select id="create_role" name="create_role" label="Role" igroup-size="sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="create()" />
            </x-adminlte-card>
        </div>
    </div>

    <x-adminlte-card class="mt-4" title="Users for WebApps">
        <p>This is for <code>WebApps</code> user only. Your local <code>Software</code> user <code>didnt</code> affected
            with this.</p>
        <table class="table table-striped table-hover responsive" id="users">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit WebApps User" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4">
                <x-adminlte-input id="name" name="name" label="Name" igroup-size="sm" />
                <x-adminlte-input id="email" name="email" label="Email" igroup-size="sm" />
                <x-adminlte-input type="password" id="password" name="password" label="Password" igroup-size="sm" />
                <x-adminlte-select id="role" name="role" label="Role" igroup-size="sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-button id="update" label="Save" theme="primary" />
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        const token = $('meta[name="csrf-token"]').attr('content');

        var userId;

        $(document).ready(function() {
            $('#users').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.users') }}',
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role.name',
                        name: 'role.name',
                        render: function(data) {
                            if (data == 'admin') {
                                return '<span class="badge badge-danger">' + data + '</span>';
                            } else if (data != 'admin' && data != null) {
                                return '<span class="badge badge-primary">' + data + '</span>';
                            } else {
                                return '<span class="badge badge-secondary">role not found</span>';
                            }
                        }
                    },
                    {
                        data: 'role.name',
                        name: 'role.name',
                        render: function(data, type, full, meta) {
                            if (data === 'admin') {
                                return '<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editModal" onclick="edit(' +
                                    full.id +
                                    ')">Edit</button>';
                            } else {
                                return '<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editModal" onclick="edit(' +
                                    full.id +
                                    ')">Edit</button> <button class="btn btn-xs btn-danger" onclick="deleteUser(' +
                                    full.id + ')">Delete</button>';
                            }

                        }
                    }
                ]
            });
        });

        function create() {
            $.ajax({
                url: '/users/create',
                type: 'POST',
                data: {
                    name: $('#create_name').val(),
                    email: $('#create_email').val(),
                    password: $('#create_password').val(),
                    role_id: $('#create_role').val(),
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#editModal').modal('hide');
                    $('#create_success').html(successMessage);
                    $('#users').DataTable().ajax.reload();
                    document.getElementById('create_name').value = '';
                    document.getElementById('create_email').value = '';
                    document.getElementById('create_password').value = '';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, error, status) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.error;
                    $('#create_failed').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        }

        function edit(id) {
            userId = id;
            $.ajax({
                url: '/users/show/' + userId,
                type: 'GET',
                success: function(response) {
                    document.getElementById('name').value = response.name;
                    document.getElementById('email').value = response.email;
                    document.getElementById('role').value = response.role_id;
                },
                error: function(xhr, error, status) {}
            });
        }

        $(document).ready(function() {
            $('#update').click(function() {
                $.ajax({
                    url: '/users/update/' + userId,
                    type: 'POST',
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        role_id: $('#role').val(),
                        _token: token
                    },
                    success: function(response) {
                        var successMessage = document.createElement(
                            "div");
                        successMessage.className =
                            "alert alert-success";
                        successMessage.textContent = response.success;
                        $('#editModal').modal('hide');
                        $('#edit_success').html(successMessage);
                        $('#users').DataTable().ajax.reload();
                        setTimeout(function() {
                            successMessage.remove();
                        }, 5000);
                    },
                    error: function(xhr, error, status) {
                        var errorMessage = document.createElement(
                            "div");
                        errorMessage.className = "alert alert-danger";
                        errorMessage.textContent = xhr.responseJSON.error;
                        $('#edit_failed').html(errorMessage);
                        setTimeout(function() {
                            errorMessage.remove();
                        }, 5000);
                    }
                });
            });
        });

        function deleteUser(id) {
            $.ajax({
                url: '/users/name/' + id,
                method: 'GET',
                success: function(response) {
                    Swal.fire({
                        title: 'Delete Confirmation',
                        html: 'Are you sure want to delete user <b>' + response.name + '</b>?',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/users/delete/' + id,
                                method: 'DELETE',
                                data: {
                                    _token: token
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Data successfully deleted'
                                    });
                                    $('#users').DataTable().ajax.reload();
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Failed',
                                        'User deletion failed.',
                                        'error');
                                }
                            });
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error',
                        'Failed to retrieve user information.',
                        'error');
                }
            })
        }
    </script>
@endsection
