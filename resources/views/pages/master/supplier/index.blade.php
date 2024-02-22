@extends('adminlte::page')

@section('content_header')
    <h1>Master Supplier</h1>
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
            <x-adminlte-card class="mt-4" title="Create Master Supplier">
                <x-adminlte-input id="id" name="id" label="ID" />
                <x-adminlte-input id="supplier" name="supplier" label="Supplier" />
                <x-adminlte-input id="address" name="address" label="Address" />
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="store()" />
            </x-adminlte-card>
        </div>
    </div>

    <div id="edit_success"></div>

    <x-adminlte-card class="mt-4" title="Master Supplier">
        <table class="table table-striped table-hover responsive" id="supplier-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Address</th>
                    @if (Gate::check('edit-master-supplier') || Gate::check('delete-master-supplier'))
                        <th>#</th>
                    @endif
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit Master Supplier" theme="primary" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4" title="Edit Master Supplier">
                <x-adminlte-input id="edit_id" name="id" label="ID" />
                <x-adminlte-input id="edit_supplier" name="supplier" label="Supplier" />
                <x-adminlte-input id="edit_address" name="address" label="Address" />
                <x-adminlte-button id="update" label="Save" theme="primary" />
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#supplier-table').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.supplier') }}',
                order: [
                    [0, 'desc']
                ],
                columns: columns
            });
        });
    </script>

    <script>
        var columns = [{
                data: 'id_supplier'
            },
            {
                data: 'supplier'
            },
            {
                data: 'address'
            }
        ];

        @if (Gate::check('edit-master-supplier') || Gate::check('delete-master-supplier'))
            columns.push({
                data: 'id_supplier',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-master-supplier')
                            true
                        @else
                            false
                        @endcan ;
                    var canDelete =
                        @can('delete-master-supplier')
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
        var supplierId;

        function show(id) {
            supplierId = id;
            $.ajax({
                url: '/master/supplier/show/' + id,
                type: 'GET',
                success: function(response) {
                    document.getElementById('edit_id').value = response.id;
                    document.getElementById('edit_supplier').value = response.supplier;
                    document.getElementById('edit_address').value = response.address;
                },
                error: function(xhr, error, status) {}
            });
        }

        function store() {
            $.ajax({
                url: '/master/supplier/store',
                method: 'POST',
                data: {
                    id: $('#id').val(),
                    supplier: $('#supplier').val(),
                    address: $('#address').val(),
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
                    $('#supplier-table').DataTable().ajax.reload();
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
        };

        $('#update').click(function() {
            var id = supplierId;
            $.ajax({
                url: '/master/supplier/update/' + id,
                method: 'POST',
                data: {
                    id: $('#edit_id').val(),
                    supplier: $('#edit_supplier').val(),
                    address: $('#edit_address').val(),
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
                    $('#supplier-table').DataTable().ajax.reload();
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
                        url: '/master/supplier/delete/' + id,
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
                            $('#supplier-table').DataTable().ajax.reload();
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
