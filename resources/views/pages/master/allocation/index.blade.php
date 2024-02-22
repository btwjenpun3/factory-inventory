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
            <x-adminlte-card class="mt-4" title="Create Master Allocation">
                <x-adminlte-input id="allocation" name="allocation" label="Allocation Name" />
                <x-adminlte-select id="kind" name="kind" label="Kind">
                    <option value="Accessories">Accessories</option>
                    <option value="Fabric">Fabric</option>
                </x-adminlte-select>
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="store()" />
            </x-adminlte-card>
        </div>
    </div>

    <div id="edit_success"></div>

    <x-adminlte-card class="mt-4" title="Master Allocation">
        <table class="table table-striped table-hover responsive" id="master_rak">
            <thead class="thead-dark">
                <tr>
                    <th>Allocation Name</th>
                    <th>Kind</th>
                    <th>Kind Code</th>
                    @if (Gate::check('edit-master-allocation') || Gate::check('delete-master-allocation'))
                        <th>#</th>
                    @endif
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit Master Allocation" theme="primary" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4" title="Edit Master Allocation">
                <x-adminlte-input id="edit_allocation" name="allocation" label="Allocation Name" />
                <x-adminlte-select id="edit_kind" name="kind" label="Kind">
                    <option value="Accessories">Accessories</option>
                    <option value="Fabric">Fabric</option>
                </x-adminlte-select>
                <x-adminlte-button id="update" label="Save" theme="primary" />
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#master_rak').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.allocation') }}',
                order: [
                    [0, 'desc']
                ],
                columns: columns
            });
        });
    </script>

    <script>
        var columns = [{
                data: 'rak_name'
            },
            {
                data: 'jenis'
            },
            {
                data: 'kode_jenis'
            }
        ];

        @if (Gate::check('edit-master-allocation') || Gate::check('delete-master-allocation'))
            columns.push({
                data: 'id_rak',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-master-allocation')
                            true
                        @else
                            false
                        @endcan ;
                    var canDelete =
                        @can('delete-master-allocation')
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
                url: '/master/allocation/show/' + id,
                type: 'GET',
                success: function(response) {
                    document.getElementById('edit_allocation').value = response.rak_name;
                    document.getElementById('edit_kind').value = response.jenis;
                },
                error: function(xhr, error, status) {}
            });
        }

        function store() {
            $.ajax({
                url: '/master/allocation/store',
                method: 'POST',
                data: {
                    allocation: $('#edit_allocation').val(),
                    kind: $('#edit_kind').val(),
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
                    $('#master_rak').DataTable().ajax.reload();
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
                url: '/master/allocation/update/' + id,
                method: 'POST',
                data: {
                    allocation: $('#edit_allocation').val(),
                    kind: $('#edit_kind').val(),
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
                    $('#master_rak').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#edit_failed').html(errorMessage);
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
                        url: '/master/allocation/delete/' + id,
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
                            $('#master_rak').DataTable().ajax.reload();
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
