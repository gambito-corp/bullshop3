@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @livewire('admin.caja.search-bar')
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                @livewire('admin.caja.shopping-cart')
            </div>
            <div class="col-md-4">
                @livewire('admin.caja.customer-module')
                @livewire('admin.caja.payment-module')
            </div>
        </div>
    </div>
@endsection
