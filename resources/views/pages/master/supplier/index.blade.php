@extends('adminlte::page')

@section('content_header')
    <h1>Master Supplier</h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Master Supplier">
        <table class="table table-striped table-hover responsive" id="supplier">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Address</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#supplier').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.supplier') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    }
                ]
            });
        });
    </script>
@endsection
