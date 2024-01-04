@extends('adminlte::page')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-card class="mt-4" title="Product Activation">
                    <x-adminlte-input name="key" label="License Key" igroup-size="sm">
                        <x-slot name="appendSlot">
                            <x-adminlte-button theme="btn btn-primary" label="Submit" />
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-key"></i>
                            </div>
                        </x-slot>
                        @if ($key == true)
                            <x-slot name="bottomSlot">
                                <span class="text-success">Product was activated!</span>
                            </x-slot>
                        @else
                            <x-slot name="bottomSlot">
                                <span class="text-danger">Product not activated! Please input your key into field and click
                                    Submit.</span>
                            </x-slot>
                        @endif
                    </x-adminlte-input>
                </x-adminlte-card>
            </div>
        </div>
    </div>
@endsection
