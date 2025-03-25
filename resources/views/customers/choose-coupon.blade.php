@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Assign Coupon</a>
            </li>
        </ol>
    </nav>
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    padding: '2em',
                });

                toast.fire({
                    icon: 'success',
                    text: "{{ session('success') }}",
                    padding: '2em',
                });
            });
        </script>
        @endif
    <!-- Outer Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-md p-6">
        <h1 class="text-xl font-bold mb-4 dark:text-white">
            Manage Coupons for {{ $customer->name }}
        </h1>

    

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex -mb-px">
                <li class="mr-4">
                    <button onclick="showTab('assignTab')" id="assignTabBtn"
                        class="py-2 px-4 border-b-2 border-blue-500 text-blue-500 font-semibold">
                        Assign Coupon
                    </button>
                </li>
                <li>
                    <button onclick="showTab('editTab')" id="editTabBtn"
                        class="py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-semibold">
                        Edit Coupons
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="assignTab" class="tab-content">
            <!-- Form to assign a single coupon -->
            <form 
                action="{{ route('customers.assignCoupon', $customer->id) }}" 
                method="POST" 
                class="space-y-3"
            >
                @csrf
                <div>
                    <label for="coupon_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                        Select a Coupon:
                    </label>
                    <select 
                        name="coupon_id" 
                        id="coupon_id" 
                        class="border border-gray-300 dark:border-gray-600 p-2 w-full rounded dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">-- Choose Coupon --</option>
                        @foreach($allCoupons as $coupon)
                            <option value="{{ $coupon->id }}">
                                {{ $coupon->code }} ({{ $coupon->discount }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-3 mt-4">
                    <!-- Assign Button -->
                    <button type="submit" class="assign-btn">Assign</button>

                    <!-- Back Link -->
                    <a href="{{ route('customers.index') }}" class="back-btn">Back</a>
                </div>
            </form>
        </div>

        <div id="editTab" class="tab-content hidden">
            <!-- List Already Assigned Coupons with Edit/Remove options -->
            <h2 class="text-lg font-semibold mb-2 dark:text-white">Assigned Coupons</h2>
            @if($customer->coupons->count() > 0)
                <ul class="space-y-3">
                    @foreach($customer->coupons as $c)
                        <li class="flex items-center justify-between dark:text-gray-100 border p-3 rounded">
                            <div>
                                <strong>{{ $c->code }}</strong>
                                <span class="ml-2">(Discount: {{ $c->discount }}%)</span>
                            </div>
                            <div class="flex space-x-3">
                                <!-- Edit Link -->
                                <a href="{{ route('customers.editCoupon', [$customer->id, $c->id]) }}" 
                                   class="text-blue-500 hover:underline">
                                    Edit
                                </a>
                                <!-- Remove Form -->
                                <form action="{{ route('customers.removeCoupon', [$customer->id, $c->id]) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to remove this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Remove</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-300">No coupons assigned yet.</p>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for Tab Handling -->
<script>
    function showTab(tabId) {
        // Hide all tab content divs
        document.querySelectorAll('.tab-content').forEach(function(el) {
            el.classList.add('hidden');
        });
        // Remove active classes from tab buttons
        document.getElementById('assignTabBtn').classList.remove('border-blue-500', 'text-blue-500');
        document.getElementById('assignTabBtn').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('editTabBtn').classList.remove('border-blue-500', 'text-blue-500');
        document.getElementById('editTabBtn').classList.add('border-transparent', 'text-gray-500');
        
        // Show selected tab
        document.getElementById(tabId).classList.remove('hidden');
        
        // Set active styling on the selected tab button
        if(tabId === 'assignTab'){
            document.getElementById('assignTabBtn').classList.add('border-blue-500', 'text-blue-500');
            document.getElementById('assignTabBtn').classList.remove('border-transparent', 'text-gray-500');
        } else if(tabId === 'editTab'){
            document.getElementById('editTabBtn').classList.add('border-blue-500', 'text-blue-500');
            document.getElementById('editTabBtn').classList.remove('border-transparent', 'text-gray-500');
        }
    }

    // Show the assign tab by default
    showTab('assignTab');
</script>

<!-- CSS for Buttons and Modal (same as before) -->
<style>
    .assign-btn {
        padding: 0.5rem 1rem;
        background-color: #2563EB;
        color: #FFFFFF;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    .assign-btn:hover {
        background-color: #1D4ED8;
    }
    .back-btn {
        padding: 0.5rem 1rem;
        background-color: #D1D5DB;
        color: #111827;
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .back-btn:hover {
        background-color: #E5E7EB;
        color: #000000;
    }
    /* Optional additional styling for tabs */
    .tab-content {
        padding-top: 1rem;
    }
</style>
@endsection
