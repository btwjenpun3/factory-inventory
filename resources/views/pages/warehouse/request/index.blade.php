@extends('adminlte::page')

@section('content_header')
    <h1>Request Lists</h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Request Lists">
        <table class="table table-striped table-hover responsive" id="warehouse-request">
            <thead class="thead-dark">
                <tr>
                    <th>No Transaction</th>
                    <th>Pick Date</th>
                    <th>PIC</th>
                    <th>No Transaction_1</th>
                    <th>No</th>
                    <th>Quantity</th>
                    <th>Pick Date_1</th>
                    <th>PIC_1</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#warehouse-request').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.warehouse.request') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'no_trans',
                        name: 'no_trans'
                    },
                    {
                        data: 'pick_date',
                        name: 'pick_date'
                    },
                    {
                        data: 'pic',
                        name: 'pic'
                    },
                    {
                        data: 'no_trans',
                        name: 'no_trans'
                    },
                    {
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'pick_date',
                        name: 'pick_date'
                    },
                    {
                        data: 'pic',
                        name: 'pic'
                    }
                ]
            });
        });
    </script>
@endsection
