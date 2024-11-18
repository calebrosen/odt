<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create a Coupon - Order Desk Tools</title>
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gradient-to-r from-sky-900 to-teal-700 font-sans text-white">
    <div class="flex flex-col items-center justify-center gap-6 py-12">
        @if(session('agentID'))
        <div class="flex flex-column text-xl font-semibold">
            @foreach ($stores as $store)
            <!-- Hidden input radio -->
            <div class="inline-block p-1">
                <input type="radio" required id="store_{{ $store->ms_short_name }}" name="storeInput" value="{{ $store->ms_short_name }}" class="peer hidden" />
                <!-- Custom styled label -->
                <label for="store_{{ $store->ms_short_name }}" class="cursor-pointer text-2xl inline-block bg-teal-500 text-white py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:bg-teal-600 peer-checked:bg-teal-700">
                    {{ $store->ms_short_name }}
                </label>
            </div>
            @endforeach
        </div>

        <!-- Main container for the title -->
        <div class="bg-black bg-opacity-20 backdrop-blur-lg border border-white/15 rounded-xl mt-1 shadow-xl max-w-xl w-full p-6 space-y-6">
            <h1 class="text-6xl font-semibold text-center text-white">Create a Coupon</h1>
            @if(session('couponsUsedPretty'))
            <div class="text-3xl font-semibold text-emerald-100 flex justify-between">
                <span>Coupons Used:</span>
                <span>{{ session('couponsUsedPretty') }}</span>
            </div>
            @endif
            @if(session('couponBalancePretty'))
            <div class="text-3xl font-semibold text-emerald-100 flex justify-between">
                <span>Coupon Balance:</span>
                <span>{{ session('couponBalancePretty') }}</span>
            </div>
            @endif
        </div>

        <div>
            <input type="number" required id="amountInput" class="text-white py-3 mt-4 px-4 bg-teal-500 bg-opacity-60 text-3xl rounded-lg placeholder:text-teal-50 placeholder:font-semibold border border-white/15 focus:outline-none focus:border-white/55 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" max="20" placeholder="Coupon Amount" autofocus>
            <button type="submit" onClick="confirmCreateCoupon()" class="text-white py-3 mt-4 px-4 bg-teal-500 text-3xl rounded-lg placeholder:text-teal-50 placeholder:text-center cursor-pointer font-bold hover:bg-teal-600 transition duration-300 placeholder:font-semibold border border-white/15 focus:outline-none focus:border-white/55 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">Create</button>
        </div>

        <div id='previousCoupons'>
        </div>
        @endif
    </div>
</body>


@if(session('agentID'))
<div class="fixed top-5 left-5">
    <button id="logoutButton" class="bg-cyan-700 hover:scale-105 bg-opacity-50 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white rounded-lg py-2 px-3 transition duration-300 hover:bg-teal-700 dark:focus:ring-blue-800">
        <span class="text-lg inline-block">Logout</span>
        <svg class="h-4 w-4 text-white-500 inline-block mb-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
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

<script>
    // Handling amountInput value
    document.getElementById('amountInput').addEventListener('input', function() {
        let maxValue = parseFloat(this.max);
        let value = parseFloat(this.value);

        //Resetting value if it's not a number
        if (isNaN(value)) {
            this.value = '';
        }

        //Putting maxValue to 0 if it's negative
        if (maxValue < 0) {
            maxValue = 0;
        }

        //If a number is inputted that is above maxValue, setting value to maxValue
        if (value > maxValue) {
            this.value = maxValue;
        }

        //If a negative number is entered, setting value to 0
        else if (value < 0) {
            this.value = 0;
        }
    });


    function confirmCreateCoupon() {
        let amount = document.getElementById('amountInput').value;
        let stores = document.querySelectorAll('input[type="radio"]');
        let selectedStore = '';
        for (let store of stores) {
            if (store.checked) {
                selectedStore = store.value;
            }
        }
        Swal.fire({
            icon: 'question',
            showDenyButton: true,
            confirmButtonText: "Create",
            denyButtonText: "Cancel",
            title: `Coupon for $${amount} on ${selectedStore}`,
            text: `Are you sure that you want to create this coupon?`,
            customClass: 'bg-cyan-700 text-white text-2xl',
            color: '#ffffff',
            confirmButtonColor: '#00b5ad',
        });

    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</html>