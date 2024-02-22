@extends('adminlte::page')

@section('content')
    <x-adminlte-card class="mt-4" title="Approval Order Plan">
        <table class="table table-striped table-hover responsive" id="approval-order-plan">
            <thead class="thead-dark">
                <tr>
                    <th>Order Plan</th>
                    <th>Purchase Order Buyer</th>
                    <th>Quantity Garment</th>
                    <th>Date</th>
                    <th>Status</th>
                    @if (Gate::check('edit-approval-order-plan') || Gate::check('delete-approval-order-plan'))
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
            $('#approval-order-plan').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.approval.order.plan') }}',
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
                data: 'po_buyer'
            },
            {
                data: 'qty_gar'
            },
            {
                data: 'create_date'
            },
            {
                data: 'approve_order_plan',
                render: function(data, type, row) {
                    if (data == '0') {
                        return '<span class="badge badge-warning">Waiting for Approval</span>'
                    } else if (data == '1') {
                        return '<span class="badge badge-success">Approved</span>'
                    } else {
                        return '<span class="badge badge-danger">Rejected</span>'
                    }
                }
            }
        ];

        @if (Gate::check('edit-approval-order-plan') || Gate::check('delete-approval-order-plan'))
            columns.push({
                data: 'no',
                render: function(data, type, row) {
                    var canEdit =
                        @can('edit-approval-order-plan')
                            true
                        @else
                            false
                        @endcan ;
                    var canDelete =
                        @can('delete-approval-order-plan')
                            true
                        @else
                            false
                        @endcan ;
                    var editButton = canEdit ?
                        '<button class="btn-xs btn-success" onclick="approve(' +
                        data + ')">Approve</button>' :
                        '';
                    var deleteButton = canDelete ?
                        '<button class="btn-xs btn-danger" onclick="reject(' +
                        data + ')">Reject</button>' :
                        '';
                    if (row.approve_order_plan == '0') {
                        return editButton + ' ' + deleteButton
                    } else {
                        return ''
                    }
                }
            });
        @endif
    </script>

    <script>
        function approve(id) {
            Swal.fire({
                title: 'Approve Confirmation',
                text: 'Are you sure want approve ?',
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/approval/approve/' + id,
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
                            $('#approval-order-plan').DataTable().ajax.reload();
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

        function reject(id) {
            Swal.fire({
                title: 'Reject Confirmation',
                text: 'Are you sure want reject ?',
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/approval/reject/' + id,
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
                            $('#approval-order-plan').DataTable().ajax.reload();
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
