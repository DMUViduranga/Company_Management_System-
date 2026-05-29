<div>

    <h2 class="text-xl font-bold mb-5">User Access Times</h2>

    <table class="min-w-full bg-white rounded-xl overflow-hidden">

        <thead class="bg-slate-100">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Start Time</th>
                <th class="p-3 text-left">End Time</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>

        <tbody>

            @foreach($users as $user)

            <tr class="border-t">
                <td class="p-3">{{ $user->name }}</td>
                <td class="p-3">{{ $user->email }}</td>
                <td class="p-3">{{ $user->allowed_from }}</td>
                <td class="p-3">{{ $user->allowed_to }}</td>

                <td class="p-3">
                    <button
                        wire:click="openEditModal({{ $user->id }})"
                        class="px-3 py-1 bg-blue-500 text-white rounded-lg">
                        Edit
                    </button>
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

    {{-- EDIT ACCESS TIME MODAL --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">

        {{-- Modal Card Container --}}
        <div class="bg-white w-full max-w-md mx-4 p-6 rounded-2xl shadow-xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95 duration-200">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Edit Access Time
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-4 mb-6">

                {{-- START TIME --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Start Time
                    </label>
                    <div class="relative">
                        <input type="time"
                            wire:model="access_start_time"
                            class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                    @error('access_start_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- END TIME --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        End Time
                    </label>
                    <div class="relative">
                        <input type="time"
                            wire:model="access_end_time"
                            class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                    @error('access_end_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">

                {{-- Cancel Button --}}
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors duration-150">
                    Cancel
                </button>

                {{-- Save Button --}}
                <button wire:click="save"
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 shadow-sm shadow-blue-100 rounded-xl hover:bg-blue-700 active:bg-blue-800 transition-all duration-150">
                    Save Changes
                </button>

            </div>
        </div>
    </div>
    @endif

</div>