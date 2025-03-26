@extends('layouts.app')

@section('content')
<style>
    /* Import Dongrek font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');

    .dongrek-font {
        font-family: 'Dangrek', 'Arial', sans-serif;
        letter-spacing: 0.01em;
        font-feature-settings: "kern" 1;
        text-rendering: optimizeLegibility;
        font-weight: 500;
    }
</style>

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

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">{{ __('coupons.home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">{{ __('coupons.coupons') }}</a>
            </li>
        </ol>
    </nav>

    <!-- Add New Coupon Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 dongrek-font">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0">{{ __('coupons.coupons_list') }}</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold">{{ __('coupons.add_new') }}</span>
        </button>
    </div>

    <!-- Coupons Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
            <table class="w-full whitespace-nowrap shadow-sm table-auto">
                <thead class="bg-blue-500 text-white">
                <tr class="dongrek-font">
                    <th class="px-4 py-2">{{ __('coupons.id') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.code') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.discount') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.start_date') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.expire_date') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.status') }}</th>
                    <th class="px-4 py-2">{{ __('coupons.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2">{{ $coupon->id }}</td>
                    <td class="px-4 py-2">{{ $coupon->code }}</td>
                    <td class="px-4 py-2">{{ number_format($coupon->discount, 0) }}%</td>
                    <td class="px-4 py-2">
                        {{ $coupon->start_date ? $coupon->start_date->format('Y-m-d ') : __('coupons.na') }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $coupon->expire_date ? $coupon->expire_date->format('Y-m-d ') : __('coupons.na') }}
                    </td>

                    <td class="align-middle px-4 py-2 dongrek-font">
                    <span class="inline-block px-3 py-1 rounded-full border-2 font-bold {{ $coupon->status == 'active' ? 'border-green-600 text-green-600' : 'border-red-600 text-red-600' }}">
                        {{ $coupon->status == 'active' ? __('coupons.active') : __('coupons.inactive') }}
                    </span>
                </td>
                    <td class="px-4 py-2 text-center dongrek-font">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2"
                            onclick="openEditModal({{ json_encode($coupon) }})">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> {{ __('coupons.edit') }}
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded"
                            onclick="confirmDelete('{{ route('coupons.destroy', $coupon->id) }}')">
                            <i class="fa-solid fa-trash" style="color: red;"></i> {{ __('coupons.delete') }}
                        </button>
                    </td>
                </tr>
                @endforeach

                @if($coupons->count() === 0)
                <tr>
                    <td colspan="7" class="text-center py-2 text-gray-500">
                        {{ __('coupons.no_coupons_found') }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->


<!-- Pagination -->
@if ($coupons->total() > 0)
<div>
    {{ $coupons->links('layouts.pagination') }}
</div>
@else
    <p>{{ __('coupons.no_coupons_available') }}</p>
@endif
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"   class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
>
    <!-- Create/Edit Coupon Modal -->
    <div id="couponModal"   class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dongrek-font">{{ __('coupons.create_new_coupon') }}</h2>
        <form id="couponForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <!-- Code Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dongrek-font">{{ __('coupons.code') }}</label>
                <input type="text" name="code" id="couponCode" class="form-input w-full px-3 py-2 border rounded-md" required>
            </div>

            <!-- Discount Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dongrek-font">{{ __('coupons.discount') }}</label>
                <input type="number" step="0.01" name="discount" id="couponDiscount" class="form-input w-full px-3 py-2 border rounded-md" required>
            </div>

            <!-- Start Date Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dongrek-font">{{ __('coupons.start_date') }}</label>
                <input type="datetime-local" name="start_date" id="couponStartDate" class="form-input w-full px-3 py-2 border rounded-md">
            </div>

            <!-- Expire Date Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dongrek-font">{{ __('coupons.expire_date') }}</label>
                <input type="datetime-local" name="expire_date" id="couponExpireDate" class="form-input w-full px-3 py-2 border rounded-md">
            </div>

            <!-- Status Field -->
            <div class="mb-3 dongrek-font">
                <label class="block text-sm font-medium mb-1">{{ __('coupons.status') }}</label>
                <select name="status" id="couponStatus" class="form-select w-full px-3 py-2 border rounded-md">
                    <option value="active">{{ __('coupons.active') }}</option>
                    <option value="inactive">{{ __('coupons.inactive') }}</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 mt-4 dongrek-font">
                <button type="button" onclick="closeModal()" class="btn-gray">{{ __('coupons.cancel') }}</button>
                <button type="submit" class="btn-green">{{ __('coupons.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('couponModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('coupons.create_new_coupon') }}';
        document.getElementById('couponForm').action = "{{ route('coupons.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('couponCode').value = '';
        document.getElementById('couponDiscount').value = '';
        document.getElementById('couponStartDate').value = '';
        document.getElementById('couponExpireDate').value = '';
        document.getElementById('couponStatus').value = 'active';
    }

    function openEditModal(coupon) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('couponModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('coupons.edit_coupon') }}';
        document.getElementById('couponForm').action = `/coupons/${coupon.id}`;
        document.getElementById('formMethod').value = "PUT";

        // Populate fields
        document.getElementById('couponCode').value = coupon.code;
        document.getElementById('couponDiscount').value = coupon.discount;

        // Convert your start/expire date to datetime-local format if not null
        if (coupon.start_date) {
            let startDate = new Date(coupon.start_date);
            document.getElementById('couponStartDate').value = formatDateTimeLocal(startDate);
        } else {
            document.getElementById('couponStartDate').value = '';
        }

        if (coupon.expire_date) {
            let expireDate = new Date(coupon.expire_date);
            document.getElementById('couponExpireDate').value = formatDateTimeLocal(expireDate);
        } else {
            document.getElementById('couponExpireDate').value = '';
        }

        document.getElementById('couponStatus').value = coupon.status;
    }

    function closeModal() {
        document.getElementById('couponModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: '{{ __('coupons.are_you_sure') }}',
            text: "{{ __('coupons.this_action_cannot_be_undone') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __('coupons.yes_delete_it') }}',
            cancelButtonText: '{{ __('coupons.cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Helper: Format JS date object to YYYY-MM-DDTHH:MM for datetime-local
    function formatDateTimeLocal(dateObj) {
        let year = dateObj.getFullYear();
        let month = String(dateObj.getMonth() + 1).padStart(2, '0');
        let day = String(dateObj.getDate()).padStart(2, '0');
        let hours = String(dateObj.getHours()).padStart(2, '0');
        let minutes = String(dateObj.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
</script>

<!-- CSS for Styling -->

@endsection
