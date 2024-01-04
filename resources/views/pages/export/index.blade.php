@extends('adminlte::page')

@section('content_header')
@endsection

@section('content')
    <div class="container pt-4">
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <x-adminlte-card title="Choose Data">
            <form method="GET" action="{{ route('export.excel') }}">
                @csrf
                <x-adminlte-select name="export" label="Select Data to Export" igroup-size="sm">
                    <option value="empty" selected>- Select Data -</option>
                    <optgroup label="Master">
                        <option value="master_item" disabled>Master Item</option>
                    </optgroup>
                    <optgroup label="Purchase">
                        <option value="purchase">Purchase</option>
                    </optgroup>
                </x-adminlte-select>
                {{-- <div id="purchaseForm" style="display: none;">
                    <label for="purchase_form">Options (Optional)</label>
                    <x-adminlte-input name="purchase_form" igroup-size="sm" />
                </div> --}}
                <x-adminlte-button type="submit" class="btn-sm bg-gradient-info" label="Export to Excel" theme="success" />
            </form>
        </x-adminlte-card>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('export').addEventListener('change', function() {
            var selectedValue = this.value;
            var purchaseForm = document.getElementById('purchaseForm');
            if (selectedValue === 'purchase') {
                purchaseForm.style.display = 'block';
            } else {
                purchaseForm.style.display = 'none';
            }
        });
    </script>
@endsection
