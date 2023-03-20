<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="px-6 py-4 whitespace-no-wrap">
        <img src="{{$product['imagen']}}" alt="{{$product['nombre']}}" class="w-16 h-16 object-cover rounded">
    </td>
    <td class="px-6 py-4 whitespace-no-wrap">
        <div class="flex items-center">
            <div class="ml-4">
                <div class="text-sm leading-5 font-medium text-gray-900">{{$product['nombre']}}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap">
        <input type="number" step="0.01" class="w-full text-sm leading-5 text-gray-900" wire:model="product.precio" wire:change="updateCartProduct">
    </td>
    <td class="px-6 py-4 whitespace-no-wrap">
        <div class="text-sm leading-5 text-gray-900">{{$product['cantidad']}}</div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap">
        <div class="text-sm leading-5 text-gray-900">{{$product['cantidad']*$product['precio']}}</div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap">
        <button class="inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-red-500 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-blue active:bg-red-800 transition duration-150 ease-in-out"
                wire:click.prevent="removeFromCart">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
