
@props(['action', 'entity'])
<div>
    <form method="POST" action="{{ $action }}" class="inline-block delete-form">
        @csrf
        @method('DELETE')
        <button type="button" class="delete-trigger bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2"
                data-entity="{{ $entity }}">
                <i class="fas fa-trash-alt mr-2"></i> Delete
        </button>
    </form>




    <div class="delete-modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-xl w-96 text-center relative">

            <!-- Close button with circular background -->
            <button class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-gray-500 hover:text-gray-700" onclick="this.closest('.delete-modal').classList.add('hidden')">
                <i class="fas fa-times"></i>
            </button>

            <h2 class="text-lg font-semibold mb-4">Are you sure you want to delete <br>
                <span class="font-bold text-red-600 entity-name"></span>?</h2>
            <div class="flex justify-center space-x-4">
                <button class="confirm-delete bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trash-alt mr-2"></i> Delete
                </button>
                <button class="cancel-delete bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times-circle mr-2"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-trigger');

        deleteButtons.forEach(button => {
            const wrapper = button.closest('div');
            const form = wrapper.querySelector('form');
            const modal = wrapper.querySelector('.delete-modal');
            const confirmBtn = modal.querySelector('.confirm-delete');
            const cancelBtn = modal.querySelector('.cancel-delete');
            const entityName = modal.querySelector('.entity-name');

            button.addEventListener('click', () => {
                entityName.textContent = button.dataset.entity;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            confirmBtn.addEventListener('click', () => {
                form.submit();
            });
        });
    });
</script>
