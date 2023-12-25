@extends('adminlte::page')

@section('content_header')
    <h1>Master KP</h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Master Item">
        <table class="table table-striped table-hover responsive" id="kp">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Desc</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>UOM</th>
                    <th>Quantity</th>
                    <th>UOM1</th>
                    <th>PO Supplier</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#kp').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.kp') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    {
                        data: 'desc',
                        name: 'desc'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'uom',
                        name: 'uom'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'uom1',
                        name: 'uom1'
                    },
                    {
                        data: 'po_sup',
                        name: 'po_sup'
                    }
                ]
            });
        });
    </script>
@endsection
