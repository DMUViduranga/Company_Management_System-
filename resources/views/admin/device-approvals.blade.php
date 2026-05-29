<x-app-layout>
    <x-slot:title>Device Approvals</x-slot:title>

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pending Device Approvals</h2>
                <p class="text-sm text-slate-500 mt-1">You can authorize new devices attempting to access the system from here.</p>
            </div>
        </div>

        {{-- Empty State Placeholder Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-100">
                {{-- Laptop/Mobile Icon --}}
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>
        </div>
    </div>
</x-app-layout>