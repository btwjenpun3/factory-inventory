@extends('adminlte::page')

@section('content_header')
    <h1>Master Item</h1>
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
            <x-adminlte-card class="mt-4" title="Create Master Item">
                <x-adminlte-select id="code_buyer" name="code_buyer" label="Code Buyer">
                    @foreach ($buyers as $buyer)
                        <option value="{{ $buyer->code }}">{{ $buyer->code }} - {{ $buyer->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-input id="items" name="items" label="Item" />
                <x-adminlte-input id="desc" name="desc" label="Description" />
                <x-adminlte-button type="submit" label="Save" theme="primary" onclick="store()" />
            </x-adminlte-card>
        </div>
    </div>

    <div id="edit_success"></div>

    <x-adminlte-card class="mt-4" title="Master Item">
        <table class="table table-striped table-hover responsive" id="item">
            <thead class="thead-dark">
                <tr>
                    <th>Code Buyer</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Edit Master Item" theme="primary" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4" title="Edit Master Item">
                <x-adminlte-select id="edit_code_buyer" name="code_buyer" label="Code Buyer">
                    @foreach ($buyers as $buyer)
                        <option value="{{ $buyer->code }}">{{ $buyer->code }} - {{ $buyer->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-input id="edit_items" name="items" label="Item" />
                <x-adminlte-input id="edit_desc" name="desc" label="Description" />
                <x-adminlte-button id="update" label="Save" theme="primary" />
            </x-adminlte-card>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        var getItem;

        const token = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {
            $('#item').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.item') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'code_buyer',
                        name: 'code_buyer'
                    },
                    {
                        data: 'items',
                        name: 'items'
                    },
                    {
                        data: 'desc',
                        name: 'desc',
                    },
                    {
                        data: 'id_item',
                        name: 'id_item',
                        render: function(data, type, row) {
                            var getItem = row.id_item;
                            var canEdit =
                                @can('admin-only')
                                    true
                                @else
                                    false
                                @endcan ;
                            var editButton = canEdit ?
                                '<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal" onclick="edit(' +
                                getItem + ')">Edit</button>' :
                                '';
                            var deleteButton = canEdit ?
                                '<button class="btn btn-sm btn-danger" onclick="deleteItem(' +
                                getItem + ')">Delete</button>' :
                                '';
                            return editButton + ' ' + deleteButton;
                        }
                    },
                ]
            });

            $('#update').click(function() {
                $.ajax({
                    url: '/master/item/update/' + getItem,
                    type: 'post',
                    data: {
                        code_buyer: $('#edit_code_buyer').val(),
                        items: $('#edit_items').val(),
                        desc: $('#edit_desc').val(),
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
                        $('#item').DataTable().ajax.reload();
                        setTimeout(function() {
                            successMessage.remove();
                        }, 5000);
                    },
                    error: function(xhr, error, status) {
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
        });

        function store() {
            $.ajax({
                url: '/master/item/store',
                method: 'POST',
                data: {
                    code_buyer: $('#code_buyer').val(),
                    items: $('#items').val(),
                    desc: $('#desc').val(),
                    _token: token
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
                    $('#item').DataTable().ajax.reload();
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

        function edit(item) {
            getItem = item;
            $.ajax({
                url: '/master/item/show/' + getItem,
                type: 'GET',
                success: function(response) {
                    document.getElementById('edit_code_buyer').value = response.code_buyer;
                    document.getElementById('edit_items').value = response.items;
                    document.getElementById('edit_desc').value = response.desc;
                },
                error: function(xhr, error, status) {}
            });
        }

        function deleteItem(item) {
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
                        url: '/master/item/delete/' + item,
                        method: 'DELETE',
                        data: {
                            _token: token
                        },
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Data succesfully deleted'
                            });
                            $('#item').DataTable().ajax.reload();
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
