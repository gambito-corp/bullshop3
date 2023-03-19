<div>
    <div>
        <!-- Button to open modal -->
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="$emit('openModal')">
            Open Modal
        </button>

        <!-- Modal -->
        <div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="modal">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal content -->
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Your Modal Title
                            </h3>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">
                                Your modal content goes here...
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <!-- Modal buttons -->
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="$emit('closeModal')">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('openModal', () => {
                    document.getElementById('modal').style.display = 'block';
                });

                Livewire.on('closeModal', () => {
                    document.getElementById('modal').style.display = 'none';
                });
            });
        </script>
    @endpush

</div>
