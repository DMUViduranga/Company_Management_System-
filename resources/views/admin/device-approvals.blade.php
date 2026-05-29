<x-app-layout>
    <x-slot:title>Device Approvals</x-slot:title>

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Device Approvals</h2>
                <p class="text-sm text-slate-500 mt-1">Manage all pending and approved devices from here.</p>
            </div>
        </div>

        {{-- Success Message Display --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif

        {{-- Combined Devices Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-4">User</th>
                        <th class="p-4">IP Address</th>
                        <th class="p-4">Browser / Device Detail</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($allDevices as $device)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4">
                            <div class="font-semibold text-slate-800">{{ $device->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $device->user->email }}</div>
                        </td>
                        <td class="p-4 font-mono text-slate-600 text-xs">{{ $device->ip_address }}</td>
                        <td class="p-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $device->browser_name }}">
                            {{ $device->browser_name }}
                        </td>
                        <td class="p-4">
                            @if($device->is_approved)
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-100">Approved</span>
                            @else
                                <span class="bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full border border-amber-100">Pending</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Approve Button --}}
                                @if(!$device->is_approved)
                                <button type="button"
                                        onclick="document.getElementById('approve-modal-{{ $device->id }}').showModal()"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm shadow-emerald-100 transition-all active:scale-95">
                                    Approve
                                </button>
                                @else
                                <button disabled
                                        class="bg-slate-100 text-slate-400 px-3 py-1.5 rounded-xl text-xs font-semibold cursor-not-allowed">
                                    Approve
                                </button>
                                @endif

                                {{-- Revoke Button --}}
                                @if($device->is_approved)
                                <button type="button"
                                        onclick="document.getElementById('revoke-modal-{{ $device->id }}').showModal()"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-xl text-xs font-semibold border border-red-100 transition-all">
                                    Revoke
                                </button>
                                @else
                                <button disabled
                                        class="bg-slate-100 text-slate-400 px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 cursor-not-allowed">
                                    Revoke
                                </button>
                                @endif

                            </div>

                            {{-- Approve Modal --}}
                            <dialog id="approve-modal-{{ $device->id }}" class="rounded-2xl p-0 backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm shadow-xl w-full max-w-sm overflow-hidden border border-slate-100 open:flex open:flex-col">
                                <div class="bg-white p-6 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 mb-4 border border-emerald-100">
                                        <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 mb-1">Approve Device</h3>
                                    <p class="text-sm text-slate-500 mb-6 px-2">
                                        Are you sure you want to approve device access for <span class="font-semibold text-slate-800">{{ $device->user->name }}</span>?
                                    </p>
                                    <div class="flex justify-center gap-3">
                                        <form method="dialog" class="flex-1">
                                            <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors duration-150">
                                                Cancel
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.devices.approve', $device->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 active:bg-emerald-800 shadow-sm shadow-emerald-200 transition-colors duration-150">
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>

                            {{-- Revoke Modal --}}
                            <dialog id="revoke-modal-{{ $device->id }}" class="rounded-2xl p-0 backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm shadow-xl w-full max-w-sm overflow-hidden border border-slate-100 open:flex open:flex-col">
                                <div class="bg-white p-6 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 mb-4 border border-red-100">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 mb-1">Revoke Device Access</h3>
                                    <p class="text-sm text-slate-500 mb-6 px-2">
                                        Are you sure you want to revoke access for <span class="font-semibold text-slate-800">{{ $device->user->name }}</span>? This user will be blocked.
                                    </p>
                                    <div class="flex justify-center gap-3">
                                        <form method="dialog" class="flex-1">
                                            <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors duration-150">
                                                Cancel
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.devices.revoke', $device->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 active:bg-red-800 shadow-sm shadow-red-200 transition-colors duration-150">
                                                Revoke
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-amber-100">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            </div>
                            <span class="text-sm text-slate-400 italic">No devices found.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>