@extends('layouts.master')

@section('content')
    <div class="container ">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Edit Product</a>
            </li>
        </ol>
    </nav>

        <div class="card shadow p-6 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Edit Product</h2>
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Main Grid: Two columns -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- LEFT COLUMN: PRODUCT DETAILS -->
                    <div class="bg-white p-4 rounded-lg shadow-sm dark:bg-gray-800">
                        <!-- Barcode Field with Generate Button -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Barcode</label>
                            <div class="flex items-center">
                                <input type="text" name="barcode" id="barcodeInput" class="form-input w-full px-3 py-2 border rounded-md" value="{{ old('barcode', $product->barcode) }}">
                                <button type="button" class="btn-magic ml-2 flex items-center justify-center rounded-md p-2" onclick="generateBarcode()">
                                    <img src="{{ asset('icons/magic.png') }}" alt="Generate Barcode" class="w-6 h-6">

                                </button>
                            </div>
                        </div>
                        <!-- Name Fields -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Name (EN)</label>
                            <input type="text" name="name_en" class="form-input w-full px-3 py-2 border rounded-md" value="{{ old('name_en', $product->name_en) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Name (KH)</label>
                            <input type="text" name="name_kh" class="form-input w-full px-3 py-2 border rounded-md" value="{{ old('name_kh', $product->name_kh) }}">
                        </div>
                        <!-- Base Price -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Base Price</label>
                            <input type="number" name="base_price" step="0.01" class="form-input w-full px-3 py-2 border rounded-md" value="{{ old('base_price', $product->base_price) }}" required>
                        </div>
                        <!-- Category -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Category</label>
                            <select name="category_id" class="form-select w-full dark:bg-gray-700 dark:text-white">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Unit -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Sale Unit</label>
                            <select name="sale_unit_id" class="form-select w-full dark:bg-gray-700 dark:text-white">
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('sale_unit_id', $product->sale_unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="purchaseUnitField" class="mb-3 {{ $product->is_stock === 'have_stock' ? '' : 'hidden' }}">
    <label class="block text-sm font-medium dark:text-white">Purchase Unit</label>
    <select name="purchase_unit_id" class="form-select w-full dark:bg-gray-700 dark:text-white">
        <option value="">Select Purchase Unit</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ old('purchase_unit_id', $product->purchase_unit_id) == $unit->id ? 'selected' : '' }}>
                {{ $unit->name }}
            </option>
        @endforeach
    </select>
