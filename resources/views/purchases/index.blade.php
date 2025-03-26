@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchases') }}</a>
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
    <!-- Header & Add New Purchase Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 dongrek-font">
        <h2 class="text-xl font-semibold dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchases_list') }}</h2>
        <a href="{{ route('purchases.create') }}" class="btn btn-green btn-sm">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Add New') }}</span>
        </a>
    </div>

    <!-- Purchases Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr class="dongrek-font">
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.id') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.date') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.supplier') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.reference') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.discount') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.subtotal') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.grand_total') }}</th>
                    <th class="px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Status') }}</th>
                    <th class="px-4 py-2 text-center {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-2 dark:text-white">{{ $purchase->id }}</td>
                    <td class="px-4 py-2 dark:text-white">{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : __('text.na') }}</td>

                    <td class="px-4 py-2 dark:text-white">
                        {{ $purchase->supplier ? $purchase->supplier->name : __('text.na') }}
                    </td>
                    <td class="px-4 py-2 dark:text-white">{{ $purchase->reference }}</td>

                    <td class="px-4 py-2 dark:text-white">{{ $purchase->discount }}</td>
                    <td class="px-4 py-2 dark:text-white">{{ number_format($purchase->subtotal, 2) }}</td>
                    <td class="px-4 py-2 dark:text-white">{{ number_format($purchase->total, 2) }}</td>
                    <td class="px-4 py-2">
                    <span class="inline-block px-3 py-1 rounded-full border-2 font-bold
                        {{ $purchase->status === 'Received' ? 'border-green-600 text-green-600' : 'border-red-600 text-red-600' }}">
                        {{ $purchase->status === 'Received' ? __('text.received') : __('text.pending') }}
                    </span>
                </td>

                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                        <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> {{ __('text.Edit') }}
                        </a>

                        <button class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700" onclick="confirmDelete('{{ route('purchases.destroy', $purchase->id) }}')">
                                <i class="fa-solid fa-trash mr-1" style="color: red;"></i> {{ __('text.Delete') }}
                            </button>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div >
        {{  $purchases->links('layouts.pagination') }}
    </div>
    <!-- Pagination -->

</div>
<script>
    async function confirmDelete(deleteUrl) {
        const result = await Swal.fire({
            icon: 'warning',
            title: '{{ __("text.are_you_sure") }}',
            text: '{{ __("text.this_action_cannot_be_undone") }}',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("text.yes_delete_it") }}',
            cancelButtonText: '{{ __("text.cancel") }}',
        });

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
    }
</script>

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
@endsection
