@extends('adminlte::page')

@section('content_header')
    <h1>Master Item</h1>
@stop

@section('content')
    <div class="container">
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <x-adminlte-card class="mt-4" title="Master Item">
            <x-adminlte-select id="code_buyer" name="code_buyer" label="Code Buyer">
                @foreach ($buyers as $buyer)
                    <option value="{{ $buyer->code }}">{{ $buyer->code }} - {{ $buyer->name }}</option>
                @endforeach
            </x-adminlte-select>
            <x-adminlte-input id="items" name="items" label="Item" />
            <x-adminlte-input id="desc" name="desc" label="Description" />
            <x-adminlte-button type="submit" label="Save" theme="primary" onclick="store()" />
        </x-adminlte-card>
    </div>
@endsection

@section('js')
    <script>
        const token = $('meta[name="csrf-token"]').attr('content');

        function store() {
            $.ajax({
                url: '/master/item/store',
                method: 'POST',
                data: {
                    code_buyer: $('#code_buyer').val(),
                    items: $('#items').val(),
                    desc: $('#desc').val(),
                    _token: token
                },
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data succesfully created'
                    });
                    $('#item').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    Swal.fire('Failed',
                        'Link deleted failed.',
                        'error');
                }
            });
        }
    </script>
@endsection
