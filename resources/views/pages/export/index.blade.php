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

                <div id="purchaseForm" style="display: none;">
                    <x-adminlte-card>
                        <span><b>(OPTIONAL)</b> Please select <code>Options</code> below if you wish to export your data
                            more detail</span>
                        <hr>
                        <x-adminlte-select name="purchase_supplier" label="Supplier" igroup-size="sm">
                            <option value="" selected>- Select Supplier -</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplier }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        </x-adminlte-select>
                        @php
                            $config = [
                                'locale' => ['format' => 'YYYY-MM-DD'],
                            ];
                        @endphp
                        <x-adminlte-date-range id="purchase_etd" name="purchase_etd" label="Choose ETD Range"
                            :config="$config" placeholder="Select a date range..." igroup-size="sm" />
                    </x-adminlte-card>
                </div>
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

        $(() => $("#purchase_etd").val(''));

        var purchaseEtdInput = $('#purchase_etd');
        purchaseEtdInput.on('apply.daterangepicker', function(ev, picker) {
            if (picker.startDate && picker.endDate) {
                purchaseEtdInput.val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            } else {
                purchaseEtdInput.val('');
            }
        });
    </script>
@endsection
