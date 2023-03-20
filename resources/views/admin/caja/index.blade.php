@extends('layouts.main')
@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2">
                @livewire('admin.caja.search-bar')
            </div>
        </div>
        <div class="flex flex-wrap -mx-2 mt-4">
            <div class="w-full md:w-2/3 px-2">
                @livewire('admin.caja.shopping-cart')
            </div>
            <div class="w-full md:w-1/3 px-2">
                @livewire('admin.caja.customer-module')
                @livewire('admin.caja.payment-module')
            </div>
        </div>
    </div>
@endsection
