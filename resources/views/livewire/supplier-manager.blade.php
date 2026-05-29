<div>
    <div class="my-print-section">


        <style>
            .print-preview-fixed-footer {
                display: none;
            }

            @media print {

                .no-print,
                .toolbar,
                .pagination,
                .no-print-element {
                    display: none !important;
                    height: 0 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }

                html,
                body {
                    height: auto !important;
                    overflow: visible !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: white !important;
                }

                .my-print-section {

                    padding: 5mm 0mm 5mm 0mm !important;
                    overflow: visible !important;
                    height: auto !important;
                    width: 100% !important;
                }

                table {
                    width: 100% !important;
                    table-layout: auto !important;
                    page-break-inside: auto !important;
                }

                tr {
                    page-break-inside: avoid !important;
                    page-break-after: auto !important;
                }

                thead {
                    display: table-header-group !important;
                }

                th,
                td {
                    border: 1px solid #ddd !important;
                    padding: 8px 10px !important;
                    font-size: 11px !important;
                }

                th {
                    background-color: #f5f5f5 !important;
                }

                @page {
                    size: A4;
                    margin: 10mm 10mm 20mm 10mm;

                    @bottom-left {
                        content: "Page " counter(page) " of " counter(pages);
                        font-size: 10px;
                        color: #374151;
                        font-weight: bold;
                    }

                    @bottom-right {
                        content: "{{ auth()->user()->name ?? '' }}";
                        font-size: 10px;
                        color: #374151;
                        font-weight: bold;
                    }
                }


                .print-preview-fixed-footer {
                    display: none !important;
                }
            }
        </style>

        {{-- FLASH MESSAGE --}}
        @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-4 no-print flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- PRINT HEADER --}}
        <div class="print-header mb-5">
            <h1 class="text-xl font-bold text-gray-800">Supplier List</h1>
        </div>

        {{-- TOOLBAR (SEARCH & BUTTONS) --}}
        <div class="toolbar no-print flex flex-wrap items-center gap-3 mb-6">

            {{-- Search Input --}}
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search suppliers..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition" />
            </div>

            {{-- Print Button --}}
            <button wire:click="printAll" wire:loading.attr="disabled"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span wire:loading wire:target="printAll">Loading Data...</span>
                <span wire:loading.remove wire:target="printAll">Print</span>
            </button>

            {{-- Add Button --}}
            <button wire:click="openCreateModal"
                class="ml-auto flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Supplier
            </button>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden print:border-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider w-12">#</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Phone</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Address</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Created</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-400 uppercase tracking-wider no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $supplier->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">

                                    <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0 no-print-element">
                                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $supplier->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $supplier->email }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $supplier->phone }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs max-w-[180px] truncate" title="{{ $supplier->address }}">{{ $supplier->address }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $supplier->created_at->format('d M Y') }}</td>

                            <td class="px-4 py-3 no-print">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openEditModal({{ $supplier->id }})"
                                        class="w-7 h-7 rounded-md flex items-center justify-center text-blue-500 hover:bg-blue-50 transition" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $supplier->id }})"
                                        class="w-7 h-7 rounded-md flex items-center justify-center text-red-400 hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-sm">No suppliers found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        {{-- SHOWING INFO --}}
        <p class="no-print text-xs text-gray-400 mt-3">
            @if($suppliers instanceof \Illuminate\Pagination\LengthAwarePaginator)
            Showing {{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} suppliers
            @else
            Showing all {{ $suppliers->count() }} suppliers for printing
            @endif
        </p>

        {{-- PAGINATION LINKS --}}
        @if($suppliers instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4 no-print pagination">
            {{ $suppliers->links() }}
        </div>
        @endif

        {{-- ADD / EDIT MODAL --}}
        @if ($showModal)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 no-print"
            x-data x-init="$el.focus()"
            @keydown.escape.window="$wire.closeModal()">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" @click.outside="$wire.closeModal()">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-[15px] font-medium text-gray-800">{{ $editMode ? 'Edit Supplier' : 'Add New Supplier' }}</h2>
                    <button wire:click="closeModal" class="ml-auto text-gray-300 hover:text-gray-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-medium">Supplier Name <span class="text-red-400">*</span></label>
                        <input wire:model.defer="name" type="text" placeholder="Enter supplier name"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-medium">Email Address <span class="text-red-400">*</span></label>
                        <input wire:model.defer="email" type="email" placeholder="email@example.com"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-medium">Phone Number <span class="text-red-400">*</span></label>
                        <input wire:model.defer="phone" type="text" placeholder="+94 77 000 0000"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition {{ $errors->has('phone') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-medium">Address <span class="text-red-400">*</span></label>
                        <textarea wire:model.defer="address" rows="3" placeholder="Enter full address"
                            class="w-full px-3 py-2.5 text-sm border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition resize-none {{ $errors->has('address') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"></textarea>
                        @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-white transition">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="px-5 py-2 text-sm font-medium bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition disabled:opacity-60">
                        <span wire:loading wire:target="save">Saving...</span>
                        <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update Supplier' : 'Save Supplier' }}</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- DELETE CONFIRMATION MODAL --}}
        @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 no-print">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xs text-center" @click.outside="$wire.closeModal()">
                <div class="px-6 pt-6 pb-2">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Delete Supplier?</h3>
                    <p class="text-xs text-gray-400 mb-4">This action cannot be undone.</p>
                </div>
                <div class="flex gap-3 px-6 pb-6">
                    <button wire:click="closeModal" class="flex-1 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="delete" wire:loading.attr="disabled" class="flex-1 px-4 py-2 text-sm font-medium bg-red-500 hover:bg-red-600 text-white rounded-xl transition disabled:opacity-60">
                        <span wire:loading wire:target="delete">Deleting...</span>
                        <span wire:loading.remove wire:target="delete">Delete</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>