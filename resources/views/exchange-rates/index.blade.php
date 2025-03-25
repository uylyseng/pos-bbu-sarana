@extends('layouts.app')

@section('content')

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
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="{{ route('exchange-rates.index') }}" class="text-primary">Exchange Rates</a>
            </li>
        </ol>
    </nav>

    <!-- Add New Exchange Rate Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Exchange Rates List</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold">Add New</span>
        </button>
    </div>

    <!-- Exchange Rates Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">From Currency</th>
                    <th class="px-4 py-2">To Currency</th>
                    <th class="px-4 py-2">Rate</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exchangeRates as $exchangeRate)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2">{{ $exchangeRate->id }}</td>
                    <td class="px-4 py-2">{{ $exchangeRate->fromCurrency->name }} ({{ $exchangeRate->fromCurrency->code }})</td>
                    <td class="px-4 py-2">{{ $exchangeRate->toCurrency->name }} ({{ $exchangeRate->toCurrency->code }})</td>
                    <td class="px-4 py-2">{{ $exchangeRate->rate }}</td>
                    <td class="px-4 py-2">{{ $exchangeRate->date }}</td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal({{ json_encode($exchangeRate) }})">
                        <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> Edit
                        </button>
                       
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $exchangeRates->links('layouts.pagination') }}
    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
   >
    <!-- Create/Edit Exchange Rate Modal -->
    <div id="exchangeRateModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white">Create New Exchange Rate</h2>
        <form id="exchangeRateForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">From Currency</label>
                <select name="currency_from" id="currencyFrom" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">To Currency</label>
                <select name="currency_to" id="currencyTo" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Exchange Rate</label>
                <input type="text" name="rate" id="exchangeRate" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Date</label>
                <input type="date" name="date" id="exchangeRateDate" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray">Cancel</button>
                <button type="submit" class="btn-green" id="saveButton">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
   function openCreateModal() {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('exchangeRateModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = 'Create New Exchange Rate';
    document.getElementById('exchangeRateForm').action = "{{ route('exchange-rates.store') }}";
    document.getElementById('formMethod').value = "POST";

    // Reset form fields
    document.getElementById('currencyFrom').value = '';
    document.getElementById('currencyTo').value = '';
    document.getElementById('exchangeRate').value = '';
    document.getElementById('exchangeRateDate').value = '';
    document.getElementById('saveButton').innerText = 'Save';

}

function openEditModal(exchangeRate) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('exchangeRateModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = 'Edit Exchange Rate';
    
    // Set form action for update
    document.getElementById('exchangeRateForm').action = `/exchange-rates/${exchangeRate.id}`;
    document.getElementById('formMethod').value = "PUT";

    // Fill form fields with existing data
    document.getElementById('currencyFrom').value = exchangeRate.currency_from;
    document.getElementById('currencyTo').value = exchangeRate.currency_to;
    document.getElementById('exchangeRate').value = exchangeRate.rate;
    document.getElementById('exchangeRateDate').value = exchangeRate.date;
    document.getElementById('saveButton').innerText = 'Update';
}

function closeModal() {
    document.getElementById('exchangeRateModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
    setTimeout(() => {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }, 300);
}

</script>


@endsection