</div>


                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium dark:text-white">Image</label>
                            <input type="file" name="image" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name_en }}" class="mt-2 h-16">
                            @endif
                        </div>

                        <!-- Product Options: Stock Options (Left Switch Container) -->
                        <div class="switch-container-box p-4 border rounded-lg bg-gray-100 shadow-md mt-6">
                            <h3 class="text-lg font-semibold mb-3 text-gray-700">Stock Options</h3>
                            <div class="flex flex-wrap gap-6">
                                <!-- Is Stock Switch -->
                                <label class="switch-container">
                                    <input type="checkbox" id="isStockSwitch" onchange="updateIsStockSwitchValue('isStockSwitch', 'isStockHidden')" {{ $product->is_stock === 'have_stock' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                    <span class="label-text">Is Stock</span>
                                </label>
                                <!-- Active Switch -->
                                <label class="switch-container">
                                    <input type="checkbox" id="activeSwitch" onchange="updateActiveSwitchValue('activeSwitch', 'activeHidden')" {{ $product->active === 'active' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                    <span class="label-text">Active</span>
                                </label>
                            </div>
                            <!-- Hidden Fields for Stock Options -->
                            <input type="hidden" name="is_stock" id="isStockHidden" value="{{ $product->is_stock }}">
                            <input type="hidden" name="active" id="activeHidden" value="{{ $product->active }}">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: DYNAMIC TABLES & Variant Options -->
                    <div class="space-y-6">
                        <!-- Dynamic Tables Container -->
                        <div class="bg-white p-4 rounded-lg shadow-sm dark:bg-gray-800">
                            <!-- Stock Table (Shown/hidden by Is Stock switch) -->
                            <div id="stockTable" class="{{ $product->is_stock === 'have_stock' ? '' : 'hidden' }}">
                                <h3 class="text-md font-semibold mb-2 dark:text-white"><b>Stock Information</b></h3>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium dark:text-white">Low Stock Level</label>
                                    <input type="number" name="low_stock" class="form-input w-full px-3 py-2 border rounded-md" value="{{ old('low_stock', $product->low_stock) }}">
                                </div>
                            </div>

                            <!-- Size Table (Shown/hidden by Has Size switch) -->
                            <div id="sizeTable" class="{{ $product->has_size === 'has' ? '' : 'hidden' }}">
                                <h3 class="text-md font-semibold mb-2 dark:text-white">Sizes</h3>
                                <table class="w-full border border-gray-300 mt-2">
                                    <thead class="bg-gray-200 dark:bg-gray-600" style="color: blue;">
                                        <tr>
                                            <th class="border px-4 py-2">Size</th>
                                            <th class="border px-4 py-2">Price</th>
                                            <th class="border px-4 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sizeTableBody">
                                        @foreach($product->sizes as $ps)
                                        <tr>
                                            <td class="border px-4 py-2">
                                                <input type="hidden" name="size_ids[]" value="{{ $ps->size->id }}">
                                                {{ $ps->size->name }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="number" name="size_prices[]" value="{{ $ps->price }}" step="0.01" class="form-input w-full px-3 py-2 border rounded-md">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <button type="button" class="btn-red" onclick="removeRow(this)">
                                                    <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">

                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button"  class="btn-green mt-2" onclick="addSizeRow()">+ Add Size</button>
                            </div>

                            <!-- Topping Table (Shown/hidden by Has Topping switch) -->
                            <div id="toppingTable" class="{{ $product->has_topping === 'has' ? '' : 'hidden' }}">
                                <h3 class="text-md font-semibold mb-2 dark:text-white">Toppings</h3>
                                <table class="w-full border border-gray-300 mt-2">
                                    <thead class="bg-gray-200 dark:bg-gray-600" style="color: blue;">
                                        <tr>
                                            <th class="border px-4 py-2">Topping</th>
                                            <th class="border px-4 py-2">Price</th>
                                            <th class="border px-4 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="toppingTableBody">
                                        @foreach($product->toppings as $pt)
                                        <tr>
                                            <td class="border px-4 py-2">
                                                <input type="hidden" name="topping_ids[]" value="{{ $pt->topping->id }}">
                                                {{ $pt->topping->name }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="number" name="topping_prices[]" value="{{ $pt->price }}" step="0.01" class="form-input w-full px-3 py-2 border rounded-md">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <button type="button" class="btn-red" onclick="removeRow(this)">
                                                    <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">

                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn-green mt-2" onclick="addToppingRow()">+ Add Topping</button>
                            </div>
                        </div>

                        <!-- Variant Options Switches (Right Column) -->
                        <div class="switch-container-box p-4 border rounded-lg bg-gray-100 shadow-md mt-6">
                            <h3 class="text-lg font-semibold mb-3 text-gray-700">Variant Options</h3>
                            <div class="flex flex-wrap gap-6">
                                <!-- Has Size Switch -->
                                <label class="switch-container">
                                    <input type="checkbox" id="hasSizeSwitch" onchange="updateHasSizeSwitchValue('hasSizeSwitch', 'hasSizeHidden', 'sizeTable')" {{ $product->has_size === 'has' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                    <span class="label-text">Has Size</span>
                                </label>
                                <input type="hidden" name="has_size" id="hasSizeHidden" value="{{ $product->has_size }}">
                                <!-- Has Topping Switch -->
                                <label class="switch-container">
                                    <input type="checkbox" id="hasToppingSwitch" onchange="updateHasToppingSwitchValue('hasToppingSwitch', 'hasToppingHidden', 'toppingTable')" {{ $product->has_topping === 'has' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                    <span class="label-text">Has Topping</span>
                                </label>
                                <input type="hidden" name="has_topping" id="hasToppingHidden" value="{{ $product->has_topping }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-gray">Cancel</a>
                    <button type="submit" class="btn btn-blue">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function generateBarcode() {
            let barcode = Math.floor(100000000000 + Math.random() * 900000000000); // 12-digit random number
            document.getElementById("barcodeInput").value = barcode;
        }

        function updateActiveSwitchValue(switchId, hiddenId) {
            const switchEl = document.getElementById(switchId);
            const hiddenInput = document.getElementById(hiddenId);
            hiddenInput.value = switchEl.checked ? "active" : "inactive";
        }

        function updateIsStockSwitchValue(switchId, hiddenId) {
    const switchEl = document.getElementById(switchId); // The is_stock switch (checkbox)
    const hiddenInput = document.getElementById(hiddenId); // Hidden input storing the is_stock value
    const stockSection = document.getElementById('stockTable'); // Section that holds the stock-related fields
    const purchaseUnitField = document.getElementById('purchaseUnitField'); // The purchase unit field

    // Update the value of the hidden input based on the checkbox state
    hiddenInput.value = switchEl.checked ? "have_stock" : "none_stock";

    // Toggle the visibility of the stock section and purchase unit field
    if (switchEl.checked) {
        stockSection && stockSection.classList.remove("hidden"); // Show stock section
        purchaseUnitField && purchaseUnitField.classList.remove("hidden"); // Show purchase unit field
    } else {
        stockSection && stockSection.classList.add("hidden"); // Hide stock section
        purchaseUnitField && purchaseUnitField.classList.add("hidden"); // Hide purchase unit field
    }
}


        function updateHasSizeSwitchValue(switchId, hiddenId, sectionId) {
            const switchEl = document.getElementById(switchId);
            const hiddenInput = document.getElementById(hiddenId);
            hiddenInput.value = switchEl.checked ? "has" : "none";
            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (switchEl.checked) {
                    section.classList.remove("hidden");
                } else {
                    section.classList.add("hidden");
                }
            }
        }

        function updateHasToppingSwitchValue(switchId, hiddenId, sectionId) {
            const switchEl = document.getElementById(switchId);
            const hiddenInput = document.getElementById(hiddenId);
            hiddenInput.value = switchEl.checked ? "has" : "none";
            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (switchEl.checked) {
                    section.classList.remove("hidden");
                } else {
                    section.classList.add("hidden");
                }
            }
        }

        function removeRow(button) {
            button.closest('tr').remove();
        }

        function loadToppings() {
            let groupId = document.getElementById("toppingGroupSelect").value;
            let toppingTable = document.getElementById("toppingTableBody");
            toppingTable.innerHTML = "";
            if (groupId) {
                fetch(`/get-toppings/${groupId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(topping => {
                            let row = document.createElement("tr");
                            row.innerHTML = `
                                <td class="border px-4 py-2">${topping.name}</td>
                                <td class="border px-4 py-2">
                                    <input type="number" name="topping_prices[]" value="${topping.price}" step="0.01" class="form-input w-full px-3 py-2 border rounded-md">
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    <button type="button" class="btn-red" onclick="removeRow(this)">
                                        <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">

                                    </button>
                                </td>
                            `;
                            toppingTable.appendChild(row);
                        });
                    });
            }
        }

        function addSizeRow() {
            let sizeTableBody = document.getElementById("sizeTableBody");
            let row = document.createElement("tr");
            row.innerHTML = `
                <td class="border px-4 py-2">
                    <select name="size_ids[]" class="form-select w-full dark:bg-gray-700 dark:text-white">
                        <option value="">Select Size</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="border px-4 py-2">
                    <input type="number" name="size_prices[]" step="0.01" value="0" class="form-input w-full px-3 py-2 border rounded-md">
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="btn-red" onclick="removeRow(this)">
                        <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">

                    </button>
                </td>
            `;
            sizeTableBody.appendChild(row);
        }

        function addToppingRow() {
            let toppingTable = document.getElementById("toppingTableBody");
            let row = document.createElement("tr");
            row.innerHTML = `
                <td class="border px-4 py-2">
                    <select name="topping_ids[]" class="form-select w-full dark:bg-gray-700 dark:text-white">
                        <option value="">Select Topping</option>
                        @foreach($toppings as $topping)
                            <option value="{{ $topping->id }}">{{ $topping->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="border px-4 py-2">
                    <input type="number" name="topping_prices[]" step="0.01" value="0" class="form-input w-full px-3 py-2 border rounded-md">
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="btn-red" onclick="removeRow(this)">
                        <img src="{{ asset('icons/delete.png') }}" alt="Remove" class="w-6 h-6">

                    </button>
                </td>
            `;
            toppingTable.appendChild(row);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const toggleDarkMode = document.getElementById("darkModeToggle");
            const body = document.body;
            if (localStorage.getItem("dark-mode") === "enabled") {
                body.classList.add("dark");
            }
            toggleDarkMode.addEventListener("click", function() {
                body.classList.toggle("dark");
                localStorage.setItem("dark-mode", body.classList.contains("dark") ? "enabled" : "disabled");
            });
        });
    </script>

    <!-- CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        body.dark {
            background-color: #1e293b;
            color: #cbd5e1;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease-in-out;
        }

        body.dark .card {
            background-color: #0f172a;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: all 0.3s ease-in-out;
        }

        body.dark .form-input,
        body.dark .form-select {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: white;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #007bff;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease-in-out;
        }

        .btn-blue {
            background-color: #007bff;
            color: white;
        }

        .btn-blue:hover {
            background-color: #0069d9;
        }

        .btn-gray {
            background-color: #6c757d;
            color: white;
        }

        .btn-gray:hover {
            background-color: #5a6268;
        }

        .btn-green {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease-in-out;
        }

        .btn-magic {
            background-color: #cbd5e1;
            color: white;
            width: 40px;
            height: 40px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .switch-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            gap: 10px;
        }

        .switch-container input {
            width: 0;
            height: 0;
            opacity: 0;
            position: absolute;
        }

        .slider {
            position: relative;
            width: 40px;
            height: 20px;
            background: #ddd;
            border-radius: 15px;
            transition: 0.3s;
        }

        .slider::before {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            top: 1px;
            left: 2px;
            transition: 0.3s;
        }

        .switch-container input:checked+.slider {
            background: #28a745;
        }

        .switch-container input:checked+.slider::before {
            transform: translateX(20px);
        }

        .label-text {
            font-size: 14px;
            color: #333;
        }

        body.dark .label-text {
            color: #cbd5e1;
        }

        .switch-container-box {
            background: #f9fafb;
            border: 1px solid #e2e8f0;
            padding: 16px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        body.dark th {
            background-color: #1e293b;
            color: #cbd5e1;
        }

        .dark-mode-toggle {
            cursor: pointer;
            background-color: #333;
            color: white;
            border-radius: 50%;
            padding: 6px 10px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .dark-mode-toggle:hover {
            background-color: #555;
        }
    </style>
@endsection