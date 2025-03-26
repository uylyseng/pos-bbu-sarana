@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6 {{ app()->getLocale() == 'km' ? 'khmer-dangrek' : '' }}">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">{{ __('customers.home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">{{ __('customers.assign_coupon') }}</a>
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
            {{ __('customers.assign_coupon_to') }} {{ $customer->name }}
        </h1>



        <!-- Form to assign a single coupon -->
        <form
            action="{{ route('customers.assignCoupon', $customer->id) }}"
            method="POST"
            class="space-y-3"
        >
            @csrf
            <div>
                <label for="coupon_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                    {{ __('customers.select_coupon') }}
                </label>
                <select
                    name="coupon_id"
                    id="coupon_id"
                    class="border border-gray-300 dark:border-gray-600 p-2 w-full rounded dark:bg-gray-700 dark:text-white"
                >
                    <option value="">{{ __('customers.choose_coupon') }}</option>
                    @foreach($allCoupons as $coupon)
                        <option value="{{ $coupon->id }}">
                            {{ $coupon->code }} ({{ $coupon->discount }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-3 mt-4">
                <!-- Assign Button -->
                <button type="submit" class="assign-btn">{{ __('customers.assign') }}</button>

                <!-- Back Link -->
                <a href="{{ route('customers.index') }}" class="back-btn">{{ __('customers.back') }}</a>
            </div>
        </form>

        <hr class="my-6 border-gray-300 dark:border-gray-600">

        <!-- List Already Assigned Coupons -->
        <h2 class="text-lg font-semibold mb-2 dark:text-white">{{ __('customers.assigned_coupons') }}</h2>
        @if($customer->coupons->count() > 0)
            <ul class="space-y-3">
                @foreach($customer->coupons as $c)
                    <li class="flex items-center justify-between dark:text-gray-100 border p-3 rounded">
                        <div>
                            <strong>{{ $c->code }}</strong>
                            <span class="ml-2">({{ __('customers.discount') }}: {{ $c->discount }}%)</span>
                        </div>
                        <div class="flex space-x-3">
                            <!-- Edit link -->
                            <a href="{{ route('customers.editCoupon', [$customer->id, $c->id]) }}" class="text-blue-500 hover:underline">
                                {{ __('customers.edit') }}
                            </a>
                            <!-- Remove form -->
                            <form action="{{ route('customers.removeCoupon', [$customer->id, $c->id]) }}" method="POST" onsubmit="return confirm('{{ __('customers.confirm_remove_coupon') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">{{ __('customers.remove') }}</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 dark:text-gray-300">{{ __('customers.no_coupons_assigned') }}</p>
        @endif
    </div>
</div>

<style>
    /* Assign Button */
    .action-links {
  display: flex;
  gap: 0.75rem; /* Same as space-x-3 in Tailwind */
  align-items: center;
}

/* Style the anchor links */
.action-link {
  color: #3b82f6;        /* Tailwind's 'blue-500' */
  text-decoration: none;
  transition: color 0.2s ease-in-out;
}
.action-link:hover {
  color: #1d4ed8;        /* Tailwind's 'blue-700' */
  text-decoration: underline;
}

/* Style the button in the form */
.action-button {
  background: none;
  border: none;
  padding: 0;
  color: #ef4444;        /* Tailwind's 'red-500' */
  cursor: pointer;
  font: inherit;         /* Keep the same font as surrounding text */
  transition: color 0.2s ease-in-out;
}
.action-button:hover {
  color: #dc2626;        /* Tailwind's 'red-600' */
  text-decoration: underline;
}

    .assign-btn {
        padding: 0.5rem 1rem;
        background-color: #2563EB; /* Tailwind's bg-blue-600 */
        color: #FFFFFF;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    .assign-btn:hover {
        background-color: #1D4ED8; /* Tailwind's bg-blue-700 */
    }
    /* Back Button */
    .back-btn {
        padding: 0.5rem 1rem;
        background-color: #D1D5DB; /* Tailwind's bg-gray-300 */
        color: #111827;           /* Tailwind's text-gray-900 */
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .back-btn:hover {
        background-color: #E5E7EB; /* Tailwind's bg-gray-200 */
        color: #000000;
    }
</style>
@endsection
