@extends('adminlte::page')

@section('content')
    <x-adminlte-card class="mt-4" title="Users for WebApps">
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
@endsection

@section('js')
    <script>
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
                        data: 'role_id',
                        name: 'role_id',
                        render: function(data) {
                            if (data === 1) {
                                return '<span class="badge badge-danger">Administrator</span>';
                            } else if (data === 2) {
                                return '<span class="badge badge-primary">Operator</span>';
                            } else {
                                return '<span class="badge badge-secondary">Role not found</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return '<button type="button" class="btn btn-sm btn-outline-primary">Edit</button>';
                        }
                    }
                ]
            });
        });
    </script>
@endsection
