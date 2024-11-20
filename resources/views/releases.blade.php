<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Release a Product - Order Desk Tools</title>
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
                <input type="radio" required id="store_{{ $store->ms_short_name }}" onClick="searchClass.setStore(this.value)" name="storeInput" value="{{ $store->ms_short_name }}" class="peer hidden" />
                <!-- Custom styled label -->
                <label for="store_{{ $store->ms_short_name }}" class="cursor-pointer text-2xl inline-block bg-teal-500 text-white py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:bg-teal-600 peer-checked:bg-teal-700">
                    {{ $store->ms_short_name }}
                </label>
            </div>
            @endforeach
        </div>

        <!-- Main container for the title -->
        <div class="bg-black bg-opacity-10 backdrop-blur-xl border border-white/15 rounded-xl mt-1 shadow-xl max-w-xl w-full p-6 space-y-6 ">
            <h1 class="text-6xl font-semibold text-center text-white">Release a Product</h1>
        </div>

        <div class="flex items-center space-x-2 rounded-lg bg-black bg-opacity-10 py-2 px-4">
            <div class="w-10 h-10 cursor-pointer select-none rounded-full bg-teal-600 flex items-center justify-center text-white pb-2 font-semibold text-4xl" id="subtractQuantity">
                -
            </div>
            <div class="text-5xl mb-2 select-none" id="quantityCount">1</div>
            <div class="w-10 h-10 cursor-pointer select-none rounded-full bg-teal-600 flex items-center justify-center text-white pb-2 font-semibold text-4xl" id="addQuantity">
                +
            </div>
        </div>

        <div class="flex flex-row items-center justify-center gap-6">
            <input type="text" required id="amountInput" onInput="searchClass.setModel(this.value)" class="text-white py-3 mt-4 px-4 bg-teal-500 shadow-md bg-opacity-60 text-3xl rounded-lg placeholder:text-teal-50 placeholder:font-semibold outline-none border border-white/15 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="Filter By Model">
            <input type="text" required id="amountInput" onInput="searchClass.setName(this.value)" class="text-white py-3 mt-4 px-4 bg-teal-500 shadow-md bg-opacity-60 text-3xl rounded-lg placeholder:text-teal-50 placeholder:font-semibold outline-none border border-white/15 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="Filter By Name">
        </div>

        <table class="table-auto bg-black bg-opacity-10 backdrop-blur-lg rounded-xl mt-1 shadow-xl border border-white/15 p-2 my-2 border-separate">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left rounded-tl-xl">Model</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">MPN</th>
                    <th class="py-3 px-4 text-2xl text-emerald-100 text-left">Name</th>
                </tr>
            </thead>
            <tbody>

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
    class Search {
    constructor() {
        this.store = '';
        this.model = '';
        this.name = '';
        this.lastCallTime = 0;
        this.searchTimeout = null;
    }

    setStore(selectedStore) {
        this.store = selectedStore;
        this.searchHandler();
    }

    setModel(searchedModel) {
        this.model = searchedModel;
        this.searchHandler();
    }

    setName(searchedName) {
        this.name = searchedName;
        this.searchHandler();
    }

    searchHandler() {
        const currentTime = Date.now();
        const timeSinceLastCall = currentTime - this.lastCallTime;

        if (timeSinceLastCall >= 2500) {
            // Cooldown expired, searching
            this.lastCallTime = currentTime;
            this.searchForProduct();
        } else {
            // Cooldown is active, searching after timeout
            const remainingTime = 2500 - timeSinceLastCall;

            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(() => {
                this.lastCallTime = Date.now();
                this.searchForProduct();
            }, remainingTime);
        }
    }

    searchForProduct() {
        fetch("{{ route('releases.search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({
                    store: this.store,
                    couponAmount: this.model,
                    name: this.name
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
                        alert('Failed to search for product.');
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
}


    // Making class global
    const searchClass = new Search();
    window.searchClass = searchClass;


    // Decreasing quantity
    document.getElementById('subtractQuantity').addEventListener('click', function() {
        let currentQ = document.getElementById('quantityCount');
        if (parseInt(currentQ.textContent) - 1 >= 1) {
            currentQ.textContent = parseInt(currentQ.textContent) - 1;
        }
    });

    // Increasing quantity
    document.getElementById('addQuantity').addEventListener('click', function() {
        let currentQ = document.getElementById('quantityCount');
        if (parseInt(currentQ.textContent) + 1 < 100) {
            currentQ.textContent = parseInt(currentQ.textContent) + 1;
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
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</html>