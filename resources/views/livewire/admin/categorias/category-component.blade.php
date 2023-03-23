<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4">Categorias</h1>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    <div class="w-full flex items-center justify-center mb-4">
        <div wire:loading class="text-center">
            <i class="fas fa-spinner fa-spin text-blue-500 text-4xl"></i>
        </div>
    </div>
    <div class="w-full flex items-center justify-center mb-4">
        <button wire:click="syncCategories" wire:loading.remove wire:loading.attr="disabled" class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Sincronizar categorías
        </button>
    </div>

    <div class="flex justify-between items-center mb-3">
{{--        <button wire:click="create()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">--}}
{{--            Agregar nueva categoría--}}
{{--        </button>--}}
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="toggleShowDeleted">
            {{ $showDeleted ? 'Salir de papelera' : 'Ver papelera' }}
        </button>
        <input type="text" class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none" placeholder="Buscar..." wire:model="searchTerm" />
    </div>
    <div class="flex items-center">
        <label for="perPage" class="mr-2">Elementos por página:</label>
        <select wire:model="perPage" id="perPage" class="form-select text-sm rounded-md">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
    </div>
    {{--tabla --}}
    <div class="bg-white shadow-md rounded my-6 w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    ID ↑↓
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nombre ↑↓
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Descripción
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 text-center whitespace-nowrap">{{ $category->id }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">{{ $category->description }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <!-- Aquí puedes agregar botones para acciones como editar y eliminar -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay categorías disponibles</td>
                </tr>
            @endforelse
            </tbody>
        </table>


    </div>
    {{--links--}}
    <div class="mt-5">
       {{ $categories->onEachSide(1)->links() }}
    </div>
    {{--modal crear--}}
        <!-- Modal -->
        <div x-data="{ open: @entangle('isModalOpen') }" x-on:open-modal.window="open = true" x-on:close-modal.window="open = false" x-show="open" class="fixed z-10 inset-0 overflow-y-auto"
             style="display:none" id="categoryModal" aria-labelledby="categoryModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="categoryModalLabel">
                            {{ $category_id ? 'Editar Categoría' : 'Agregar Categoría' }}
                        </h3>
                        <div class="mt-2">
                            <form>
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        Nombre
                                    </label>
                                    <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="name" placeholder="Ingrese el nombre de la categoría" wire:model="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6">
                <span class="flex w-full rounded-md shadow-sm">
                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click.prevent="store()">
                        Guardar
                    </button>
                </span>
                    </div>
                    <div class="mt-3 sm:mt-4">
                <span class="flex w-full rounded-md shadow-sm">
                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" wire:click.prevent="closeModal()">
                        Cancelar
                    </button>
                </span>
                    </div>
                </div>
            </div>
        </div>

        @if ($isDeleteModalOpen)
            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Confirmar eliminación
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            ¿Estás seguro de que deseas eliminar esta categoría?
                                        </p>
                                        <input type="password" wire:model="password" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Introduce la contraseña">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="forceDeleteCategory" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Eliminar
                            </button>
                            <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif



</div>

