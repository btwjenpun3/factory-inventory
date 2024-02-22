@extends('adminlte::page')

@section('content')
    <x-adminlte-card class="mt-4" title="Warehouse Received">
        <table class="table table-striped table-hover responsive" id="warehouse-received">
            <thead class="thead-dark">
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Unit of Material</th>
                    <th>Quantity</th>
                    <th>Unit of Material</th>
                    <th>Supplier</th>
                    @if (Gate::check('edit-warehouse-received'))
                        <th>#</th>
                    @endif
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#warehouse-received').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.warehouse.receive') }}',
                order: [
                    [0, 'desc']
                ],
                columns: columns
            });
        });
    </script>

    <script>
        var columns = [{
                data: 'item',
                render: function(data, type, row) {
                    return '<b>' + data + '</b>'
                }
            },
            {
                data: 'desc'
            },
            {
                data: 'color'
            },
            {
                data: 'size'
            },
            {
                data: 'uom'
            },
            {
                data: 'qty'
            },
            {
                data: 'uom1'
            },
            {
                data: 'supp'
            },
        ];

        @if (Gate::check('edit-warehouse-received'))
            columns.push({
                data: 'no',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-warehouse-received')
                            true
                        @else
                            false
                        @endcan ;
                    var editButton = canEdit ?
                        '<button class="btn-xs btn-primary" data-toggle="modal" data-target="#editModal" onclick="show(' +
                        data +
                        ')">Receive</button>' :
                        '';
                    return editButton
                }
            });
        @endif
    </script>
@endsection
