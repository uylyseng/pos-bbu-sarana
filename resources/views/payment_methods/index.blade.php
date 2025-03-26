@extends('layouts.app')

@section('content')

<style>
    /* Add Dongrek font for Khmer text */
    @font-face {
        font-family: 'Dongrek';
        src: url('{{ asset('fonts/Dongrek/Dongrek-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    .khmer-font {
        font-family: 'Dongrek', sans-serif;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white dongrek-font">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">{{ __('payment_methods.home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">{{ __('payment_methods.payment_methods') }}</a>
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


    <!-- Add New Payment Method Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white khmer-font">{{ __('payment_methods.payment_methods_list') }}</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold khmer-font">{{ __('payment_methods.add_new') }}</span>
        </button>
    </div>

    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="table table-hover shadow-sm w-full">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 khmer-font">{{ __('payment_methods.id') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('payment_methods.name') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('payment_methods.description') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('payment_methods.amount') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('payment_methods.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $paymentMethod)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2">{{ $paymentMethod->id }}</td>
                    <td class="px-4 py-2">{{ $paymentMethod->name }}</td>
                    <td class="px-4 py-2">{{ $paymentMethod->descript }}</td>
                    <td class="px-4 py-2">{{ $paymentMethod->amount }}</td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal({{ json_encode($paymentMethod) }})">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> <span class="khmer-font">{{ __('payment_methods.edit') }}</span>
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('{{ route('payment_methods.destroy', $paymentMethod->id) }}')">
                            <i class="fa-solid fa-trash " style="color: red;"></i> <span class="khmer-font">{{ __('payment_methods.delete') }}</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $paymentMethods->links() }}
    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Payment Method Modal -->
    <div id="paymentMethodModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white khmer-font">{{ __('payment_methods.create_new_payment_method') }}</h2>
        <form id="paymentMethodForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">{{ __('payment_methods.payment_method_name') }}</label>
                <input type="text" name="name" id="paymentMethodName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">{{ __('payment_methods.description') }}</label>
                <textarea name="descript" id="paymentMethodDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray khmer-font">{{ __('payment_methods.cancel') }}</button>
                <button type="submit" class="btn-green khmer-font" id="saveButton">{{ __('payment_methods.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('paymentMethodModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('payment_methods.create_new_payment_method') }}';
        document.getElementById('paymentMethodForm').action = "{{ route('payment_methods.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('paymentMethodName').value = '';
        document.getElementById('paymentMethodDescription').value = '';
        document.getElementById('saveButton').innerText = '{{ __('payment_methods.save') }}';
    }

    function openEditModal(paymentMethod) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('paymentMethodModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('payment_methods.edit_payment_method') }}';
        document.getElementById('paymentMethodForm').action = `/payment_methods/${paymentMethod.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('paymentMethodName').value = paymentMethod.name;
        document.getElementById('paymentMethodDescription').value = paymentMethod.descript;
        document.getElementById('saveButton').innerText = '{{ __('payment_methods.update') }}';
    }

    function closeModal() {
        document.getElementById('paymentMethodModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        window.Swal.fire({
            title: '{{ __('payment_methods.are_you_sure') }}',
            text: '{{ __('payment_methods.this_action_cannot_be_undone') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('payment_methods.yes_delete_it') }}',
            cancelButtonText: '{{ __('payment_methods.cancel') }}',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection
