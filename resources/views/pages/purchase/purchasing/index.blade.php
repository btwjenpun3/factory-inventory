@extends('adminlte::page')

@section('content')
    <div id="edit_success"></div>
    <x-adminlte-card class="mt-4" title="Purchasing">
        <table class="table table-striped table-hover responsive" id="purchase-purchasing">
            <thead class="thead-dark">
                <tr>
                    <th>Production Card</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Unit of Material</th>
                    <th>Quantity</th>
                    <th>Unit of Material</th>
                    <th>Purchase Order Supplier</th>
                    <th>Purchase Order Buyer</th>
                    <th>Status</th>
                    @if (Gate::check('edit-purchase-purchasing'))
                        <th>#</th>
                    @endif
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    <x-adminlte-modal id="editModal" title="Add Purchase" theme="info" size="md">
        <div class="container">
            <div id="edit_failed"></div>
            <x-adminlte-card class="mt-4" title="Supplier">
                <x-adminlte-input id="_supplier" name="_supplier" label="Supplier" />
            </x-adminlte-card>
            <x-adminlte-card class="mt-4" title="Order Purchase">
                <x-adminlte-input id="_no_invoice" name="_no_invoice" label="Invoice No." />
                <x-adminlte-select id="_currency" name="_currency" label="Currency">
                    <option value="IDR">IDR</option>
                    <option value="USD">USD</option>
                </x-adminlte-select>
                <x-adminlte-input id="_price" name="_price" label="Price" />
                <x-adminlte-input type="date" id="_etd" name="_etd" label="Estimated Date" />
                <x-adminlte-input id="_awb" name="_awb" label="Air Way Bill" />
            </x-adminlte-card>
            <x-adminlte-button id="update" label="Save" theme="primary" />
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#purchase-purchasing').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.purchase.purchasing') }}',
                order: [
                    [0, 'desc']
                ],
                columns: columns
            });
        });
    </script>

    <script>
        var columns = [{
                data: 'kp',
                render: function(data, type, row) {
                    return '<b>' + data + '</b>'
                }
            },
            {
                data: 'item'
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
                data: 'po_sup'
            },
            {
                data: 'po_buyer'
            },
            {
                data: 'status',
                render: function(data, type, row) {
                    if (data == null) {
                        return '<span class="badge badge-warning">Waiting for approval</span>'
                    } else {
                        return '<span class="badge badge-success">Approved</span>'
                    }
                }
            }
        ];

        @if (Gate::check('edit-purchase-purchasing'))
            columns.push({
                data: 'no',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-purchase-purchasing')
                            true
                        @else
                            false
                        @endcan ;
                    var editButton = canEdit ?
                        '<button class="btn-xs btn-primary" data-toggle="modal" data-target="#editModal" onclick="show(' +
                        data +
                        ')">Update</button> <button class="btn-xs btn-success" onclick="approve(' +
                        data + ')">Approve</button>' :
                        '';
                    if (row.status == '1') {
                        return ''
                    } else {
                        return editButton
                    }
                }
            });
        @endif
    </script>

    <script>
        var kpId;

        function show(id) {
            kpId = id;
            $.ajax({
                url: '/purchase/show/' + id,
                method: 'GET',
                success: function(response) {
                    $('#_supplier').val(response.supp);
                    $('#_no_invoice').val(response.no_invo);
                    $('#_currency').val(response.idr);
                    $('#_price').val(response.price);
                    $('#_etd').val(response.etd);
                    $('#_awb').val(response.awb);
                },
                error: function(xhr, error) {

                }
            });
        }

        $('#update').click(function() {
            $.ajax({
                url: '/purchase/update/' + kpId,
                method: 'POST',
                data: {
                    supplier: $('#_supplier').val(),
                    no_invoice: $('#_no_invoice').val(),
                    currency: $('#_currency').val(),
                    price: $('#_price').val(),
                    etd: $('#_etd').val(),
                    awb: $('#_awb').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#editModal').modal('hide');
                    $('#edit_success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $('#purchase-purchasing').DataTable().ajax.reload();
                },
                error: function(xhr, error) {

                }
            });
        });

        function approve(id) {
            Swal.fire({
                title: 'Approve Confirmation',
                text: 'You cannot update this purchase after approve! Are you sure ? ',
                showCancelButton: true,
                confirmButtonColor: '#E4A11B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/purchase/approve/' + id,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Data succesfully deleted'
                            });
                            $('#purchase-purchasing').DataTable().ajax.reload();
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
