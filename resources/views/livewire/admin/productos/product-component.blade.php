<div class="container mx-auto" x-data="{ showModal: false, sku: '', productName: '', productBrand: '', productSize: '', productPrice: '' }">
    <div class="fixed inset-x-0 top-0 z-50">
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
    </div>
    <h1 class="text-2xl font-semibold mb-4">Productos</h1>
    <div class="w-full flex items-center justify-center mb-4">
        <div wire:loading class="text-center">
            <i class="fas fa-spinner fa-spin text-blue-50 0 text-4xl"></i>
        </div>
    </div>
    <div class="flex justify-between items-center mb-4">
        <!-- Selector de cantidad de resultados -->
        <div>
            <select wire:model="perPage" class="form-select">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>
        <!-- Botón para sincronizar productos -->
        <button
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-4"
            wire:click="syncProducts"
            wire:loading.attr="disabled"
        >
            Sincronizar productos
        </button>
        <!-- Barra de búsqueda -->
        <div class="w-1/2">
            <input
                type="text"
                class="w-full shadow-sm px-3 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="Buscar productos"
                wire:model.debounce.300ms="search"
            />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('id')">ID <i class="{{ $this->getSortArrowClass('id') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('sku')">SKU <i class="{{ $this->getSortArrowClass('sku') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('wp_id')">Id de WooCommerce <i class="{{ $this->getSortArrowClass('wp_id') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('name')">Nombre <i class="{{ $this->getSortArrowClass('name') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('size')">Talla <i class="{{ $this->getSortArrowClass('size') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('brand')">Marca <i class="{{ $this->getSortArrowClass('brand') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('price')">Precio <i class="{{ $this->getSortArrowClass('price') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('cost')">Costo <i class="{{ $this->getSortArrowClass('cost') }}"></i></button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('stock')">Cantidad <i class="{{ $this->getSortArrowClass('stock') }}"></i></button>
                </th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="text-center">{{ $product->id }}</td>
                    <td class="text-center">{{ $product->sku }}</td>
                    <td class="text-center">{{ $product->wp_id }}</td>
                    <td class="text-center">{{ $product->name }}</td>
                    <td class="text-center">{{ $product->size }}</td>
                    <td class="text-center">{{ $product->brand }}</td>
                    <td class="text-center">{{ $product->price }}</td>
                    <td class="text-center">{{ $product->cost }}</td>
                    <td class="text-center">{{ $product->stock }}</td>
                    <td class="text-center">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" width="100">
                        @else
                            No image
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="flex items-center">
                            <!-- Botón para agregar al carrito -->
                            <button class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded mr-2" wire:click="addToCart({{ $product->id }})">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button @click="sku = '{{ $product->sku }}'; productName = '{{ $product->name }}'; productBrand = '{{ $product->brand }}'; productSize = '{{ $product->size }}'; productPrice = '{{ $product->price }}'; showModal = true; generateBarcode(sku);" class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">
                                <i class="fas fa-barcode"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay productos disponibles</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $products->onEachSide(1)->links() }}
    </div>

{{--    modal--}}
    <div>
        <div x-show="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Codigo de Barras del Producto <span x-text="productName"></span>
                                </h3>
                                <div class="mt-2">
                                    <p>Marca: <span x-text="productBrand"></span></p>
                                    <p><span x-text="productName"></span> <span x-text="productSize"></span></p>
                                    <div id="barcodeContainer">
                                        <svg id="barcode"></svg>
                                    </div>
                                    <p x-text="sku"></p>
                                    <p>S/ <span x-text="productPrice"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showModal = false" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </button>
                        <button onclick="printModalContent()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('barcode', () => ({
                init() {
                    this.$watch('showModal', (value) => {
                        if (value) {
                            generateBarcode(this.sku);
                        }
                    });
                },
            }));
        });

        function generateBarcode(sku) {
            JsBarcode("#barcode", sku, {
                format: "CODE128",
                lineColor: "#000",
                width: 2,
                height: 100,
                displayValue: false,
                margin: 0,
                fontSize: 14
            });
        }

        function printModalContent() {
            let printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Imprimir código de barras</title>');
            printWindow.document.write('<style>body { font-family: Arial, sans-serif; text-align: center; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<p>Marca: ' + document.querySelector('[x-text="productBrand"]').innerText + '</p>');
            printWindow.document.write('<p>' + document.querySelector('[x-text="productName"]').innerText + ' ' + document.querySelector('[x-text="productSize"]').innerText + '</p>');
            printWindow.document.write(document.getElementById('barcodeContainer').innerHTML);
            printWindow.document.write('<p>' + document.querySelector('[x-text="sku"]').innerText + '</p>');
            printWindow.document.write('<p>S/ ' + document.querySelector('[x-text="productPrice"]').innerText + '</p>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }


    </script>
</div>
