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
    <div class="flex flex-col items-center justify-center min-h-screen p-6">
        <!-- Login container -->
        <div
            class="bg-black bg-opacity-10 backdrop-blur-lg border border-white/15 rounded-xl shadow-xl max-w-xl w-full p-6 space-y-6 mb-5">
            <div class="text-5xl font-semibold text-center mb-8 text-emerald-200">IRG Order Desk Tools</div>

            <form method="POST" action="{{ route('auth.login') }}">
                @csrf
                <div class="space-y-6">
                    
                    <!-- Agent Dropdown -->
                    <div>
                        <label for="agent" class="block text-2xl font-medium text-emerald-200">Agent</label>
                        <select name="agent_id"
                            class="mt-2 bg-opacity-20 text-xl border-none outline-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-cyan-500 focus:text-gray block w-full p-4 bg-slate-900 text-white"
                            required>
                            <option value="" disabled selected>Select an Agent</option>
                            @foreach ($agents as $agent)
                            <option value="{{ $agent->AgentID }}"
                                class="bg-cyan-950 bg-opacity-90 border-none outline-none  text-white hover:text-white hover:shadow-lg hover:shadow-blue-500 hover:bg-cyan-950">
                                {{ $agent->Agent }}
                            </option>
                            @endforeach


                        </select>

                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" autocomplete="password" class="block text-2xl font-medium text-emerald-200">Password</label>
                        <input type="password" id="password" name="password"
                            class="mt-2 bg-opacity-20 text-xl rounded-lg border-none outline-none focus:ring-2 focus:ring-teal-500 focus:border-cyan-500 w-full p-4 bg-slate-900  placeholder-white text-white"
                            placeholder="Enter your password" maxlength="4" required />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit"
                        class="w-full bg-teal-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium rounded-lg text-xl py-3 transition duration-300  hover:bg-teal-700 dark:focus:ring-blue-800">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        window.onload = function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
                customClass: 'bg-cyan-700 text-white',  // Black background
                color: '#ffffff', // White text color
                confirmButtonColor: '#00b5ad', // Teal button color
            });
        }
    </script>
@endif

</html>