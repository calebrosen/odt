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
    <div class="flex flex-col items-center justify-center gap-12 py-12">
        <!-- Main container for the title -->
        @if(session('agentName'))
            <div class="bg-black bg-opacity-20 backdrop-blur-lg border border-white/15 rounded-xl shadow-xl max-w-xl w-full p-6 space-y-6">
                <h1 class="text-6xl font-semibold text-center text-white">Welcome, {{ session('agentName') }}</h1>
            </div>
            

            <!-- Buttons container -->
            <div class="flex flex-row gap-8 justify-center font-semibold">
                <button class="bg-cyan-700 hover:scale-105 hover:bg-blue-800 focus:ring-4 text-2xl focus:outline-none focus:ring-blue-300 text-white rounded-lg text-lg py-5 p-8 transition duration-300  hover:bg-teal-700 dark:focus:ring-blue-800">Make a Coupon</button>
                <button class="bg-cyan-700 hover:scale-105 hover:bg-blue-800 focus:ring-4 text-2xl focus:outline-none focus:ring-blue-300 text-white rounded-lg text-lg py-5 p-8 transition duration-300  hover:bg-teal-700 dark:focus:ring-blue-800">Release a Product</button>
            </div>
        @endif
    </div>
</body>





</html>