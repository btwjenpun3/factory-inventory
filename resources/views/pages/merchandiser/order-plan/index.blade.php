@extends('adminlte::page')

@section('content')
    <div class="pt-4">
        <div class="row">
            <button type="button" class="btn-sm btn-success mx-2" data-toggle="collapse" data-target="#addCollapse">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>

    {{-- Modal untuk Add --}}

    <div class="collapse multi-collapse mt-4" id="addCollapse">
        <div id="success"></div>
        <div id="error"></div>
        <div class="container-fluid bg-white border border-dark p-3">
            <div class="row">
                <div class="col">
                    <x-adminlte-select2 id="buyer" name="buyer" label="Code Buyer" igroup-size="sm">
                        <option value="" selected>- Select Buyer -</option>
                        @foreach ($buyers as $buyer)
                            <option value="{{ $buyer->code }}">{{ $buyer->code }} - {{ $buyer->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-adminlte-select2 id="po_buyer" name="po_buyer" label="PO Buyer" igroup-size="sm">
                        <option value="" selected>- Select PO Buyer -</option>
                        @foreach ($orderBuys as $orderBuy)
                            <option value="{{ $orderBuy->po_buyer }}">{{ $orderBuy->po_buyer }}
                            </option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-adminlte-select2 id="item" name="item" label="Item" igroup-size="sm">
                        <option value="" selected>- Select Item -</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->code_buyer }}">{{ $item->code_buyer }}
                            </option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-adminlte-input id="desc" name="desc" label="Item Description" igroup-size="sm" disabled />
                </div>
                <div class="col">
                    <x-adminlte-input id="size" name="size" label="Size" igroup-size="sm" />
                    <x-adminlte-select2 id="uom" name="uom" label="Unit of Material" igroup-size="sm">
                        <option value="" selected>- Select UoM -</option>
                        <option value="METER">METER</option>
                        <option value="YARD">YARD</option>
                        <option value="PCS">PCS</option>
                        <option value="CNS">CNS</option>
                    </x-adminlte-select2>
                    <x-adminlte-input id="quantity" name="quantity" label="Quantity" igroup-size="sm" />
                    <x-adminlte-select2 id="uom2" name="uom2" label="Unit of Material" igroup-size="sm">
                        <option value="" selected>- Select UoM -</option>
                        <option value="METER">METER</option>
                        <option value="YARD">YARD</option>
                        <option value="PCS">PCS</option>
                        <option value="CNS">CNS</option>
                    </x-adminlte-select2>
                </div>
                <div class="col">
                    <x-adminlte-input id="color" name="color" label="Color" igroup-size="sm" />
                    <x-adminlte-input id="po_supplier" name="po_supplier" label="PO Supplier" igroup-size="sm" />
                    <x-adminlte-input id="qty_garment" name="qty_garment" label="Quantity Garment" igroup-size="sm"
                        disabled />
                </div>
            </div>
            <div class="col text-right">
                <x-adminlte-button label="Add new order plan" theme="success" class="btn" onclick="store()" />
            </div>
        </div>
    </div>

    {{-- DataTable --}}

    <x-adminlte-card class="mt-4" title="Order Plan">
        <table class="table table-striped table-hover responsive" id="order-plan">
            <thead class="thead-dark">
                <tr>
                    <th>Code Buyer</th>
                    <th>PO Buyer</th>
                    <th>Item</th>
                    <th>Item Description</th>
                    <th>Size</th>
                    <th>Unit of Material</th>
                    <th>Quantity</th>
                    <th>Unit of Material</th>
                    <th>Color</th>
                    <th>PO Supplier</th>
                    <th>Quantity Garment</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>

    {{-- Modal Save --}}

    <x-adminlte-modal id="showModal" title="Order Plan Approval" size="lg">
        <div class="container">
            <div id="loading" style="display: none;">Loading...</div>
            <table id="showDetailTable" class="table table-striped">
                <tbody>
                    <tr>
                        <td><b>Code Buyer</b></td>
                        <td id="_code_buyer"></td>
                    </tr>
                    <tr>
                        <td><b>PO Buyer</b></td>
                        <td id="_po_buyer"></td>
                    </tr>
                    <tr>
                        <td><b>Item</b></td>
                        <td id="_item"></td>
                    </tr>
                    <tr>
                        <td><b>Item Description</b></td>
                        <td id="_item_description"></td>
                    </tr>
                    <tr>
                        <td><b>Size</b></td>
                        <td id="_size"></td>
                    </tr>
                    <tr>
                        <td><b>Unit of Material</b></td>
                        <td id="_uom"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity</b></td>
                        <td id="_quantity"></td>
                    </tr>
                    <tr>
                        <td><b>Unit of Material 2</b></td>
                        <td id="_uom_2"></td>
                    </tr>
                    <tr>
                        <td><b>Color</b></td>
                        <td id="_color"></td>
                    </tr>
                    <tr>
                        <td><b>PO Supplier</b></td>
                        <td id="_po_supplier"></td>
                    </tr>
                    <tr>
                        <td><b>Quantity Garment</b></td>
                        <td id="_quantity_garment"></td>
                    </tr>
                </tbody>
            </table>
            <div class="col text-right">
                <x-adminlte-button label="Save" theme="success" class="btn" onclick="save()" />
            </div>
        </div>
    </x-adminlte-modal>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            dataTable = $('#order-plan').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.kp.temporary') }}',
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'code_buyer'
                    },
                    {
                        data: 'po_buyer'
                    },
                    {
                        data: 'item'
                    },
                    {
                        data: 'item_description'
                    },
                    {
                        data: 'size'
                    },
                    {
                        data: 'unit_of_material'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'unit_of_material_2'
                    },
                    {
                        data: 'color'
                    },
                    {
                        data: 'po_supplier'
                    },
                    {
                        data: 'quantity_garment'
                    },
                    {
                        data: 'id',
                        render: function(data, full) {
                            return '<button class="btn btn-xs btn-success" onclick="approveAll()">Send for Approve All</button> <button class="btn btn-xs btn-danger" onclick="hapus(' +
                                data + ')">Delete</button>';
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        $('#item').on('change', function() {
            var selectedItem = $(this).val();
            $.ajax({
                url: '{{ route('merchandiser.get.item') }}',
                method: 'GET',
                data: {
                    code_buyer: selectedItem
                },
                success: function(response) {
                    $('#desc').val(response.desc);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#po_buyer').on('change', function() {
            var selectedItem = $(this).val();
            $.ajax({
                url: '{{ route('merchandiser.get.quantity.garment') }}',
                method: 'GET',
                data: {
                    po_buyer: selectedItem
                },
                success: function(response) {
                    $('#qty_garment').val(response.qty);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    </script>

    <script>
        var orderPlanId;

        function store() {
            $.ajax({
                url: '{{ route('merchandiser.store') }}',
                method: 'POST',
                data: {
                    buyer: $('#buyer').val(),
                    po_buyer: $('#po_buyer').val(),
                    item: $('#item').val(),
                    desc: $('#desc').val(),
                    size: $('#size').val(),
                    uom: $('#uom').val(),
                    quantity: $('#quantity').val(),
                    uom2: $('#uom2').val(),
                    color: $('#color').val(),
                    po_supplier: $('#po_supplier').val(),
                    qty_garment: $('#qty_garment').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $('#order-plan').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        }

        function showBeforeApprove(id) {
            orderPlanId = id;
            $('#showDetailTable').hide();
            $('#loading').show();
            $.ajax({
                url: '/merchandiser/show/temporary/' + id,
                type: 'GET',
                success: function(response) {
                    $('#_code_buyer').html(response.code_buyer);
                    $('#_po_buyer').html(response.po_buyer);
                    $('#_item').html(response.item);
                    $('#_item_description').html(response.item_description);
                    $('#_size').html(response.size);
                    $('#_uom').html(response.unit_of_material);
                    $('#_quantity').html(response.quantity);
                    $('#_uom_2').html(response.unit_of_material_2);
                    $('#_color').html(response.color);
                    $('#_po_supplier').html(response.po_supplier);
                    $('#_quantity_garment').html(response.quantity_garment);
                    $('#loading').hide();
                    $('#showDetailTable').show();
                },
                error: function(xhr, error, status) {

                }
            });
        }

        function approveAll() {
            var tableData = $('#order-plan').DataTable().rows().data().toArray();

            $.ajax({
                url: '{{ route('merchandiser.approve.all') }}',
                method: 'POST',
                data: {
                    data: tableData,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $('#order-plan').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        }

        function hapus(id) {
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are you sure want delete this ?',
                showCancelButton: true,
                confirmButtonColor: '#E4A11B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/merchandiser/delete/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Data succesfully deleted'
                            });
                            $('#order-plan').DataTable().ajax.reload();
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
