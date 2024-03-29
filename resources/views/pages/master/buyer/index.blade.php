@extends('adminlte::page')

@section('content_header')
    <h1>Master Buyer</h1>
@endsection

@section('content')
    <div class="mb-3">
        <button class="btn-sm btn-success" data-toggle="collapse" data-target="#createCollapse">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>

    <div class="collapse multi-collapse" id="createCollapse">
        <div class="container">
            <div id="success"></div>
            <div id="error"></div>
            <x-adminlte-card class="mt-4" title="Create Master Buyer">
                <x-adminlte-input id="code" name="code" label="Code" />
                <x-adminlte-input id="name" name="name" label="Name" />
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="store()" />
            </x-adminlte-card>
        </div>
    </div>

    <div id="edit_success"></div>

    <x-adminlte-card class="mt-4" title="Master Item">
        <table class="table table-striped table-hover responsive" id="buyer">
            <thead class="thead-dark">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    @if (Gate::check('edit-master-buyer') || Gate::check('delete-master-buyer'))
                        <th>#</th>
                    @endif
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit Master Buyer" theme="primary" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4" title="Edit Master Buyer">
                <x-adminlte-input id="edit_code" name="code" label="Code" />
                <x-adminlte-input id="edit_name" name="name" label="Name" />
                <x-adminlte-button id="update" label="Save" theme="primary" />
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#buyer').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.buyer') }}',
                order: [
                    [0, 'desc']
                ],
                columns: columns
            });
        });
    </script>
    <script>
        var columns = [{
                data: 'code'
            },
            {
                data: 'name'
            }
        ];

        @if (Gate::check('edit-master-buyer') || Gate::check('delete-master-buyer'))
            columns.push({
                data: 'id_buyer',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-master-buyer')
                            true
                        @else
                            false
                        @endcan ;
                    var canDelete =
                        @can('delete-master-buyer')
                            true
                        @else
                            false
                        @endcan ;
                    var editButton = canEdit ?
                        '<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal" onclick="show(' +
                        data + ')">Edit</button>' :
                        '';
                    var deleteButton = canDelete ?
                        '<button class="btn btn-sm btn-danger" onclick="deleteItem(' +
                        data + ')">Delete</button>' :
                        '';
                    return editButton + ' ' + deleteButton;
                }
            });
        @endif
    </script>
    <script>
        var buyerId;

        function show(id) {
            buyerId = id;
            $.ajax({
                url: '/master/buyer/show/' + id,
                type: 'GET',
                success: function(response) {
                    document.getElementById('edit_code').value = response.code;
                    document.getElementById('edit_name').value = response.name;
                },
                error: function(xhr, error, status) {}
            });
        }

        function store() {
            $.ajax({
                url: '/master/buyer/store',
                method: 'POST',
                data: {
                    code: $('#code').val(),
                    name: $('#name').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $('#buyer').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        }

        $('#update').click(function() {
            var id = buyerId;
            $.ajax({
                url: '/master/buyer/update/' + id,
                method: 'POST',
                data: {
                    code: $('#edit_code').val(),
                    name: $('#edit_name').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#editModal').modal('hide');
                    $('#edit_success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $('#buyer').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        });

        function deleteItem(id) {
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are you sure want delete this ?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/master/buyer/delete/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Data succesfully deleted'
                            });
                            $('#buyer').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Failed',
                                'Link deleted failed.',
                                'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
