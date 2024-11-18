<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Desk Tools</title>
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gradient-to-r from-sky-900 to-teal-700 font-sans text-white">
    <div class="flex flex-col items-center justify-center gap-12 p-8">
        <!-- Main container for the title -->
        @if(session('agentName'))
            <div class="bg-black bg-opacity-20 backdrop-blur-lg border border-white/15 rounded-xl shadow-xl max-w-xl w-full p-6 mt-16 space-y-6">
                <h1 class="text-6xl font-semibold text-center text-white">Welcome, {{ session('agentName') }}</h1>
            </div>

            <!-- Buttons container -->
            <div class="flex flex-row gap-8 justify-center font-semibold">
                <a href="/coupons">
                    <button class="bg-cyan-700 hover:scale-105 hover:bg-blue-800 focus:ring-4 text-2xl focus:outline-none focus:ring-blue-300 text-white rounded-lg  p-5 transition duration-300  hover:bg-teal-700 dark:focus:ring-blue-800">Make a Coupon</button>
                </a>
                <a href="/releases">
                    <button class="bg-cyan-700 hover:scale-105 hover:bg-blue-800 focus:ring-4 text-2xl focus:outline-none focus:ring-blue-300 text-white rounded-lg  p-5 transition duration-300  hover:bg-teal-700 dark:focus:ring-blue-800">Release a Product</button>
                </a>
            </div>
        @endif
    </div>
</body>

@if(session('agentID'))
<div class="fixed top-5 left-5">
    <button id="logoutButton" class="bg-cyan-700 hover:scale-105 hover:bg-blue-800 bg-opacity-50 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white rounded-lg py-2 px-3 transition duration-300 hover:bg-teal-700 dark:focus:ring-blue-800">
        <span class="text-lg inline-block">Logout</span>
        <svg class="h-4 w-4 text-white-500 inline-block mb-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />  <polyline points="16 17 21 12 16 7" />  <line x1="21" y1="12" x2="9" y2="12" /></svg>
    </button>
</div>
<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        fetch("{{ route('logout.button') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
        })
        .then(response => {
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
@endif

</html>