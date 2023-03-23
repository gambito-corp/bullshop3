<div>
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
{{--                @dump($products)--}}
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
