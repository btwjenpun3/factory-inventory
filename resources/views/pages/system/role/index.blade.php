@extends('adminlte::page')

@section('content')
    <div id="edit_success pt-4"></div>
    <div class="pt-4">
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
    </div>
    <x-adminlte-modal id="editModal" title="Edit Role Permissions" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4">
                <div id="role" class="mb-3"></div>
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
                            }).join('');
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
                                data + ')">Edit</button>';
                        }
                    }
                ]
            });
        });

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
                $.ajax({
                    url: '/roles/update/' + roleId,
                    type: 'POST',
                    data: {
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
    </script>
@endsection
