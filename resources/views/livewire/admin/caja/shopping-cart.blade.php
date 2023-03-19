<div>
    @if (session()->has('error'))
        <div class="fixed top-0 left-0 right-0 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if(count($cart) > 0)
        <table class="table w-full">
            <thead>
            <tr>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    imagen
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Producto
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Precio
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Cantidad
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">acciones</th>
            </tr>

            </thead>
            <tbody>
            @forelse ($cart as $item)
                <tr>
                    <td class="text-center">
                        <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}" width="100">
                    </td>
                    <td class="text-center">{{ $item['nombre'] }}</td>
                    <td class="text-center">
                        {{$item['precio']}}
                    </td>
                    <td class="text-center">
                        {{$item['cantidad']}}
                    </td>
                    <td class="text-center">{{ $item['precio'] * $item['cantidad'] }}</td>
                    <td class="text-center">
                        <button class="bg-red-500 hover:bg-red-700 text-white p-2 rounded" wire:click="removeFromCart({{ $item['id'] }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

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
