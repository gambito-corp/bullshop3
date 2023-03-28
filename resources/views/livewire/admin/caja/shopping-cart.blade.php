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
                <form class="w-full" wire:submit.prevent="fetchClientData">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                            DNI
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="dni" type="text" placeholder="Ingrese DNI" wire:model="dni" required>
                    </div>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Buscar cliente
                    </button>
                </form>
                <h1 class="font-bold text-lg text-gray-800">
                    Nombre:
                    <span class="text-blue-600">
                        {{isset($cliente) ? $cliente->name : '...'}}
                    </span>
                </h1>

                <div class="connect-sorting">
                    <h5 class="text-center mb-3">RESUMEN DE VENTA</h5>

                    <div class="connect-sorting-content">
                        <div class="card simple-title-task">
                            <div class="card-body">
                                <div class="flex justify-between">
                                    <div>
                                        <h2 class="text-lg font-bold mb-2">TOTAL: S/. {{number_format($total, 2)}}</h2>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold mb-2 mr-4">Articulos: {{$cantidad}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mb-4">
                {{--    pago--}}






                <div class="mt-3">
                    <div class="sm:col-span-12">
                        <div class="connect-sorting">
                            <h5 class="text-center mb-2">DENOMINACIONES</h5>
                            <div class="container">
                                <div class="flex flex-wrap">
                                    <div class="mt-2 w-full sm:w-auto">
                                        <div class="flex">
                                            <form wire:submit.prevent>
                                                <input type="text" wire:model="montoMedio" class="border border-gray-300 p-2 rounded" placeholder="Ingrese un número">
                                                @if($montoMedio > 0)
                                                    <div class="relative inline-block text-left ml-2">
                                                        <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Medio De Pago
                                                        </button>
                                                        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                                <a href="#" wire:click.prevent="processInput('Opción 1', {{$montoMedio}})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Opción 1</a>
                                                                <a href="#" wire:click.prevent="processInput('Opción 2', {{$montoMedio}})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Opción 2</a>
                                                                <a href="#" wire:click.prevent="processInput('Opción 3', {{$montoMedio}})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Opción 3</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @error('montoMedio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            @if($resto <= 0)
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-12 py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                        wire:click="venta">
                                    Pagar
                                </button>
                            @endif
                            @if($change > 0)
                                <h1 class="font-bold text-lg text-gray-800">
                                    Cambio:
                                    <span class="text-blue-600">
                                        {{isset($change) ? $change : '...'}}
                                    </span>
                                </h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
