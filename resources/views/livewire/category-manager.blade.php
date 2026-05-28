<div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- HEADER --}}
        <div class="print-header mb-5">
            <h1 class="text-xl font-bold text-gray-800">Category List</h1>
        </div>

    {{-- TOOLBAR --}}
    <div class="flex flex-wrap gap-3 mb-5">

        {{-- SEARCH  --}}
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 1 1 14 0Z" />
                </svg>
            </div>

            <input type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                class="block w-full border p-2 pl-10 rounded text-sm focus:outline-none focus:border-blue-500">
        </div>

        {{-- ADD BUTTON --}}
        <button wire:click="openCreateModal"
            class="bg-blue-500 text-white px-4 py-2 rounded ml-auto">
            + Add Category
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Category Name</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                <tr class="border-t">
                    <td class="p-2">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>

                    <td class="p-2">{{ $category->name }}</td>

                    <td class="p-2 flex gap-10">
                        {{-- EDIT --}}
                        <button wire:click="openEditModal({{ $category->id }})"
                            class="bg-yellow-400 px-2 py-1 rounded text-xs">
                            Edit
                        </button>

                        {{-- DELETE --}}
                        <button wire:click="confirmDelete({{ $category->id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-4 text-gray-500">
                        No categories found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>



    {{-- CREATE / EDIT MODAL --}}

    @if($showModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-5 rounded">

            <h2 class="text-lg font-bold mb-3">
                {{ $isEditMode ? 'Edit Category' : 'Add Category' }}
            </h2>

            {{-- NAME --}}
            <input type="text" wire:model.defer="name"
                placeholder="Category Name"
                class="border p-2 w-full mb-1">
            @error('name') <span class="text-red-500 text-xs block mb-3">{{ $message }}</span> @enderror


            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-2 mt-2">

                <button wire:click="closeModal"
                    class="px-3 py-1 border rounded">
                    Cancel
                </button>

                <button wire:click="save"
                    class="bg-blue-500 text-white px-3 py-1 rounded">
                    {{ $isEditMode ? 'Update' : 'Save' }}
                </button>

            </div>

        </div>
    </div>
    @endif


    {{-- DELETE MODAL --}}

    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white p-5 rounded w-80 text-center">

            <p class="mb-4">Are you sure you want to delete this category?</p>

            <div class="flex justify-center gap-3">

                <button wire:click="closeModal"
                    class="px-3 py-1 border rounded">
                    Cancel
                </button>

                <button wire:click="delete"
                    class="bg-red-500 text-white px-3 py-1 rounded">
                    Delete
                </button>

            </div>

        </div>

    </div>
    @endif

</div>