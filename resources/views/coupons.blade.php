<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Coupons - Order Desk Tools</title>
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
        <div class="bg-black bg-opacity-10 backdrop-blur-lg border border-white/15 rounded-xl mt-1 shadow-xl max-w-xl w-full p-6 space-y-6">
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
            <input type="number" required id="amountInput" class="text-white py-3 mt-4 px-4 bg-teal-500 bg-opacity-60 text-3xl rounded-lg placeholder:text-teal-50 placeholder:font-semibold border-none outline-none focus:ring-2 focus:ring-teal-500 focus:border-cyan-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" max="{{ session('couponBalanceActual') }}" placeholder="Coupon Amount" autofocus>
            <button type="submit" onClick="confirmCreateCoupon()" class="text-white py-3 mt-4 px-4 bg-teal-500 text-3xl rounded-lg placeholder:text-teal-50 placeholder:text-center cursor-pointer font-bold hover:bg-teal-600 transition duration-300 placeholder:font-semibold border border-white/15 focus:outline-none focus:border-white/55 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">Create</button>
        </div>
        <table class="table-auto bg-black bg-opacity-10 backdrop-blur-lg rounded-xl mt-1 shadow-xl border border-white/15 p-2 my-2 border-separate">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left rounded-tl-xl">Store</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">Code</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">Discount</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">Creation Date</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">Order ID</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left rounded-tr-xl">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currentCoupons as $coupon)
                <tr>
                    <td class="py-3 px-4 text-xl text-left">{{ $coupon->store }}</td>
                    <td class="py-3 px-4 text-xl text-left">{{ $coupon->code }}</td>
                    <td class="py-3 px-4 text-xl text-left">{{ $coupon->discount }}</td>
                    <td class="py-3 px-4 text-xl text-left">{{ $coupon->date_added }}</td>
                    @if ($coupon->order_id == 0)
                        <td class="py-3 px-4 text-xl text-left">No order placed</td>
                        <td class="py-3 px-4 text-xl text-left text-sky-500 hover:text-sky-600 active:text-sky-600 cursor-pointer" data-custom-coupon-code="{{ $coupon->code }}" data-custom-coupon-store="{{ $coupon->store }}" onClick="deleteCoupon(this)">Delete</td>
                    @else
                        <td class="py-3 px-4 text-xl text-left">{{ $coupon->order_id }}</td>
                        <td class="py-3 px-4 text-xl text-left">Order placed</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>


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
        fetch("{{ route('auth.logout') }}", {
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

        // Resetting value if it's not a number
        if (isNaN(value)) {
            this.value = '';
        }

        // Putting maxValue to 0 if it's negative
        if (maxValue < 0) {
            maxValue = 0;
        }

        // If a number is inputted that is above maxValue, setting value to maxValue
        if (value > maxValue) {
            this.value = maxValue;
        }

        // If a negative number is entered, setting value to 0
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

        if (selectedStore !== '' && amount) {
            Swal.fire({
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: "Create",
                denyButtonText: "Cancel",
                title: `Coupon for $${amount} on ${selectedStore}`,
                text: "Are you sure that you want to create this coupon?",
                customClass: 'bg-cyan-700 text-white text-xl',
                color: '#ffffff',
                confirmButtonColor: '#00b5ad'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, posting to create the coupon
                    fetch("{{ route('coupons.create') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: JSON.stringify({
                                couponAmount: amount,
                                store: selectedStore,
                                agentID: "{{ session('agentID') }}"
                            })
                        })
                        .then(response => {
                            return response.text();
                        })
                        .then(responseText => {
                            try {
                                const data = JSON.parse(responseText);
                                if (data.success && data.data[0].CouponCode) {
                                    copyToClipboard(data.data[0].CouponCode);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 8000);
                                } else {
                                    alert('Failed to create coupon.');
                                }
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                alert('Response is not valid JSON.');
                            }
                        })
                        .catch(error => {
                            console.error('Error creating coupon:', error);
                            alert('An error occurred.');
                        });
                }
            })
        }
    }

    function deleteCoupon(tdElement) {
        const couponCode = tdElement.getAttribute('data-custom-coupon-code');
        const selectedStore = tdElement.getAttribute('data-custom-coupon-store');
        if (couponCode && selectedStore) {
            Swal.fire({
                title: `Delete code ${couponCode}?`,
                text: `Are you sure you want to delete coupon ${couponCode} on ${selectedStore}?`,
                icon: "warning",
                showCancelButton: true,
                customClass: 'bg-cyan-700 text-white text-xl',
                color: '#ffffff',
                confirmButtonColor: '#00b5ad'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('coupons.delete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: JSON.stringify({
                                store: selectedStore,
                                couponCode: couponCode
                            })
                        })
                        .then(response => {
                            console.log(response);
                            return response.text();
                        })
                        .then(responseText => {
                            try {
                                const data = JSON.parse(responseText);
                                console.log(data);
                                if (data.success) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: `Coupon code ${couponCode} on ${selectedStore} has been deleted.`,
                                        icon: "success",
                                        customClass: 'bg-cyan-700 text-white text-xl',
                                        color: '#ffffff',
                                        timer: 3000,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        timerProgressBar: true
                                    });
                                    location.reload();
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Failed to delete coupon",
                                        icon: "error",
                                        customClass: 'bg-cyan-700 text-white text-xl',
                                        color: '#ffffff',
                                        timer: 3000
                                    });
                                }
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                alert('Response is not valid JSON.');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting coupon:', error);
                            alert('An error occurred.');
                        });
                }
                });
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                Swal.fire({
                        icon: 'success',
                        title: `Code copied to clipboard`,
                        text: `Coupon code ${text} has been copied to the clipboard.`,
                        customClass: 'bg-cyan-700 text-white text-xl',
                        timerProgressBar: true,
                        color: '#ffffff',
                        confirmButtonColor: '#00b5ad',
                        timer: 7500
                    })
                    .catch(err => {
                        console.error('Failed to copy: ', err);
                    });
            })
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</html>