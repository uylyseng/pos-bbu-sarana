@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.edit_purchase') }}</a>
            </li>
        </ol>
    </nav>

    <h2 class="text-2xl font-semibold mb-4 dark:text-white dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.edit_purchase') }}</h2>

    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT for updating -->

        <!-- Purchase Details Container -->
        <div class="bg-white rounded shadow p-6 dark:bg-[#1b2e4b] mb-6">
            <h3 class="text-xl font-semibold mb-4 dark:text-white dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchase_details') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.date') }} *</label>
                    <input type="date"
                           name="purchase_date"
                           id="purchase_date"
                           class="form-input mt-1 block w-full"
                           value="{{ old('purchase_date', (new \DateTime($purchase->purchase_date))->format('Y-m-d')) }}"
                           required>
                </div>
                <!-- Reference -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.reference') }} *</label>
                    <input type="text"
                           name="reference"
                           id="reference"
                           class="form-input mt-1 block w-full"
                           placeholder="{{ __('text.auto_generate') }}"
                           value="{{ old('reference', $purchase->reference) }}">
                </div>
                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.supplier') }} *</label>
                    <select name="supplier_id" id="supplier_id" class="form-select mt-1 block w-full" required>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $supplier->id == old('supplier_id', $purchase->supplier_id) ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Status') }}</label>
                    <select name="status" id="status" class="form-select mt-1 block w-full">
                        <option value="Received"
                            {{ old('status', $purchase->status) == 'Received' ? 'selected' : '' }}>
                            {{ __('text.received') }}
                        </option>
                        <option value="Pending"
                            {{ old('status', $purchase->status) == 'pending' ? 'selected' : '' }}>
                            {{ __('text.pending') }}
                        </option>
                    </select>
                </div>
                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.payment_method') }}</label>
                    <select name="payment_method_id" id="payment_method_id" class="form-select mt-1 block w-full">
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}"
                                {{ $paymentMethod->id == old('payment_method_id', $purchase->payment_method_id) ? 'selected' : '' }}>
                                {{ $paymentMethod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Details -->
                <div>
                    <label class="block text-sm font-semibold dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.details') }}</label>
                    <textarea name="details" id="details" class="form-input mt-1 block w-full" rows="3"
                              placeholder="{{ __('text.additional_details') }}">{{ old('details', $purchase->details) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1b2e4b] rounded shadow p-6 mb-6">
    <div class="relative">
        <!-- Search input wrapper with icon on the right -->
        <div class="relative mb-4 items-end">
            <input
                type="text"
                id="search-input"
                class="form-input w-full pr-10"
                placeholder="{{ __('text.search_placeholder') }}"
            >
            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                <!-- Magnifying glass icon -->
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5a7 7 0 016.32 4.394A7 7 0 1111 5zM21 21l-4.35-4.35"></path>
                </svg>
            </span>
        </div>

        <!-- Suggestions dropdown -->
        <div
    id="suggestions"
    class="absolute z-9 bg-white dark:bg-[#1b2e4b] border border-gray-300 w-full"
    style="max-height: 200px; overflow-y: auto;"
>
    <!-- Suggestions appear here -->
</div>

    </div>
</div>
        <!-- Purchase Items Container -->
        <div class="bg-white rounded shadow p-6 dark:bg-[#1b2e4b]">
            <h3 class="text-xl font-semibold mb-4 dark:text-white dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchase_items') }}</h3>

            <!-- Search Input and Suggestions Dropdown -->


            <!-- Purchase Items Table -->
            <div class="overflow-x-auto mb-4">
                <table class="w-full whitespace-nowrap shadow-sm table-auto">
                    <thead class="bg-blue-500 text-white">
                        <tr class="dongrek-font">
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.product_name') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.qty') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.discount') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.units') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.cost') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.subtotal') }}</th>
                            <th class="p-2 text-left {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.remove') }}</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        @foreach($purchase->purchaseItems as $item)
                        <tr class="border-b">
                            <td class="p-2">
                                {{ $item->product->name_en }} ({{ $item->product->barcode }})
                                <input type="hidden"
                                       name="items[{{ $item->product_id }}][product_id]"
                                       value="{{ $item->product_id }}">
                            </td>
                            <td class="p-2">
                                <input type="number"
                                       class="form-input w-16 qty"
                                       name="items[{{ $item->product_id }}][quantity]"
                                       value="{{ $item->quantity }}"
                                       required>
                            </td
                            <td class="p-2">
                                <input type="number"
                                       class="form-input w-16 discount"
                                       name="items[{{ $item->product_id }}][discount]"
                                       value="{{ $item->discount }}">
                            </td>
                            <td class="p-2">
                                <select class="form-select w-24"
                                        name="items[{{ $item->product_id }}][purchase_unit_id]"
                                        required>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $unit->id == $item->purchase_unit_id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="number"
                                       class="form-input w-16 cost"
                                       name="items[{{ $item->product_id }}][unit_price]"
                                       value="{{ $item->unit_price }}"
                                       required>
                            </td>
                            <!-- Display final cost in "Subtotal" cell -->
                            <td class="p-2 subtotal">
                                <!-- (quantity * cost) - discount -->
                                {{ ($item->quantity * $item->unit_price) - $item->discount }}
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" class="remove-btn text-red-500">
                                    <img src="{{ asset('icons/delete.png') }}" alt="{{ __('text.remove') }}" class="w-6 h-6">
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="separator"></div>
                <!-- Subtotal, Discount, and Grand Total (below table) -->
                <div class="mt-4 space-y-1 text-right">
                    <div>
                        <span class="font-semibold mr-2 dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.subtotal') }}:</span>
                        <span id="subtotal-amount">0.00</span>
                    </div>
                    <div>
                        <span class="font-semibold mr-2 dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.discount') }}:</span>
                        <span id="discount-amount">0.00</span>
                    </div>
                    <div>
                        <span class="font-bold mr-2 dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.grand_total') }}:</span>
                        <span id="grandtotal-amount">0.00</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="submit" class="btn-green px-6 py-2 rounded dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.update_purchase') }}</button>
                <a href="{{ route('purchases.index') }}"
       class="btn-red px-6 py-2 rounded dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
       {{ __('text.cancel') }}
    </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Convert Blade data to JS
    var units = @json($units);

    // Listen for input events on the search field
    document.getElementById('search-input').addEventListener('input', function(event) {
        let query = this.value.trim();
        if (!query) {
            clearSuggestions();
            return;
        }
        fetch(`/products/search?query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(products => {
                if (Array.isArray(products) && products.length) {
                    showSuggestions(products);
                } else {
                    clearSuggestions();
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                clearSuggestions();
            });
    });

    // Display suggestions dropdown
    function showSuggestions(products) {
    let suggestionsBox = document.getElementById('suggestions');
    suggestionsBox.innerHTML = '';

    products.forEach(product => {
        let suggestionItem = document.createElement('div');
        // Use "cursor-pointer" if you want a pointer on hover (instead of "cursor-hover")
        suggestionItem.classList.add('p-2', 'cursor-pointer', 'hover:bg-blue-200');

        // Use innerHTML to style only the barcode
        suggestionItem.innerHTML = `
            ${product.name_en} -
            ${product.name_kh} -
           <span style="color: orange;">${product.barcode}</span>
        `;

        suggestionItem.addEventListener('click', function() {
            addProduct(product);
            clearSuggestions();
            document.getElementById('search-input').value = '';
        });
        suggestionsBox.appendChild(suggestionItem);
    });
}

    // Clear suggestions dropdown
    function clearSuggestions() {
        document.getElementById('suggestions').innerHTML = '';
    }

    // Add a new product row
    function addProduct(product) {
        let productList = document.getElementById('product-list');
        let options = '';
        units.forEach(function(unit) {
            options += `<option value="${unit.id}">${unit.name}</option>`;
        });
        let newRow = `
            <tr class="border-b">
                <td class="p-2">
                    ${product.name_en} (${product.barcode})
                    <input type="hidden" name="items[${product.id}][product_id]" value="${product.id}">
                </td>
                <td class="p-2">
                    <input type="number" class="form-input w-16 qty" name="items[${product.id}][quantity]" value="1" required>
                </td>
                <td class="p-2">
                    <input type="number" class="form-input w-16 discount" name="items[${product.id}][discount]" value="0">
                </td>
                <td class="p-2">
                    <select class="form-select w-24" name="items[${product.id}][purchase_unit_id]" required>
                        ${options}
                    </select>
                </td>
                <td class="p-2">
                    <input type="number" class="form-input w-16 cost" name="items[${product.id}][unit_price]" value="${product.cost}" required>
                </td>
                <td class="p-2 subtotal">0.00</td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-btn text-red-500">
                        <img src="{{ asset('icons/delete.png') }}" alt="{{ __('text.remove') }}" class="w-6 h-6">
                    </button>
                </td>
            </tr>
        `;
        productList.insertAdjacentHTML('beforeend', newRow);
        updateTotals();
    }

    // Calculate row subtotals and overall totals
    function updateTotals() {
        let totalSubtotal = 0;
        let totalDiscount = 0;
        let grandTotal    = 0;

        document.querySelectorAll('#product-list tr').forEach(row => {
            let qty      = parseFloat(row.querySelector('.qty')?.value)      || 0;
            let cost     = parseFloat(row.querySelector('.cost')?.value)     || 0;
            let discount = parseFloat(row.querySelector('.discount')?.value) || 0;

            // Row subtotal BEFORE discount
            let rowSubtotal = qty * cost;
            totalSubtotal += rowSubtotal;

            // Accumulate discount
            totalDiscount += discount;

            // Final cost for this row AFTER discount
            let rowFinal = rowSubtotal - discount;
            // Update row's displayed subtotal
            row.querySelector('.subtotal').textContent = rowFinal.toFixed(2);

            // Accumulate into grand total
            grandTotal += rowFinal;
        });

        // Update displayed totals
        document.getElementById('subtotal-amount').textContent   = totalSubtotal.toFixed(2);
        document.getElementById('discount-amount').textContent   = totalDiscount.toFixed(2);
        document.getElementById('grandtotal-amount').textContent = grandTotal.toFixed(2);
    }

    // Update totals when quantity, cost, or discount changes
    document.addEventListener('input', function(event) {
        if (
            event.target.matches('.qty')     ||
            event.target.matches('.cost')    ||
            event.target.matches('.discount')
        ) {
            updateTotals();
        }
    });

    // Remove product row when remove button is clicked
    document.addEventListener('click', function(event) {
        if (event.target.closest('.remove-btn')) {
            event.target.closest('tr').remove();
            updateTotals();
        }
    });

    // Recalculate totals on page load (so existing items are correct)
    document.addEventListener('DOMContentLoaded', function() {
        updateTotals();
    });
</script>

<style>
    .btn-red {
        background-color: red;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-red:hover {
        background-color: red;
    }
      .separator {
      border-bottom: 1px solid  #000;
      margin: 10px 0;
    }
    .btn-green {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-green:hover {
        background-color: #218838;
    }
    .form-input, .form-select {
        border: 1px solid #ccc;
        padding: 8px;
        width: 100%;
        border-radius: 4px;
    }
    .form-select {
        background-color: #f9f9f9;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        text-align: left;
        padding: 10px;

    }
    th {
        background-color: #007bff;
        color: white;
    }
    .remove-btn {
    background-color: transparent;
    /* Add a 1px solid border in a neutral color (e.g., #ccc) */
    border: 1px solid #ccc;
    padding: 4px 6px;
    cursor: pointer;
    border-radius: 4px; /* optional rounding */
}

.remove-btn:hover {
    color: #dc3545;
    /* Change the border color on hover, matching the text color */
    border-color: #dc3545;
}
    #suggestions {
        max-height: 200px;
        overflow-y: auto;
    }

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
