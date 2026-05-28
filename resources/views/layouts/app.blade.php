<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Inbizsys - Supplier Management' }}</title>

    {{-- Tailwind CSS v4 via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900 print:h-auto print:overflow-visible print:bg-white">


    <div class="flex h-screen overflow-hidden print:block print:h-auto print:w-full print:overflow-visible print:[&_div]:block print:[&_main]:block print:[&_section]:block print:[&_div]:[position:static] print:[&_main]:[position:static] print:[&_section]:[position:static]">

        {{-- SIDEBAR --}}
        <aside class="w-60 flex-shrink-0 flex flex-col bg-white overflow-y-auto border-r border-slate-200 print:hidden [*::-webkit-scrollbar]:w-1 [*::-webkit-scrollbar-track]:bg-transparent [*::-webkit-scrollbar-thumb]:bg-slate-900/5 [*::-webkit-scrollbar-thumb]:rounded-sm">
            <div class="px-5 py-5 flex items-center gap-3 border-b border-slate-100">
                <a href="/suppliers" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Inbizsys Logo" class="h-14 w-auto object-contain">
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 bg-slate-50/50 rounded-2xl">
                <p class="px-3 pb-2 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-slate-100/80 mb-3">Main Menu</p>

                <a href="/suppliers"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm border transition-all duration-200 ease-in-out 
                    {{ request()->is('suppliers*') ? 'bg-slate-200 text-slate-900 border-slate-300' : 'bg-white text-slate-700 border-slate-100/80 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 {{ request()->is('suppliers*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Suppliers
                </a>


                <a href="/categories"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm border transition-all duration-200 ease-in-out 
                    {{ request()->is('categories*') ? 'bg-slate-200 text-slate-900 border-slate-300' : 'bg-white text-slate-700 border-slate-100/80 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 {{ request()->is('categories*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Category
                </a>

                
                <a href="/contacts"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm border transition-all duration-200 ease-in-out 
                    {{ request()->is('contacts*') ? 'bg-slate-200 text-slate-900 border-slate-300' : 'bg-white text-slate-700 border-slate-100/80 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 {{ request()->is('contacts*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Contact
                </a>
            </nav>

            {{-- LOGOUT SECTION --}}
            <div class="px-4 py-5 border-t border-slate-100 bg-white sticky bottom-0 z-10">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl bg-red-50 text-red-700 text-sm font-semibold border border-red-100/80 hover:bg-red-100 hover:text-red-800 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col overflow-hidden print:block print:h-auto print:w-full print:max-w-full print:overflow-visible">

            {{-- Top Header --}}
            <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between flex-shrink-0 print:hidden">
                <div>
                    <h1 class="text-base font-bold text-slate-800">{{ $title ?? 'Supplier Management' }}</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Application / {{ $title ?? 'Suppliers' }}</p>
                </div>
            </header>

            {{-- Dynamic Component Injection --}}
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 print:p-0 print:m-0 print:w-full print:max-w-full print:bg-white print:overflow-visible">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Livewire Scripts --}}
    @livewireScripts

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        window.addEventListener('trigger-print', () => {
            setTimeout(() => {

                const userName = "{{ auth()->user()->name ?? 'Unknown' }}";
                const styleEl = document.createElement('style');
                styleEl.id = 'dynamic-print-style';
                styleEl.innerHTML = `
                @page {
                    size: A4;
                    margin: 15mm 15mm 25mm 15mm;
                }
                @media print {
                    .print-preview-fixed-footer { display: none !important; }
                    body::after {
                        content: "";
                    }
                }
            `;

                const pageStyle = document.createElement('style');
                pageStyle.innerHTML = `
                @page {
                    @bottom-left { content: "Page " counter(page); font-size: 10pt; font-weight: bold; color: #374151; }
                    @bottom-right { content: "${userName}"; font-size: 10pt; font-weight: bold; color: #374151; }
                }
            `;
                document.head.appendChild(pageStyle);

                window.print();

                setTimeout(() => document.head.removeChild(pageStyle), 2000);

                if (window.Livewire) {
                    const el = document.querySelector('[wire\\:id]');
                    if (el) {
                        const component = Livewire.find(el.getAttribute('wire:id'));
                        if (component) component.call('afterPrint');
                    }
                }
            }, 800);
        });
    </script>


</body>

</html>