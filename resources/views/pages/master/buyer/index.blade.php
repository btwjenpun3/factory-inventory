@extends('adminlte::page')

@section('content_header')
    <h1>Master Buyer</h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Master Item">
        <table class="table table-striped table-hover responsive" id="buyer">
            <thead class="thead-dark">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
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
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    }
                ]
            });
        });
    </script>
@endsection
