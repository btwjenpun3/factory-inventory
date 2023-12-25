@extends('adminlte::page')

@section('content_header')
    <h1>Purchase <b style="color:red">(Under Contructions)</b></h1>
@endsection

@section('content')
    <x-adminlte-card class="mt-4" title="Purchase">
        <table class="table table-striped table-hover responsive" id="warehouse-receive">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>KP</th>
                    <th>Item</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    {{-- <script>
        $(document).ready(function() {
            $('#warehouse-receive').DataTable({
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
                    }
                ]
            });
        });
    </script> --}}
@endsection
