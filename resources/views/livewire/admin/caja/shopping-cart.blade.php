<div class="flex flex-col w-full h-screen">
    @if (session()->has('message'))
        <div class="fixed top-0 left-0 right-0 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed top-0 left-0 right-0 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
{{--    busqueda--}}
    <div class="flex-shrink-0 w-full">
        <input
            type="text"
            class="w-full shadow-sm px-3 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            placeholder="Buscar productos"
            wire:model.debounce.300ms="search"
            wire:keydown.enter="searchProduct"
            autofocus
        />
    </div>
    <div class="flex flex-wrap">
        <div class="w-full lg:w-2/3 pr-4">
            <div class="flex-1 p-6 overflow-y-auto">
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Precio</th>
                                <th class="px-4 py-3">Cantidad</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Acciones</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($products as $product)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">{{ $product['nombre'] }}</td>
                                    <td class="px-4 py-3">{{ $product['sku'] }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="1" min="0" class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border-none rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                               wire:model.defer="products.{{ $loop->index }}.precio"
                                               wire:change="editUnitPrice({{ $product['id'] }}, $event.target.value)"
                                        >
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="1" min="0" class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border-none rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                               wire:model.defer="products.{{ $loop->index }}.cantidad"
                                               wire:change="editUnitQuantity({{ $product['id'] }}, $event.target.value)"
                                        >
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $product['precio'] * $product['cantidad'] }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-md"
                                                wire:click="remover({{ $product['id'] }})">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/3">
            <div class="mb-4">
                {{--    clientes--}}
                <div class="flex-shrink-0 w-full md:w-1/3 p-6">
                    Bloque de Cliente
                </div>
            </div>
            <div class="mb-4">
                {{--    pago--}}
                <div class="flex-shrink-0 w-full md:w-1/3 p-6">
                    Bloque de pago
                </div>
            </div>
        </div>
    </div>
</div>
