@extends('adminlte::page')

@section('content_header')
    <h1>Purchase</h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Purchase">
        <table class="table table-striped table-hover responsive" id="purchase">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>KP</th>
                    <th>Item</th>
                    <th>Desc</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>UOM</th>
                    <th>Quantity</th>
                    <th>UOM1</th>
                    <th>PO Supp</th>
                    <th>No Purchase</th>
                    <th>Date Mod</th>
                    <th>Supplier</th>
                    <th>AWB</th>
                    <th>Price</th>
                    <th>No Invoice</th>
                    <th>ETD</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#purchase').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.purchase') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'kp',
                        name: 'kp'
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
                    },
                    {
                        data: 'no_tr_purhase',
                        name: 'no_tr_purhase'
                    },
                    {
                        data: 'date_mod',
                        name: 'date_mod'
                    },
                    {
                        data: 'supp',
                        name: 'supp'
                    },
                    {
                        data: 'awb',
                        name: 'awb'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'no_invo',
                        name: 'no_invo'
                    },
                    {
                        data: 'etd',
                        name: 'etd'
                    }
                ]
            });
        });
    </script>
@endsection
