<div>
    <input
        type="text"
        class="w-full shadow-sm px-3 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        placeholder="Buscar productos"
        wire:model.debounce.300ms="search"
        wire:keydown.enter="searchProduct"
        autofocus
    />
</div>
