<?php

namespace App\Http\Livewire\Admin\Caja;

use Livewire\Component;

class CustomerModule extends Component
{
    public $customerInfo = '';
    public $total;
    public function render()
    {
        return view('livewire.admin.caja.customer-module');
    }
}
