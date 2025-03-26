@extends('layouts.app')

@section('content')
<div class="container {{ app()->getLocale() == 'km' ? 'khmer-dangrek' : '' }}">
    <h1>{{ __('customers.customers') }}: {{ $customer->name }}</h1>
    <p>{{ __('customers.contact_info') }}: {{ $customer->contact_info }}</p>

    <hr class="my-4">

    <!-- Form to Assign a Coupon -->
    <form action="{{ route('customers.assignCoupon', $customer->id) }}" method="POST">
        @csrf
        <label for="coupon_id" class="block mb-1">{{ __('customers.select_coupon') }}</label>
        <select name="coupon_id" id="coupon_id" class="border p-2 w-full mb-3">
            <option value="">{{ __('customers.choose_coupon') }}</option>
            @foreach($allCoupons as $coupon)
                <option value="{{ $coupon->id }}">{{ $coupon->code }} ({{ $coupon->discount }})</option>
            @endforeach
        </select>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            {{ __('customers.assign_coupon') }}
        </button>
    </form>

    <!-- Display Already Assigned Coupons -->
    <h2 class="mt-6 mb-2 text-xl font-semibold">{{ __('customers.assigned_coupons') }}</h2>
    @if($customer->coupons->count() > 0)
        <ul class="list-disc ml-4">
            @foreach($customer->coupons as $coupon)
                <li>
                    {{ $coupon->code }} ({{ $coupon->discount }})
                    <!-- Optionally a button to detach -->
                    <form action="{{ route('customers.detachCoupon', [$customer->id, $coupon->id]) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline ml-2">
                            {{ __('customers.remove') }}
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">{{ __('customers.no_coupons_assigned') }}</p>
    @endif
</div>
@endsection
