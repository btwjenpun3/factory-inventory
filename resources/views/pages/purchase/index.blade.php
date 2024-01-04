@extends('adminlte::page')

@section('content')
    <div class="pt-4">
        <button type="button" class="btn-sm btn-primary" data-toggle="collapse" data-target="#searchCollapse">
            <i class="fas fa-search"></i> Search Data
        </button>
    </div>

    <div class="collapse multi-collapse" id="searchCollapse">
        <div class="container-fluid pt-4">
            <div class="row gx-5">
                <div class="col">
                    <x-adminlte-input name="kp" label="Nomor KP" placeholder="Masukkan nomor kartu purchase"
                        igroup-size="sm" />
                    <x-adminlte-input name="item" label="Item" placeholder="Masukkan item" igroup-size="sm" />
                    <x-adminlte-input name="desc" label="Description" placeholder="Masukkan description" igroup-size="sm"
                        disabled />
                    <x-adminlte-select2 name="supplier" label="Supplier" igroup-size="sm">
                        <option value="" selected>- Pilih Supplier -</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->supplier }}">{{ $supplier->supplier }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="col">
                    <x-adminlte-input name="eta" label="ETA" placeholder="Masukkan Estimated Arrived"
                        igroup-size="sm" disabled />
                    <x-adminlte-input name="etd" label="ETD" placeholder="Masukkan Estimated Date" igroup-size="sm"
                        disabled />
                    <x-adminlte-input name="no_kapal" label="No Kapal" placeholder="Masukkan Nomor Kapal" igroup-size="sm"
                        disabled />
                </div>
            </div>
        </div>

        <div class="col text-right">
            <x-adminlte-button label="Search" id="searchTable" theme="success" class="btn-sm" />
        </div>
    </div>

    <x-adminlte-modal id="showModal" title="Purchase Detail" size="lg">
        <div class="container">
            <div id="loading" style="display: none;">Loading...</div>
            <table id="showDetailTable" class="table table-striped">
                <tbody>
                    <tr>
                        <td><b>No KP</b></td>
                        <td id="show_kp"></td>
                    </tr>
                    <tr>
                        <td><b>Item</b></td>
                        <td id="show_item"></td>
                    </tr>
                    <tr>
                        <td><b>Description</b></td>
                        <td id="show_description"></td>
                    </tr>
                    <tr>
                        <td><b>Color</b></td>
                        <td id="show_color"></td>
                    </tr>
                    <tr>
                        <td><b>Size</b></td>
                        <td id="show_size"></td>
                    </tr>
                    <tr>
                        <td><b>UOM</b></td>
                        <td id="show_uom"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity</b></td>
                        <td id="show_quantity"></td>
                    </tr>
                    <tr>
                        <td><b>UOM1</b></td>
                        <td id="show_uom1"></td>
                    </tr>
                    <tr>
                        <td><b>PO Supplier</b></td>
                        <td id="show_po_supplier"></td>
                    </tr>
                    <tr>
                        <td><b>PO Buyer</b></td>
                        <td id="show_po_buyer"></td>
                    </tr>
                    <tr>
                        <td><b>PO_Gen</b></td>
                        <td id="show_po_gen"></td>
                    </tr>
                    <tr>
                        <td><b>Create Date</b></td>
                        <td id="show_create_date"></td>
                    </tr>
                    <tr>
                        <td><b>Approved</b></td>
                        <td id="show_approved"></td>
                    </tr>
                    <tr>
                        <td><b>Date Mod</b></td>
                        <td id="show_date_mod"></td>
                    </tr>
                    <tr>
                        <td><b>Supplier</b></td>
                        <td id="show_supplier"></td>
                    </tr>
                    <tr>
                        <td><b>AWB</b></td>
                        <td id="show_awb"></td>
                    </tr>
                    <tr>
                        <td><b>Price</b></td>
                        <td id="show_price"></td>
                    </tr>
                    <tr>
                        <td><b>IDR</b></td>
                        <td id="show_idr"></td>
                    </tr>
                    <tr>
                        <td><b>No Invoice</b></td>
                        <td id="show_no_invoice"></td>
                    </tr>
                    <tr>
                        <td><b>ETD</b></td>
                        <td id="show_etd"></td>
                    </tr>
                    <tr>
                        <td><b>Qty Gar</b></td>
                        <td id="show_qty_gar"></td>
                    </tr>
                    <tr>
                        <td><b>Status</b></td>
                        <td id="show_status"></td>
                    </tr>
                    <tr>
                        <td><b>Delete Date</b></td>
                        <td id="show_delete_date"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity Received</b></td>
                        <td id="show_quantity_received"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity Pass QC</b></td>
                        <td id="show_quantity_pass_qc"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity Request</b></td>
                        <td id="show_quantity_request"></td>
                    </tr>
                    <tr>
                        <td><b>Stock</b></td>
                        <td id="show_stock"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-adminlte-modal>

    <x-adminlte-card class="mt-4" title="Purchase">
        <table class="table table-striped table-hover responsive" id="purchase">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>KP</th>
                    <th>Item</th>
                    <th>Supplier</th>
                    <th>No Invoice</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        var userId;
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
                        name: 'no',
                        render: function(data) {
                            return '<b>' + data + '</b>';
                        }
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
                        data: 'supp',
                        name: 'supp',
                        render: function(data) {
                            if (data == '') {
                                return '-';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'no_invo',
                        name: 'no_invo',
                        render: function(data) {
                            if (data == '') {
                                return '-';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data == 'received' || data == 'Received') {
                                return '<span class="badge bg-success">Received</span>'
                            } else {
                                return '<span class="badge bg-warning">Not Receive</span>'
                            }
                        }
                    },
                    {
                        data: 'no',
                        name: 'no',
                        render: function(data, full) {
                            return '<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#showModal" onclick="showDetail(' +
                                data + ')">Detail</button>'
                        }
                    }
                ]
            });
        });

        $('#searchTable').on('click', function() {
            var kpValue = $('input[name="kp"]').val();
            var itemValue = $('input[name="item"]').val();
            var supplierValue = $('select[name="supplier"]').val();
            dataTable.search(kpValue + ' ' + itemValue + ' ' + supplierValue).draw();
        });

        function showDetail(id) {
            userId = id;
            $('#showDetailTable').hide();
            $('#loading').show();
            $.ajax({
                url: '/purchase/show/' + userId,
                type: 'GET',
                success: function(response) {
                    $('#show_kp').html(response.kp);
                    $('#show_item').html(response.item);
                    $('#show_size').html(response.size);
                    $('#show_uom').html(response.uom);
                    $('#show_quantity').html(response.qty);
                    $('#show_uom1').html(response.uom1);
                    $('#show_po_supplier').html(response.po_supplier);
                    $('#show_po_buyer').html(response.po_buyer);
                    $('#show_po_gen').html(response.po_gen);
                    $('#show_create_date').html(response.create_date);
                    $('#show_approved').html(response.approved);
                    $('#show_date_mod').html(response.date_mod);
                    $('#show_supplier').html(response.supp);
                    $('#show_awb').html(response.awb);
                    $('#show_price').html(response.price);
                    $('#show_idr').html(response.idr);
                    $('#show_no_invoice').html(response.no_invo);
                    $('#show_etd').html(response.etd);
                    $('#show_qty_gar').html(response.qty_gar);
                    if (response.status == 'received' || response.status == 'Received') {
                        $('#show_status').html('<span class="badge bg-success">Received</span>');
                    } else {
                        $('#show_status').html('<span class="badge bg-warning">Not Receive</span>');
                    }
                    $('#show_delete_date').html(response.del_date);
                    $('#show_quantity_received').html(response.qty_rcvd);
                    $('#show_quantity_pass_qc').html(response.qty_passqc);
                    $('#show_quantity_request').html(response.qty_req);
                    $('#show_stock').html(response.stock);
                    $('#loading').hide();
                    $('#showDetailTable').show();
                },
                error: function(xhr, error, status) {}
            });
        }
    </script>
@endsection

@section('css')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        #loading {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #d1d1d1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
        }

        #showDetailTable {
            display: none;
        }
    </style>
@endsection
