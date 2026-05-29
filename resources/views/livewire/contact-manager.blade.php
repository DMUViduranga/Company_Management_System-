<div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="print-header mb-5">
        <h1 class="text-xl font-bold text-gray-800">Contact List</h1>
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
                placeholder="Search contacts..."
                class="block w-full border p-2 pl-10 rounded text-sm focus:outline-none focus:border-blue-500">
        </div>

        {{-- CATEGORY FILTER --}}
        <select wire:model.live="categoryFilter"
            class="border p-2 rounded w-60">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- ADD BUTTON --}}
        <button wire:click="openCreateModal"
            class="bg-blue-500 text-white px-4 py-2 rounded ml-auto">
            + Add Contact
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Email</th>
                    <th class="p-2 text-left">Phone</th>
                    <th class="p-2 text-left">Category</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($contacts as $contact)
                <tr class="border-t">
                    <td class="p-2">
                        {{ $contacts->firstItem() + $loop->index }}
                    </td>

                    <td class="p-2">{{ $contact->name }}</td>
                    <td class="p-2">{{ $contact->email }}</td>
                    <td class="p-2">{{ $contact->phone }}</td>

                    <td class="p-2">
                        {{ $contact->category->name ?? '-' }}
                    </td>

                    <td class="p-2 flex gap-2">

                        {{-- EDIT --}}
                        <button wire:click="openEditModal({{ $contact->id }})"
                            class="bg-yellow-400 px-2 py-1 rounded text-xs">
                            Edit
                        </button>

                        {{-- DELETE --}}
                        <button wire:click="confirmDelete({{ $contact->id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                            Delete
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-gray-500">
                        No contacts found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $contacts->links() }}
    </div>



    {{-- CREATE / EDIT MODAL --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">

        {{-- Modal Card Container --}}
        <div class="bg-white w-full max-w-md mx-4 p-6 rounded-2xl shadow-xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-800">
                    {{ $editMode ? 'Edit Contact' : 'Add New Contact' }}
                </h2>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-4 mb-6">

                {{-- CATEGORY (FIXED SEARCHABLE DROPDOWN) --}}
                <div class="relative" x-data="{ open: false, search: $wire.entangle('categorySearch') }" @click.away="open = false">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Category
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            x-model="search"
                            @focus="open = true"
                            @input.debounce.300ms="$wire.dispatch('render')"
                            placeholder="Type to search category..."
                            class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div
                        x-show="open"
                        x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto"
                        style="display: none;">

                        @if($categories->count() > 0)
                        @foreach($categories as $cat)
                        <button
                            type="button"
                            @click="$wire.categoryId = '{{ $cat->id }}'; search = '{{ $cat->name }}'; open = false;"
                            class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            {{ $cat->name }}
                        </button>
                        @endforeach
                        @else
                        <div class="px-4 py-2.5 text-sm text-slate-400 italic">
                            No categories found.
                        </div>
                        @endif
                    </div>

                    @error('categoryId')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- NAME --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" wire:model.defer="name" placeholder="John Doe"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" wire:model.defer="email" placeholder="example@mail.com"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- PHONE --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="tel" max="10" maxlength="10" wire:model.defer="phone" placeholder="2221234567"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">

                {{-- Cancel Button --}}
                <button wire:click="closeModal"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors duration-150">
                    Cancel
                </button>

                {{-- Save / Update Button --}}
                <button wire:click="save"
                    class="px-5 py-2 text-sm font-semibold text-white rounded-xl shadow-sm transition-all duration-150 
                {{ $editMode ? 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-blue-100' : 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 shadow-emerald-100' }}">
                    {{ $editMode ? 'Update Contact' : 'Save Contact' }}
                </button>

            </div>
        </div>
    </div>
    @endif

    {{-- DELETE MODAL --}}

    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">

        {{-- Modal Card Container --}}
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-sm mx-4 transform transition-all border border-slate-100 text-center animate-in fade-in zoom-in-95 duration-200">

            {{-- Warning Icon --}}
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 mb-4 border border-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            {{-- Content --}}
            <h3 class="text-base font-bold text-slate-900 mb-1">Delete Contact</h3>
            <p class="text-sm text-slate-500 mb-6 px-2">
                Are you sure you want to delete this contact? This action cannot be undone.
            </p>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-center gap-3">

                {{-- Cancel Button --}}
                <button wire:click="closeModal"
                    class="flex-1 px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors duration-150">
                    Cancel
                </button>

                {{-- Delete Button --}}
                <button wire:click="delete"
                    class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 active:bg-red-800 shadow-sm shadow-red-200 transition-colors duration-150">
                    Delete
                </button>

            </div>

        </div>

    </div>
    @endif

</div>