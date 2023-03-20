<div>
    @if (session()->has('error'))
        <div class="fixed top-0 left-0 right-0 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if(count($cart) > 0)
        <table class="w-full">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Imagen</th>
                <th class="py-3 px-6 text-left">Producto</th>
                <th class="py-3 px-6 text-center">Precio</th>
                <th class="py-3 px-6 text-center">Cantidad</th>
                <th class="py-3 px-6 text-center">Total</th>
                <th class="py-3 px-6 text-center">Acciones</th>
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            @forelse ($cart as $item)
                @livewire('admin.caja.assets.product',['product' => $item])
            @empty
                <tr>
                    <td colspan="6" class="text-center">Escanea un producto para agregarlo al carrito</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @else
        <div class="text-center">Escanea un producto para agregarlo al carrito</div>
    @endif
</div>
