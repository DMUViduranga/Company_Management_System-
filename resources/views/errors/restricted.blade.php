<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted</title>

    {{-- Tailwind CSS v4 via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center border-t-4 border-red-500">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-800 mb-2">Access Restricted</h1>
        <p class="text-gray-600 text-sm mb-4">You can only access this system during official working hours.</p>

        <div class="bg-gray-50 p-4 rounded text-xs text-left space-y-2 text-gray-700 mb-5">
            <div>⏰ <span class="font-semibold">Current Time:</span> {{ $currentTime }}</div>
            <div>💼 <span class="font-semibold">Allowed Hours:</span> {{ $accessStart }} - {{ $accessEnd }}</div>
            <div class="text-red-600 font-medium">⏳ <span class="font-semibold">Next Access:</span> {{ $nextAccess }}</div>
        </div>

        <a href="/" class="inline-block bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition">
            Go to Home
        </a>
    </div>

</body>
</html>