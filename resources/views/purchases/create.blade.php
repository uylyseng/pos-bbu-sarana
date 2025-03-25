@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
<nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Add Purchases</a>
            </li>
        </ol>
    </nav>
    <h2 class="text-2xl font-semibold mb-4 dark:text-white">Add Purchases</h2>
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <!-- Purchase Details Container -->
        <div class="bg-white rounded shadow p-6 dark:bg-[#1b2e4b] mb-6">
            <h3 class="text-xl font-semibold mb-4 dark:text-white">Purchase Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date: defaults to today's date but user can change -->
                <div>
                    <label class="block text-sm font-semibold dark:text-white">Date *</label>
                    <input type="date" 
                           name="purchase_date" 
                           id="purchase_date" 
                           class="form-input mt-1 block w-full" 
                           value="{{ date('Y-m-d') }}" 
                           required>
                </div>

                <!-- Reference: auto-generate if left blank -->
                <div>
                    <label class="block text-sm font-semibold dark:text-white">Reference *</label>
                    <input type="text" 
                           name="reference" 
                           id="reference" 
                           class="form-input mt-1 block w-full" 
                           placeholder="Auto-generate if left blank" 
                           value="{{ old('reference') }}">
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-semibold dark:text-white">Supplier *</label>
                    <select name="supplier_id" 
                            id="supplier_id" 
                            class="form-select mt-1 block w-full" 
                            required>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold dark:text-white">Status</label>
                    <select name="status" 
                            id="status" 
                            class="form-select mt-1 block w-full">
                        <option value="Received">Received</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-semibold dark:text-white">Payment Method</label>
                    <select name="payment_method_id" 
                            id="payment_method_id" 
                            class="form-select mt-1 block w-full">
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                <label class="block text-sm font-semibold dark:text-white">Details</label>
                <textarea name="details" 
                          id="details" 
                          class="form-input mt-1 block w-full" 
                          rows="3" 
                          placeholder="Additional details..."></textarea>
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
                placeholder="Search Product Name or Barcode"
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
            <h3 class="text-xl font-semibold mb-4 dark:text-white">Purchase Items</h3>

            <!-- Search Input and Suggestions Dropdown (one div above the table) -->
            

            <!-- Purchase Items Table -->
            <div class="overflow-x-auto mb-4">
                <table class="w-full whitespace-nowrap shadow-sm table-auto">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2 text-left">Product Name</th>
                            <th class="p-2 text-left">Qty</th>
                            <th class="p-2 text-left">Discount</th>
                            <th class="p-2 text-left">Units</th>
                            <th class="p-2 text-left">Cost</th>
                            <th class="p-2 text-left">Subtotal</th>
                            <th class="p-2 text-left">Remove</th>
                        </tr>
                    </thead>
                    <tbody id="product-list"></tbody>
                </table>
            </div>

            <!-- Details (one div under the table) -->
           
            <div class="separator"></div>
            <!-- Subtotal, Discount, Grand Total (below the table) -->
            <div class="space-y-1 text-right mb-6">
    <div>
        <span class="font-semibold mr-2">Subtotal:</span>
        <span id="subtotal-amount">0.00</span>
    </div>
    <div>
        <span class="font-semibold mr-2">Discount:</span>
        <span id="discount-amount">0.00</span>
    </div>
    <div>
        <span class="font-bold mr-2">Grand Total:</span>
        <span id="grandtotal-amount">0.00</span>
    </div>
</div>

<div class="flex justify-end space-x-3">
    <!-- Create Purchase button -->
    <button type="submit" class="btn-green px-6 py-2 rounded">Create Purchase</button>
    <!-- Cancel button (link) -->
    <a href="{{ route('purchases.index') }}"
       class="btn-red px-6 py-2 rounded">
       Cancel
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

    // Add a product row to the table
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
                <!-- This "Subtotal" cell shows the row's final after discount -->
                <td class="p-2 subtotal">0.00</td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-btn text-red-500">
                        <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">
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
            let qty      = parseFloat(row.querySelector('.qty').value) || 0;
            let cost     = parseFloat(row.querySelector('.cost').value) || 0;
            let discount = parseFloat(row.querySelector('.discount').value) || 0;

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
            event.target.matches('.qty') ||
            event.target.matches('.cost') ||
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

    // Auto-generate reference if empty
    document.addEventListener("DOMContentLoaded", function () {
        let referenceInput = document.getElementById("reference");
        function generateReference() {
            // Generates P100 - P999
            return "P" + String(Math.floor(Math.random() * 900) + 100);
        }
        if (!referenceInput.value) {
            referenceInput.value = generateReference();
        }
    });
</script>

<style>
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
        color: #000;
    }
</style>
@endsection
