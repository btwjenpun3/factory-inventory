@extends('adminlte::page')

@section('content')
    <div class="pt-4">
        <button type="button" class="btn-sm btn-primary" data-toggle="collapse" data-target="#createCollapse">
            <i class="fas fa-search"></i> Search Data
        </button>
    </div>
    <div class="collapse multi-collapse" id="createCollapse">
        <div class="container-fluid pt-4">
            <div class="row gx-5">
                <div class="col">
                    <x-adminlte-input name="kp" label="Nomor KP" placeholder="Masukkan nomor kartu purchase"
                        igroup-size="sm">
                    </x-adminlte-input>
                    <x-adminlte-input name="item" label="Item" placeholder="Masukkan item" igroup-size="sm" />
                    <x-adminlte-input name="desc" label="Description" placeholder="Masukkan description"
                        igroup-size="sm" />
                    <x-adminlte-select2 name="supplier" label="Supplier" igroup-size="sm">
                        <option value="" selected>- Pilih Supplier -</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->supplier }}">{{ $supplier->supplier }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="col">
                    <x-adminlte-input name="iLabel" label="ETA" placeholder="Masukkan Estimated Arrived"
                        igroup-size="sm" />
                    <x-adminlte-input name="iLabel" label="ETD" placeholder="Masukkan Estimated Date"
                        igroup-size="sm" />
                    <x-adminlte-input name="iLabel" label="No Kapal" placeholder="Masukkan Nomor Kapal" igroup-size="sm" />
                </div>
            </div>
        </div>
        <div class="col text-right">
            <x-adminlte-button label="Search" id="searchTable" theme="success" class="btn-sm" />
        </div>
    </div>
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
            dataTable = $('#purchase').DataTable({
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

        // Tambahkan event listener untuk tombol pencarian
        $('#searchTable').on('click', function() {
            var kpValue = $('input[name="kp"]').val();
            var itemValue = $('input[name="item"]').val();
            var descValue = $('input[name="desc"]').val();
            var supplierValue = $('select[name="supplier"]').val();

            // Lakukan pencarian dengan mengirimkan nilai KP ke server
            dataTable.search(kpValue + ' ' + itemValue + ' ' + descValue + ' ' + supplierValue).draw();
        });
    </script>
@endsection

@section('css')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
@endsection
