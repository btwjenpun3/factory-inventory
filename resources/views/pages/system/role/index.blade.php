@extends('adminlte::page')

@section('content')
    <div class="mb-3 pt-4">
        <button class="btn-sm btn-primary" data-toggle="collapse" data-target="#createCollapse"><i class="fas fa-plus"></i> Add
            New Role</button>
    </div>

    <div class="collapse multi-collapse" id="createCollapse">
        <div class="container">
            <div id="create_success"></div>
            <div id="create_failed"></div>
            <x-adminlte-card class="mt-4" title="Add New User">
                <x-adminlte-input id="create_role" name="create_role" label="Role Name" igroup-size="sm" />
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="create()" />
            </x-adminlte-card>
        </div>
    </div>

    <div id="edit_success"></div>

    <x-adminlte-card title="Role Permissions">
        <p>You can create or customize <code>Permissions</code> for your <code>Role</code> from this page.</p>
        <table class="table table-striped table-hover responsive" id="roles">
            <thead class="thead-dark">
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit Role Permissions" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4">
                <x-adminlte-input id="edit_role" name="edit_role" label="Role Name" igroup-size="sm" />
                <hr>
                @foreach ($permissions as $permission)
                    <input type="checkbox" id="permission-{{ $permission->id }}" name="permission-{{ $permission->id }}"
                        value="{{ $permission->id }}">
                    <label for="permission-{{ $permission->id }}"> {{ $permission->permission }}</label><br>
                @endforeach
                <div class="text-right">
                    <x-adminlte-button class="mt-4" id="update" label="Save" theme="primary" />
                </div>
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        var roleId;
        const token = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            $('#roles').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.roles') }}',
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        render: function(data, type, full, meta) {
                            var permissionList = data.map(function(permission) {
                                return '<li>' + permission.permission + '</li>';
                            }).sort().join('');
                            if (permissionList) {
                                return '<ul>' + permissionList + '</ul>';
                            } else {
                                return '<p class="text-danger"><i>Permissions not assigned. Please assign from Edit button.</i></p>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return '<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editModal" onclick="edit(' +
                                data +
                                ')">Edit</button> <button class="btn btn-xs btn-danger" onclick="deleteRole(' +
                                data + ')">Delete</button>';
                        }
                    }
                ]
            });
        });

        function create() {
            $.ajax({
                url: '/roles/create',
                type: 'POST',
                data: {
                    role: $('#create_role').val(),
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#create_success').html(successMessage);
                    $('#roles').DataTable().ajax.reload();
                    document.getElementById('create_role').value = '';
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
            roleId = id;
            $.ajax({
                url: '/roles/show/' + roleId,
                type: 'GET',
                success: function(response) {
                    $('input[type="checkbox"]').prop('checked', false);
                    response.permissions.forEach(function(permission) {
                        $('#permission-' + permission.id).prop('checked', true);
                    });
                    document.getElementById('edit_role').value = response.role.name;
                },
                error: function(xhr, error, status) {}
            });
        }

        $(document).ready(function() {
            $('#update').click(function() {
                var selectedPermissions = [];
                $('input[name^="permission-"]:checked').each(function() {
                    selectedPermissions.push($(this).val());
                });
                var name = $('#edit_role').val();
                $.ajax({
                    url: '/roles/update/' + roleId,
                    type: 'POST',
                    data: {
                        name: name,
                        permissions: selectedPermissions,
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
                        $('#roles').DataTable().ajax.reload();
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

        function deleteRole(id) {
            $.ajax({
                url: '/roles/name/' + id,
                method: 'GET',
                success: function(response) {
                    Swal.fire({
                        title: 'Delete Confirmation',
                        html: 'Are you sure want to delete role <b>' + response.name + '</b>?',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/roles/delete/' + id,
                                method: 'DELETE',
                                data: {
                                    _token: token
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Role successfully deleted'
                                    });
                                    $('#roles').DataTable().ajax.reload();
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Failed',
                                        'Role deletion failed.',
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
