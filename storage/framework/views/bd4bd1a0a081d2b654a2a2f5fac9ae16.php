<?php $__env->startSection('content'); ?>

  <?php if(session('success')): ?>
    <script>
    Swal.fire({
    title: "Success!",
    text: "<?php echo session('success'); ?>",
    icon: "success",
    timer: 1000,
    showConfirmButton: false,
    });
    </script>
  <?php endif; ?>

  <?php if(session('info')): ?>
    <script>
    Swal.fire({
    title: "Info!",
    text: "<?php echo session('info'); ?>",
    icon: "info",
    timer: 1000,
    showConfirmButton: false,
    });
    </script>
  <?php endif; ?>
  <?php if(session('message')): ?>
    <script>
    Swal.fire({
    title: "message!",
    text: "<?php echo session('message'); ?>",
    icon: "message",
    timer: 1000,
    showConfirmButton: false,
    });
    </script>
  <?php endif; ?>

  <?php if(session('error')): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const toast = window.Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 5000,
      padding: '2em',
    });

    toast.fire({
      icon: 'error',
      text: "<?php echo e(session('error')); ?>",
      padding: '2em',
    });
    });
    </script>
  <?php endif; ?>

  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
    <!-- Left Sidebar (Order Summary & Items) -->
    <div class="w-full md:w-1/3 lg:w-1/3 bg-gray dark:bg-gray-800 p-2 shadow-md sticky top-0 
       h-screen overflow-y-auto rounded-[3px] flex flex-col">

    <div class="flex justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md shadow">
      <!-- Business Name -->
      <h1 class="text-base text-green-500 font-bold tracking-wide">
      SOUSDEY COFFEE
      </h1>

      <!-- Date & Icon -->
      <div class="flex items-center space-x-2 text-x font-semibold">
      <span><?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></span>
      <a href="<?php echo e(route('dashboard.cashier')); ?>" class="text-blue-500 hover:underline">
        <i class="fas fa-home text-blue-700"></i>
      </a>
      </div>
    </div>

    <!-- Customer Selector -->
    <div class="mb-2 p-2">
      <div class="flex flex-row space-x-4">
      <!-- Total People Input -->
      <div class="w-1/3">
        <input type="number" id="totalPeople" name="total_people"
        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 p-1 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
        placeholder="Enter number of people" value="1" min="1">
      </div>
      <!-- Select Table Dropdown (Disabled) -->
      <div class="w-1/3">
        <select id="tableSelector" name="table_id"
        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 p-1 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white appearance-none"
        disabled>
        <option value="">Table</option>
        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($table->id); ?>">
        <?php echo e($table->name); ?> (<?php echo e($table->group->name ?? 'No Group'); ?>)
      </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <!-- Customer Selector -->
      <div class="w-1/3 relative">
        <select id="customerSelector" name="customer_id"
        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 p-1 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
        <!-- <option value=""></option> -->
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $activeCoupon = $customer->activeCoupon();
        $hasCoupon = $activeCoupon ? true : false;
        $couponDiscount = $activeCoupon ? $activeCoupon->discount : 0;
      ?>
        <option value="<?php echo e($customer->id); ?>" data-has-coupon="<?php echo e($hasCoupon ? 'true' : 'false'); ?>"
          data-coupon-discount="<?php echo e($couponDiscount); ?>">
          <?php echo e($customer->name); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="absolute right-2 top-1/2 transform -translate-y-1/2">
        <span id="customerStatusIndicator" class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
        </div>
      </div>
      </div>
    </div>



    <!-- Order Items Table (flex-grow so it can scroll if needed) -->
    <div class="flex-grow border p-2 rounded-lg bg-white dark:bg-gray-800 mb-2 shadow-md 
       overflow-y-auto">
      <table class="min-w-full text-xs md:text-sm">
      <thead class="bg-blue-500  text-white sticky top-0 z-10 ">
        <tr class="text-left font-semibold">
        <th class="px-3 py-2">Name</th>
        <th class="px-3 py-2 text-center">Size</th>
        <th class="px-3 py-2 text-center">Topping</th>
        <th class="px-3 py-2 text-center">Price</th>
        <th class="px-3 py-2 text-center">Qty</th>
        <th class="px-3 py-2 text-center">Dis</th>
        <th class="px-3 py-2 text-center">Total</th>
        <th class="px-3 py-2 text-center">
          <i class="fas fa-trash text-red-500"></i>
        </th>
        </tr>
      </thead>
      <tbody id="orderList">
        <!-- Order rows dynamically inserted -->
      </tbody>
      </table>
    </div>

    <!-- Order Summary -->
    <div class="p-5 bg-blue-50 dark:bg-gray-900  rounded-md shadow-md text-xs mb-2">
      <div class="grid grid-cols-2 gap-4 mb-2">
      <div class="flex items-center justify-between">
        <span class="text-gray-700 font-bold  dark:text-gray-200">Total Items:</span>
        <span id="totalItems" class="font-bold text-gray-800 dark:text-gray-100">0</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-gray-700 font-bold dark:text-gray-200"><b>|</b> Sub Total:</span>
        <span class="font-bold text-gray-800 dark:text-gray-100">
        $<span id="subtotal">0.00</span>
        </span>
      </div>
      </div>

      <!-- Discount Row -->
      <div class="flex items-center justify-between mb-2 cursor-pointer 
       text-blue-700 dark:text-blue-300 font-medium hover:underline" onclick="showDiscountPopup()">
      <span class="inline-flex items-center">
        Discount
        <!-- Circular badge with dollar icon -->
        <span class="ml-1 w-4 h-4 flex items-center justify-center rounded-full 
         bg-blue-500 text-white text-xs">
        <i class="fas fa-dollar-sign text-[10px]"></i>
        </span>
        :
      </span>
      <span id="discount">0 (0)</span>
      </div>


      <!-- Divider -->
      <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>

      <!-- Total Payable Row -->
      <div class="flex items-center justify-between text-sm font-bold
       text-green-600 dark:text-green-300">
      <span>Total Payable:</span>
      <span>
        $<span id="totalAmount">0.00</span>
      </span>
      </div>
    </div>

    <!-- Order Actions (Buttons) -->
    <div class="flex justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-md shadow space-x-2">
      <!-- <div class="flex-grow">
      <button
      class="hold-button w-full px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm"
      onclick="holdOrder()">
      Hold
      </button>
      </div> -->
      <div class="flex-grow">
    <button id="clearOrderButton"
      class="w-full px-4 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm h-10"
      onclick="clearOrderTable()">
      Clear Order
    </button>
  </div>
  <div class="flex-grow">
    <button id="holdOrderButton" class="w-full px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm h-10" onclick="holdOrder()">
      Hold
    </button>
  </div>
      <div class="flex-grow">
      <button id="toggleQRButton"
        class="w-full px-4 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm h-10"
        onclick="toggleQR()">
        Show QR
      </button>
      </div>
      <div class="flex-grow">
      <!-- <button class="pay-button w-full px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm h-10"
        onclick="createOrderAndShowPayment()">
        Pay
      </button> -->
      <!-- <button id="payButton" class="pay-button w-full px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm h-10" onclick="handlePayment()">
  Pay
</button> -->
<button id="payButton" class="pay-button w-full px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm h-10" onclick="processPayment()">
  <span id="payButtonText">Pay</span>
</button>


      </div>
    </div>
    </div>

    <!-- Right Container (Tables / Products) -->
    <div
    class="w-full md:w-2/3 lg:w-2/3 bg-gray-200 dark:bg-gray-800 pl-4 pr-4 pt-4 shadow-md rounded-[4px] relative h-screen flex flex-col">
    <!-- Header (Top Bar) -->
    <div class="mb-4 bg-white dark:bg-gray-700  p-4 rounded-[6px] flex">
      <div class="flex-1 flex items-center">
      <div class="text-xl font-bold text-green-500">
        SOUSDEY COFFEE
      </div>
      </div>

      <!-- Right Container: Action Buttons + Profile -->
      <div class="flex items-center space-x-4">
      <div class="flex space-x-2">
        <button id="backToTableButton" class="flex items-center justify-center w-10 h-10 bg-gray-500  text-white 
         rounded-[3px] hover:bg-gray-600 focus:outline-none 
         transition-colors duration-200" title="Table " onclick="backToTableSelection()">
        <!-- <img src="<?php echo e(asset('icons/icontable.png')); ?>" class="w-6 h-6"> -->
        <i class="fa fa-table table-icon"></i>

        </button>

        <button class="flex items-center justify-center w-10 h-10 bg-gray-500 text-white 
         rounded-[3px] hover:bg-gray-600 focus:outline-none transition-colors duration-200" title="Category"
        onclick="toggleCategoryGrid()">
        <!-- <img src="<?php echo e(asset('icons/category.png')); ?>" class="w-7 h-7"> -->
        <i class="fa fa-list category-icon"></i>
        </button>

        <button class="flex items-center justify-center w-10 h-10 bg-gray-500  text-white 
      rounded-[3px] hover:bg-gray-600 focus:outline-none transition-colors duration-200" title="Shift Details"
        onclick="showShiftDetailsModal()">
        <i class="fa-solid fa-calendar-days shift-icon"></i>
        </button>

        <button class="flex items-center justify-center w-10 h-10 bg-gray-500  text-white 
         rounded-[3px] focus:outline-none transition-colors duration-200" title="Close Shift"
        onclick="showCloseShiftPopup()">
        <i class="fa-solid fa-xmark close-icon"></i> 
        </button>

        <button class="flex items-center justify-center w-10 h-10 bg-gray-500 text-white 
         rounded-[3px] hover:bg-gray-600 focus:outline-none transition-colors duration-200" title="Dark Mode"
        onclick="toggleDarkMode()">
        <i class="fas fa-moon"></i>
        </button>
        <button onclick="window.open('<?php echo e(route('customerscreen')); ?>', '_blank');" ,
        class="px-4 py-2 bg-gray-500  text-white rounded hover:bg-gray-600 focus:outline-none">
        <i class="fa-solid fa-desktop screen-icon"></i>

        </button>

      </div>


      <div class="flex items-center space-x-2">
        <div class="relative w-10 h-10">
        <img class="w-10 h-10 rounded-full" src="<?php echo e(Auth::user() && Auth::user()->profile
    ? asset('storage/' . Auth::user()->profile)
    : asset('assets/images/default-avatar.png')); ?>" alt="User Profile Photo" />
        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full
         ring-1 ring-white dark:ring-gray-800 bg-green-500">
        </span>
        </div>
      </div>
      </div>
    </div>

    <!-- Table Zone & Product Grid -->
    <div class="mb-4 bg-white dark:bg-gray-800 p-4 h-screen">
      <div class="flex-grow overflow-y-auto space-y-8">
      <!-- Table Zone -->
      <div id="tableZone">
        <div class="flex gap-2 mb-4">
        <button
          class="px-3 py-1 border border-blue-300 rounded font-bold text-gray-600 hover:bg-blue-100 focus:outline-none transition"
          onclick="filterTables('all')">
          All
        </button>
        <?php $__currentLoopData = $tableGroups ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <button
        class="px-3 py-1 border border-blue-300 rounded text-gray-600 hover:bg-gray-100 focus:outline-none transition"
        onclick="filterTables('<?php echo e($group->id); ?>')">
        <?php echo e($group->name); ?>

      </button>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div id="tableGrid"
        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-6 gap-4">
        <?php $__currentLoopData = $tableGroups ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $group->tables ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
      // Assume status "active" means free and "occupid" means held.
      $isHeld = $table->status === 'occupid';
      ?>
        <div class="table-card" id="table_<?php echo e($table->id); ?>" data-status="<?php echo e($table->status); ?>"
          data-group="<?php echo e($group->id); ?>" onclick="selectTable(<?php echo e($table->id); ?>)"
          style="<?php echo e($isHeld ? 'background-color: red;' : ''); ?>">
          <img src="<?php echo e(asset('assets/images/tables/table.png')); ?>" alt="Table Image">
          <p>
          <span class="table-name"><?php echo e($table->name); ?></span><br>
          <span id="tablePrice_<?php echo e($table->id); ?>" class="table-total">
          <?php if($isHeld && isset($table->held_order_total)): ?>
        Total: $<?php echo e(number_format($table->held_order_total, 2)); ?>

      <?php endif; ?>
          </span>
          </p>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <!-- Product Grid -->
      <div id="productGrid" class="hidden flex flex-col">
  <div id="productsGrid"
       class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-6 gap-4"
       style="max-height: calc(100vh - 250px);">
    <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php $__currentLoopData = $category->products ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="product-card flex flex-col items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md p-3" data-category-id="<?php echo e($category->id); ?>" onclick="showProductPopup(<?php echo e(json_encode($product)); ?>)">
          <!-- Image -->
          <img src="<?php echo e($product->image ? asset('storage/' . $product->image) : asset('assets/images/shoping.png')); ?>"
               alt="<?php echo e($product->name_en); ?>"
               class="w-full h-24 object-cover rounded mb-2" />
  
          <!-- Product Name -->
          <p class="text-center text-gray-800 dark:text-gray-200 font-semibold text-base truncate w-full">
            <?php echo e($product->name_en); ?><?php echo e($product->name_kh ? ' | ' . $product->name_kh : ''); ?>

          </p>
  
          <!-- Price -->
          <p class="text-center text-green-600 font-bold text-lg mt-1">
            $<?php echo e(number_format($product->base_price, 2)); ?>

          </p>
  
          <!-- Badge Section -->
          <div class="mt-2 flex flex-wrap justify-center gap-1">
            <?php if($product->is_stock === 'have_stock'): ?>
              <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">
                Qty: <?php echo e($product->qty ?? '0'); ?>

              </span>
            <?php endif; ?>
            <?php if($product->has_size === 'has'): ?>
              <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded">
                Sizes
              </span>
            <?php endif; ?>
            <?php if($product->has_topping === 'has'): ?>
              <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-0.5 rounded">
                Toppings
              </span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>


      <!-- Category Grid -->
            <!-- <div id="categoryGrid" class="hidden flex flex-col">
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 overflow-y-auto"
              style="max-height: calc(150vh - 300px);">
              <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="category-card dark:bg-gray-700 shadow transition-shadow"
                  onclick="selectCategory('<?php echo e($category->id); ?>')">
                  <img src="<?php echo e($category->image ? asset('storage/' . $category->image) : asset('assets/images/noimages.png')); ?>"
                   alt="<?php echo e($category->name); ?>" class="category-img">

                  <div class="category-card-title">
                      <?php echo e($category->name); ?>

                  </div>
                  <div class="category-card-subtitle dark:text-gray-300">
                      <?php echo e(count($category->products)); ?> Products
                  </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  </div> -->
  <div id="categoryGrid" class="hidden flex flex-col">
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 overflow-y-auto" style="max-height: calc(150vh - 300px);">
    <!-- Show All Card -->
    <div class="category-card dark:bg-gray-700 shadow transition-shadow cursor-pointer" onclick="selectCategory('all')">
      <img src="<?php echo e(asset('assets/images/select-all.png')); ?>" alt="Show All" class="category-img">
      <div class="category-card-title">Show All</div>
      <div class="category-card-subtitle dark:text-gray-300">All Products</div>
    </div>
    
    <!-- Loop through categories -->
    <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="category-card dark:bg-gray-700 shadow transition-shadow cursor-pointer" onclick="selectCategory('<?php echo e($category->id); ?>')">
        <img src="<?php echo e($category->image ? asset('storage/' . $category->image) : asset('assets/images/shoping.png')); ?>"
             alt="<?php echo e($category->name); ?>" class="category-img">
        <div class="category-card-title"><?php echo e($category->name); ?></div>
        <div class="category-card-subtitle <?php echo e(count($category->products) == 0 ? 'text-red-500 dark:text-red-400' : 'dark:text-gray-300'); ?>">
          <?php echo e(count($category->products)); ?> Products
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>

</div>
</div>
</div>
</div>
</div>


  <!-- Product Popup (Choosing size/topping/qty) -->
  <!-- Product Popup Overlay -->
  <div id="productPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 
       flex items-center justify-center p-4">
  <div class="bg-white dark:bg-gray-800 rounded-lg p-4 w-full max-w-md shadow-md mb-2 relative">

    <!-- Title & Close Button -->
    <div class="flex justify-between items-center mb-3">
      <h2 id="popupTitle" class="text-lg font-bold text-gray-800 dark:text-white">Select Options</h2>
      <button onclick="closeProductPopup()" class="text-gray-700 dark:text-white hover:text-red-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Separator -->
    <span class="block w-full border-b border-gray-300 dark:border-gray-600 mb-3"></span>

    <!-- Sizes (Radio Buttons) -->
    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Select Size:</label>
    <div id="popupSizeContainer" class="flex flex-wrap gap-3 mb-4">
      <!-- Dynamically populated with radio inputs -->
    </div>

    <!-- Toppings (Checkboxes) -->
    <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-200">Select Topping (single or multiple):</label>
    <div id="popupToppingContainer"
         class="mb-3 max-h-40 overflow-y-auto border p-2 rounded bg-gray-50 dark:bg-gray-700">
      <!-- Dynamically populated with checkboxes -->
    </div>

    <!-- Quantity -->
    <label for="popupQty" class="block mb-1 font-semibold text-gray-700 dark:text-gray-200">Quantity:</label>
    <input type="number" id="popupQty" value="1" min="1"
           class="w-full border rounded p-2 mb-3 bg-white dark:bg-gray-700 dark:text-white" />

    <!-- Calculated Total -->
    <!-- <div class="text-right text-sm font-semibold mb-3 text-gray-700 dark:text-gray-200">
      Total: <span id="popupTotalPrice">$0.00</span>
    </div> -->

    <!-- Footer: Show Size Price and Topping Total -->
    <div class="bg-gray-100 p-3 rounded-lg">
  <div class=" border-gray-300 dark:border-gray-600 pt-1 mt-1">
    <div class="text-gray-700 dark:text-gray-200 text-sm">
      <div class="flex justify-between mb-1">
        <span class="font-bold">Size Price:</span>
        <span id="popupSizePrice" class="text-green-600 font-bold">$0.00</span>
      </div>
      <div class="flex justify-between mb-1">
        <span class="font-bold">Topping Total:</span>
        <span id="popupToppingTotal" class="text-green-600 font-bold">$0.00</span>
      </div>
      <div class="flex justify-between">
        <span class="font-bold">Total:</span>
        <span id="popupTotalPrice" class="text-green-600 font-bold">$0.00</span>
      </div>
    </div>
  </div>
</div>


    <!-- Action Button (Auto Responsive) -->
    <div class="flex justify-center mt-3">
      <button type="button"
              class="w-full px-3 py-2 bg-green-500 hover:bg-green-600 font-bold text-white rounded-md text-sm h-10"
              onclick="addProductFromPopup()">
        Add to Order
      </button>
    </div>

  </div>
</div>




  <!-- Discount Popup HTML -->
  <div id="discountPopup" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div id="discountPopupContent" class="bg-white dark:bg-gray-800 rounded-lg p-5 shadow-lg w-full max-w-sm relative">

    <!-- Header with Title & Close Button -->
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-lg font-bold text-gray-800 dark:text-white">Discount (Amount or Percentage)</h2>
      <button onclick="closeDiscountPopup()" class="text-gray-700 dark:text-white hover:text-red-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
      </button>
    </div>

    <!-- Separator Line -->
    <div class="border-b border-gray-300 dark:border-gray-600 mb-3"></div>

    <!-- Hidden Input for Discount Value -->
    <input type="hidden" id="productDiscount" name="product_discount" value="0" />

    <!-- Discount Target Selection -->
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Apply Discount to:</label>
    <div class="flex items-center space-x-4 mb-3">
      <div class="flex items-center">
      <input type="radio" id="discount_order" name="discountTarget" value="order" checked
        class="form-radio h-4 w-4 text-blue-600" />
      <label for="discount_order" class="ml-1 text-sm text-gray-700 dark:text-gray-300">Order</label>
      </div>
      <div class="flex items-center">
      <input type="radio" id="discount_product" name="discountTarget" value="product"
        class="form-radio h-4 w-4 text-blue-600" />
      <label for="discount_product" class="ml-1 text-sm text-gray-700 dark:text-gray-300">Product</label>
      </div>
    </div>

    <!-- Product Selection (Only Visible When "Product" is Selected) -->
    <div id="discountProductSelector" class="hidden mb-3">
      <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Select Product(s):</label>

      <!-- "Select All" Checkbox -->
      <div class="flex items-center mb-2">
      <input type="checkbox" id="selectAllProducts" class="form-checkbox h-4 w-4 text-blue-600"
        onclick="toggleSelectAll(this)">
      <label for="selectAllProducts" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</label>
      </div>

      <!-- Scrollable Product List -->
      <div id="discountProductOptions"
      class="flex flex-col space-y-2 max-h-40 overflow-y-auto border p-2 rounded bg-gray-50 dark:bg-gray-700">
      <!-- Product options (checkboxes) will be populated dynamically -->
      </div>
    </div>

    <!-- Discount Type Selection -->
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Discount Type:</label>
    <select id="discountType"
      class="mb-2 w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
      <option value="percentage">Percentage</option>
      <option value="amount">Amount</option>
    </select>

    <!-- Discount Value Input -->
    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Discount Value:</label>
    <input type="number" id="discountValue" min="0"
      class="mb-4 w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white" />

    <!-- Action Buttons (Centered) -->
    <div class="flex justify-center space-x-3">
      <button class="px-4 w-full py-2 bg-green-600 text-white rounded hover:bg-green-700"
      onclick="applyDiscount()">Apply</button>

    </div>
    </div>
  </div>



  <!-- Payment Popup -->
  <div id="paymentPopup" class="fixed inset-0 z-50 flex items-start justify-center pt-10
       bg-black bg-opacity-50 transform scale-y-0 opacity-0 origin-top
       transition-transform transition-opacity duration-300 ease-out
       hidden">
    <div class="relative p-4 w-full max-w-md md:max-w-lg bg-white dark:bg-gray-800 rounded-lg">
    <!-- Close icon (X) in top-right corner -->
    <button
      type="button"
      class="absolute top-3 right-3 text-gray-600 hover:text-gray-800
             dark:text-gray-300 dark:hover:text-gray-100"
      onclick="closePaymentPopup()"
      aria-label="Close"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M4.293 4.293a1 1 0 011.414 0L10 8.586
             l4.293-4.293a1 1 0 011.414 1.414L11.414 10
             l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414
             l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10
             4.293 5.707a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <h2 class="mb-2 text-sm font-bold text-gray-700 dark:text-gray-200">
      Payment
      <span class="font-semibold text-xs text-gray-700 dark:text-gray-300">
            1$ =
          </span>
          <span class="text-xs text-gray-600 dark:text-gray-200">
            <span id="exchangeRateDisplay" class="font-bold">
              <?php echo e(number_format($exchangeRate, 0)); ?>៛
            </span>
          </span>
    </h2>
 <span class="block w-full border-b border-gray-300 dark:border-gray-600 mb-3"></span>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
      <!-- Left column: Payment details -->
      <div class="flex flex-col gap-2">
       


      <div>
        <label for="amountUSD" class="block mb-1 font-semibold text-xs text-gray-700 dark:text-gray-300">
        Amount ($):
        </label>
        <input type="number" id="amountUSD"
        class="border rounded p-2 w-full text-xs text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700"
        placeholder="$" />
      </div>

      <div>
        <label for="amountRiel" class="block mb-1 font-semibold text-xs text-gray-700 dark:text-gray-300">
        Amount (៛):
        </label>
        <input type="number" id="amountRiel"
        class="border rounded p-2 w-full text-xs text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700"
        placeholder="៛" />
      </div>

      <div>
        <label for="paymentMethod" class="block mb-1 font-semibold text-xs text-gray-700 dark:text-gray-300">
        Payment Method:
        </label>
        <select id="paymentMethod"
        class="border rounded p-2 w-full text-xs text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700">
        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($method->id); ?>">
        <?php echo e($method->name); ?>

      </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div>
        <label for="paymentNote" class="block mb-1 font-semibold text-xs text-gray-700 dark:text-gray-300">
        Note :
        </label>
        <textarea id="paymentNote" rows="2"
        class="border rounded p-2 w-full text-xs text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 min-h-[160px]"
        placeholder="Enter any notes about this payment..."></textarea>
      </div>
      </div>

      <div class="p-2 border rounded bg-blue-50 dark:bg-gray-700 flex flex-col gap-2">
      <button id="grandTotalButton"
        class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-2 px-3 rounded w-full">
        0.00
      </button>

      <div class="grid grid-cols-2 gap-2">
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="1">1</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="5">5</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="10">10</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="20">20</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="50">50</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="100">100</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="500">500</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="1000">1000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="2000">2000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="5000">5000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="10000">10000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="20000">20000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="50000">50000</button>
        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded"
        data-value="100000">100000</button>
      </div>

      <button type="button" id="clearButtonCustom"
        class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 rounded">
        Clear
      </button>
      </div>
    </div>

    <div class="flex flex-row gap-4 mt-4">
      <div class="flex-1 p-2 border rounded bg-gray-50 dark:bg-gray-700">
      <h6 class="font-bold text-xs mb-1 text-gray-700 dark:text-gray-200">
        Total Payable
      </h6>
      <div class="flex justify-between mb-1 text-xs text-gray-600 dark:text-gray-300">
        <span>USD:</span>
        <span id="totalPayableUSD">0.00 $</span>
      </div>
      <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
        <span>Riel:</span>
        <span id="totalPayableRiel">0.00 ៛</span>
      </div>
      </div>

      <div class="flex-1 p-2 border rounded bg-gray-50 dark:bg-gray-700">
      <h6 class="font-bold text-xs mb-1 text-gray-700 dark:text-gray-200">
        Balance
      </h6>
      <div class="flex justify-between mb-1 text-xs text-gray-600 dark:text-gray-300">
        <span>USD:</span>
        <span id="changeUSD">0.00 $</span>
      </div>
      <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
        <span>Riel:</span>
        <span id="changeRiel">0 ៛</span>
      </div>
      </div>
    </div>

    <div class="payment-actions mt-4 flex justify-end gap-2">
      <!-- <button type="button" class=" w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded"
      onclick="completePayment()">
      Confirm
      </button> -->
    
      <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded"
    onclick="handlePayment()">
    Confirm
</button> 


    </div>
    </div>
  </div>




  

  <!-- Close Shift Popup -->
  <div id="closeShiftPopup" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden flex items-center justify-center transition-opacity duration-300">
  <!-- MODAL CONTAINER -->
  <div class="relative p-4 w-[500px] md:w-[485px] bg-white dark:bg-gray-800 rounded-lg">
    
    <!-- HEADER: Title + Close Icon -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold text-gray-800 dark:text-white">Close Shift</h2>
      <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-300" onclick="closeCloseShiftPopup()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- HORIZONTAL LINE -->
    <hr class="border-b border-gray-300 dark:border-gray-600 mb-4">

    <!-- FORM -->
    <form id="closeShiftForm" onsubmit="submitCloseShift(event)">
      <?php echo csrf_field(); ?>

      <!-- SHIFT ID / USER ID (hidden) -->
      <input type="hidden" id="shiftUserId" value="<?php echo e(Auth::user()->id); ?>" />
      <input type="hidden" id="shiftId" value="<?php echo e($shift->id ?? ''); ?>" />

      <!-- Split Columns for Shift Details -->
      <div class="flex justify-between mb-4">
        <!-- Left Column: Closed By and Cash In Hand -->
        <div class="w-1/2 pr-2">
          <!-- CLOSED BY -->
          <div class="mb-4">
            <label class=" text-sm font-medium text-gray-700 dark:text-gray-300">Closed By:</label>
            <span class="text-gray-800 dark:text-gray-200"><?php echo e($shift->closer->name ?? Auth::user()->name); ?></span>
          </div>

          <!-- CASH IN HAND (READ-ONLY) -->
          <div class="mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Cash In Hand:</label>
            <span class=" text-gray-800 dark:text-gray-200"><?php echo e($shift->cash_in_hand ?? '0.00'); ?></span>
          </div>
        </div>
        
        <!-- Right Column: Open At and Close At -->
        <div class="w-1/2 pl-2">
          <!-- OPEN AT -->
          <div class="mb-4">
            <label class=" text-sm font-medium text-gray-700 dark:text-gray-300">Open At:</label>
            <span class=" text-gray-800 dark:text-gray-200"><?php echo e($shift->time_open ?? now()); ?></span>
          </div>

          <!-- CLOSE AT -->
          <div class="mb-4">
            <label class=" text-sm font-medium text-gray-700 dark:text-gray-300">Close At:</label>
            <span class=" text-gray-800 dark:text-gray-200"><?php echo e(now()); ?></span>
          </div>
        </div>
      </div>

      <!-- CASH SUBMITTED (Final Amount) -->
      <div class="mb-4">
        <label for="cashSubmitted" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cash Submitted:</label>
        <input type="number" value="<?php echo e($shift->total_cash ?? '0.00'); ?>" step="0.01" id="cashSubmitted" class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white" placeholder="Enter final cash amount" required />
      </div>

      <!-- BUTTONS -->
      <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 w-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Close Shift</button>
      </div>
    </form>
  </div>
</div>

  <!-- Shift Details Modal -->
<!-- SHIFT DETAILS MODAL -->
<div
  id="shiftDetailsModal"
   class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
>
  <div
    class="relative mt-2 bg-white dark:bg-gray-800 rounded-lg p-5 shadow-lg w-full max-w-sm"
  >
    <!-- HEADER: Title + Close Icon -->
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-lg font-bold text-gray-800 dark:text-white">
        Shift Details
      </h2>
      <button
        type="button"
        class="text-gray-500 hover:text-gray-700 dark:text-gray-300"
        onclick="closeShiftDetailsModal()"
      >
        <!-- Simple “X” icon (SVG) -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <!-- HORIZONTAL LINE -->
    <span class="block w-full border-b border-gray-300 dark:border-gray-600 mb-3"></span>

    <!-- SHIFT DETAILS CONTENT -->
    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
      <div>
        <span class="font-semibold">Open At:</span>
        <span id="shiftOpenTime"><?php echo e($shift->time_open ?? now()); ?></span>
      </div>
      <hr>
      <div>
        <span class="font-semibold">Open By:</span>
        <span id="shiftOpenBy"><?php echo e($shift->closer->name ?? Auth::user()->name); ?></span>
      </div>
      <hr>
      <div>
        <span class="font-semibold">Closed At:</span>
        <span id="shiftCloseTime"><?php echo e(now()); ?></span>
      </div>
      <hr>
      <div>
        <span class="font-semibold">Closed By:</span>
        <span id="shiftCloseBy"><?php echo e($shift->closer->name ?? Auth::user()->name); ?></span>
      </div>
      <hr>
      <div>
        <span class="font-semibold">Cash In Hand:</span>
        <span id="shiftCashInHand"><?php echo e($shift->cash_in_hand ?? '0.00'); ?></span>
      </div>
      <hr>
      <div>
        <span class="font-semibold">Cash Total:</span>
        <span id="shiftCashSubmitted"><?php echo e($shift->total_cash ?? '0.00'); ?></span>
      </div>
      <hr>
      <!-- Add any other relevant shift details here -->
    </div>
  
      <!-- end Payment Methods section -->

      <!-- Add any other relevant shift details here -->
 

    <!-- OPTIONAL: FOOTER or Additional Buttons -->
    
  </div>
</div>



  <script>

function handlePayment() {
  const payButton = document.getElementById("payButton");
  if (payButton && payButton.textContent.trim().toLowerCase() === "update order") {
    updateHeldOrderPayment();
  } else {
    completePayment();
  }
}
function processPayment() {
  const payButton = document.getElementById("payButton");
  if (payButton && payButton.textContent.trim().toLowerCase() === "update order") {
    updateHeldOrderAndShowPayment();
  } else {
    createOrderAndShowPayment();
  }
}




 function showShiftDetailsModal() {
    document.getElementById('shiftDetailsModal').classList.remove('hidden');
   
  }

  // Hide the modal
  function closeShiftDetailsModal() {
    document.getElementById('shiftDetailsModal').classList.add('hidden');
  }









    let selectedTableId = null; // Global variable for selected table ID

    async function createOrderAndShowPayment() {

    try {
      const customerId = document.getElementById("customerSelector")?.value || null;
      const tableId = selectedTableId || null;
      const total = parseFloat(document.getElementById("totalAmount")?.innerText) || 0;
      const createdBy = "<?php echo e(Auth::user()->id); ?>"; // For audit logging


      const productDiscountInput = document.getElementById("unitDiscount");
      const globalDiscountValue = productDiscountInput ? parseFloat(productDiscountInput.value) || 0 : 0;
      const totalPeople = document.getElementById("totalPeople")?.value || 0;
      let items = [];
      let totalItemCount = 0; // Track total items

      // Read discount type from popup (percentage or amount)
      const discountTypeElem = document.getElementById("discountType");
      const discountType = discountTypeElem ? discountTypeElem.value : "percentage";
      // Also read the manual discount value entered in the popup.
      const discountValueInput = parseFloat(document.getElementById("discountValue").value) || 0;

      const orderRows = document.querySelectorAll("#orderList tr");
      for (let row of orderRows) {
      const productId = parseInt(row.dataset.productId) || 0;
      const qty = parseInt(row.dataset.qty) || 0;
      const price = parseFloat(row.dataset.price) || 0;
      const sizeIds = row.dataset.sizeId ? parseInt(row.dataset.sizeId) : 0;
      let toppingIds = [];
      if (row.dataset.toppingIds) {
        try {
        toppingIds = JSON.parse(row.dataset.toppingIds);
        } catch (err) {
        console.warn("Could not parse toppingIds JSON:", err);
        toppingIds = [];
        }
      }
      if (!productId || qty <= 0 || price <= 0) {
        console.warn("Skipping invalid product row:", row);
        continue;
      }

      // Optional: Stock check code.
      try {
        const stockResponse = await fetch(`/products/${productId}/stock`);
        if (!stockResponse.ok) {
        console.error("Failed to fetch stock data:", stockResponse.statusText);
        throw new Error("Failed to fetch stock data");
        }
        const stockData = await stockResponse.json();
        const isStock = stockData.is_stock || "none_stock";
        const haveStock = parseInt(stockData.qty, 10) || 0;
        console.log(`Checking stock for product ${productId}:`, {
        isStock,
        haveStock,
        qty
        });
        if (isStock === "have_stock" && haveStock < qty) {
        Swal.fire({
          icon: 'warning',
          title: 'Stock Insufficient',
          text: `Not enough stock for ${row.dataset.productName || "product"}. Only ${haveStock} available.`,
          confirmButtonText: 'OK'
        });
        return;
        }
      } catch (error) {
        console.error("Error fetching stock data for product ID", productId, ":", error);
        alert("An error occurred while checking product stock.");
        return;
      }

      // Determine discount target from the UI.
      // Determine the discount target from the UI.
      const discountTargetElem = document.querySelector('input[name="discountTarget"]:checked');
      const discountTarget = discountTargetElem ? discountTargetElem.value : "order";
      let itemDiscount = 0; // This will be the computed discount in dollars for this row.
      let itemDiscountFraction = 0; // Only applicable if discount is percentage-based.

      if (discountTarget === "product") {
        // For product-level discount, each row should have its own discount stored in its dataset.
        // Assume that your discount popup has updated row.dataset.productDiscount with a fraction (e.g., 0.5 for 50%).
        itemDiscountFraction = parseFloat(row.dataset.productDiscount) || 0;
        if (discountType === "percentage") {
        // Calculate discount in dollars: unit price * discount fraction * quantity.
        itemDiscount = price * itemDiscountFraction * qty;
        } else if (discountType === "amount") {
        // For amount discount, assume the discount value applies per unit.
        const discountPerUnit = parseFloat(document.getElementById("discountValue").value) || 0;
        itemDiscount = discountPerUnit * qty;
        }
      } else if (discountTarget === "all") {
        // For an all-products discount, use the global discount value from the input.
        // The global discount input should already be in the correct format:
        // - If discount type is "percentage", the value is a fraction (e.g., 0.1 for 10%).
        // - If discount type is "amount", the value is the discount per unit.
        if (discountType === "percentage") {
        itemDiscountFraction =
          globalDiscountValue; // where globalDiscountValue is assumed to be a fraction (e.g., 0.1)
        itemDiscount = price * itemDiscountFraction * qty;
        } else if (discountType === "amount") {
        itemDiscount = globalDiscountValue * qty;
        }
      } else {
        // For "order" discount target, no per-row discount is applied.
        itemDiscount = 0;
      }

      // Push the order item. For discount_target "product" or "all", store the computed discount amount.
      items.push({
        product_id: productId,
        size_id: sizeIds,
        quantity: qty,
        unit_price: price,
        topping_id: toppingIds,
        product_discount: (discountTarget === "product" || discountTarget === "all") ?
        itemDiscount : 0,
      });

      totalItemCount += qty;
      }

      if (items.length === 0) {
      alert("No valid items to order.");
      return;
      }

      // Prepare the base order payload.
      const payload = {
      customer_id: customerId,
      table_id: tableId,
      total,
      total_people: totalPeople,
      total_item: totalItemCount,
      created_by: createdBy,
      items: items,
      };

      // Sum the total product discount from all items.
      const totalProductDiscount = items.reduce((sum, item) => sum + (item.product_discount || 0), 0);

      // Determine the global discount target from the UI.
      const discountTargetElemGlobal = document.querySelector('input[name="discountTarget"]:checked');
      const discountTargetGlobal = discountTargetElemGlobal ? discountTargetElemGlobal.value : "order";

      if (discountTargetGlobal === "order") {
      // For order-level discount, we read the discount from the discount element.
      const discountText = document.getElementById("discount").innerText;
      const discountAmount = parseFloat(discountText.replace('$', '')) || 0;
      payload.order_discount = discountAmount;
      // Also store the sum of product-level discounts (if any).
      payload.product_discount = totalProductDiscount;
      } else if (discountTargetGlobal === "all") {
      // For an all-products discount, send the global product discount.
      payload.product_discount = totalProductDiscount;
      delete payload.order_discount;
      } else if (discountTargetGlobal === "product") {
      // For product-level discount, send the total discount from items.
      payload.product_discount = totalProductDiscount;
      delete payload.order_discount;
      }

      console.log("Sending order payload:", payload);

      const response = await fetch("<?php echo e(route('pos.store')); ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(payload)
      });

      if (!response.ok) {
      const errorData = await response.json();
      alert("Error creating order: " + (errorData.message || 'Unknown error'));
      // Re-enable the button if there's an error.
      const payButton = document.querySelector('.pay-button'); // Ensure this selector matches your Pay button
      if (payButton) {
        payButton.disabled = false;
      }
      return;
      }

      const data = await response.json();
      console.log("Order created successfully, ID =", data.order_id);
      window.currentOrderId = data.order_id;
      showPaymentPopup();

    } catch (err) {
      console.error("Error in createOrderAndShowPayment:", err);
      alert("An error occurred while creating the order.");
    }
    }



    async function updateHeldOrderAndShowPayment() {
  try {
    const customerId = document.getElementById("customerSelector")?.value || null;
    // Use selectedTableId if available; otherwise, try to get it from the table selector.
    const tableId = selectedTableId || document.getElementById("tableSelector")?.value || null;
    const total = parseFloat(document.getElementById("totalAmount")?.innerText) || 0;
    const createdBy = "<?php echo e(Auth::user()->id); ?>"; // For audit logging

    const productDiscountInput = document.getElementById("unitDiscount");
    const globalDiscountValue = productDiscountInput ? parseFloat(productDiscountInput.value) || 0 : 0;
    const totalPeople = document.getElementById("totalPeople")?.value || 0;
    let items = [];
    let totalItemCount = 0; // Track total items

    const discountTypeElem = document.getElementById("discountType");
    const discountType = discountTypeElem ? discountTypeElem.value : "percentage";
    const discountValueInput = parseFloat(document.getElementById("discountValue").value) || 0;

    const orderRows = document.querySelectorAll("#orderList tr");
    for (let row of orderRows) {
      const productId = parseInt(row.dataset.productId) || 0;
      const qty = parseInt(row.dataset.qty) || 0;
      const price = parseFloat(row.dataset.price) || 0;
      const sizeIds = row.dataset.sizeId ? parseInt(row.dataset.sizeId) : 0;
      let toppingIds = [];
      if (row.dataset.toppingIds) {
        try {
          toppingIds = JSON.parse(row.dataset.toppingIds);
        } catch (err) {
          console.warn("Could not parse toppingIds JSON:", err);
          toppingIds = [];
        }
      }
      if (!productId || qty <= 0 || price <= 0) {
        console.warn("Skipping invalid product row:", row);
        continue;
      }

      // Stock check removed to prevent decrementing stock again

      const discountTargetElem = document.querySelector('input[name="discountTarget"]:checked');
      const discountTarget = discountTargetElem ? discountTargetElem.value : "order";
      let itemDiscount = 0;
      let itemDiscountFraction = 0;

      if (discountTarget === "product") {
        itemDiscountFraction = parseFloat(row.dataset.productDiscount) || 0;
        if (discountType === "percentage") {
          itemDiscount = price * itemDiscountFraction * qty;
        } else if (discountType === "amount") {
          const discountPerUnit = parseFloat(document.getElementById("discountValue").value) || 0;
          itemDiscount = discountPerUnit * qty;
        }
      } else if (discountTarget === "all") {
        if (discountType === "percentage") {
          itemDiscountFraction = globalDiscountValue;
          itemDiscount = price * itemDiscountFraction * qty;
        } else if (discountType === "amount") {
          itemDiscount = globalDiscountValue * qty;
        }
      } else {
        itemDiscount = 0;
      }

      items.push({
        product_id: productId,
        size_id: sizeIds,
        quantity: qty,
        unit_price: price,
        topping_id: toppingIds,
        product_discount: (discountTarget === "product" || discountTarget === "all") ? itemDiscount : 0,
      });

      totalItemCount += qty;
    }

    if (items.length === 0) {
      alert("No valid items to order.");
      return;
    }

    // Prepare payload. Only include table_id if available.
    let payload = {
      order_id: window.heldOrderId,
      customer_id: customerId,
      total,
      total_people: totalPeople,
      total_item: totalItemCount,
      created_by: createdBy,
      items: items,
    };
    if (tableId) {
      payload.table_id = tableId;
    }

    const totalProductDiscount = items.reduce((sum, item) => sum + (item.product_discount || 0), 0);
    const discountTargetElemGlobal = document.querySelector('input[name="discountTarget"]:checked');
    const discountTargetGlobal = discountTargetElemGlobal ? discountTargetElemGlobal.value : "order";

    if (discountTargetGlobal === "order") {
      const discountText = document.getElementById("discount").innerText;
      const discountAmount = parseFloat(discountText.replace('$', '')) || 0;
      payload.order_discount = discountAmount;
      payload.product_discount = totalProductDiscount;
    } else if (discountTargetGlobal === "all") {
      payload.product_discount = totalProductDiscount;
      delete payload.order_discount;
    } else if (discountTargetGlobal === "product") {
      payload.product_discount = totalProductDiscount;
      delete payload.order_discount;
    }

    console.log("Sending held order update payload:", payload);

    const response = await fetch("<?php echo e(route('pos.updateHold')); ?>", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const errorData = await response.json();
      alert("Error updating held order: " + (errorData.message || 'Unknown error'));
      const payButton = document.querySelector('.pay-button');
      if (payButton) {
        payButton.disabled = false;
      }
      return;
    }

    const data = await response.json();
    console.log("Held order updated successfully, ID =", data.order_id);
    window.currentOrderId = data.order_id;

    // Update table status to active (free)
    if (tableId) {
      const tableCard = document.getElementById("table_" + tableId);
      if (tableCard) {
        tableCard.style.backgroundColor = "";
        tableCard.dataset.status = "active";
      }
    }

    window.heldOrderId = null;
    showPaymentPopup();

  } catch (err) {
    console.error("Error in updateHeldOrderAndShowPayment:", err);
    alert("An error occurred while updating the held order.");
  }
}


    function showCloseShiftPopup() {
    document.getElementById('closeShiftPopup').classList.remove('hidden');
    }


    function closeCloseShiftPopup() {
    document.getElementById('closeShiftPopup').classList.add('hidden');
    }

    async function submitCloseShift(event) {
    event.preventDefault();

    const userId = document.getElementById('shiftUserId').value;
    const shiftId = document.getElementById('shiftId').value;
    const cashSubmitted = document.getElementById('cashSubmitted').value;

    // Build the payload
    const payload = {
      user_id: userId,
      shift_id: shiftId,
      cash_submitted: cashSubmitted
      // The server sets time_close automatically
    };

    try {
      const response = await fetch("<?php echo e(route('shifts.close')); ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
      },
      body: JSON.stringify(payload)
      });

      let data;
      try {
      // Attempt to parse JSON from the response
      data = await response.json();
      } catch (parseErr) {
      // If parsing fails, likely an HTML response
      console.warn("Response was not valid JSON. Possibly an HTML redirect or error page.");

      // In many cases, the shift might still be closed on the server.
      // As a fallback, just redirect to shifts.create
      window.location.href = "<?php echo e(route('shifts.create')); ?>";
      return;
      }

      // If we do get JSON, check data.success
      if (data.success) {
      alert("Shift closed successfully!");
      // Redirect to shifts.create
      window.location.href = "<?php echo e(route('shifts.create')); ?>";
      } else {
      alert("Error: " + data.message);
      }
    } catch (err) {
      console.error("Close shift error:", err);
      alert("An error occurred while closing the shift.");
    }
    }

    // Show the Payment popup (already in your code, but you can adapt as needed)
    function showPaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    popup.classList.remove("hidden");
    // e.g. call updateTotals() or focus on #amountUSD
    document.getElementById("amountUSD").focus();
    }

    // Hide Payment popup
    function closePaymentPopup() {
    document.getElementById("paymentPopup").classList.add("hidden");
    }


    // Show Payment Popup
    function showPaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    // Remove 'hidden' so it becomes visible in the DOM
    popup.classList.remove("hidden");

    // Force reflow so the browser applies the initial scale/opacity
    void popup.offsetWidth;

    // Add a 'show' class that triggers the scale + opacity transitions
    popup.classList.add("show");

    // Optionally call your updateTotals() function or anything else
    updateTotals();

    // Focus the USD input
    document.getElementById("amountUSD").focus();
    }

    function closePaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    // Remove the 'show' class, letting it animate back to scaleY(0), opacity: 0
    popup.classList.remove("show");

    // After the transition ends (~300ms), add 'hidden' again
    setTimeout(() => {
      popup.classList.add("hidden");
    }, 300);
    }


    // Get Exchange Rate from Display
    const exchangeRateDisplay = document.getElementById("exchangeRateDisplay");
    let exchangeRate = parseFloat(exchangeRateDisplay.textContent.replace(/[^0-9.]/g, '')) || 4100.00;

    // Get Grand Total from `#grandTotalButton`
    function getGrandTotalFromButton() {
    const grandTotalElement = document.getElementById("grandTotalButton");
    return parseFloat(grandTotalElement.textContent.replace(/[^0-9.]/g, "")) || 0;
    }

    // ----- Update Totals Function -----
    function updateTotals() {
    const totalPayableUSD = getGrandTotalFromButton();
    const totalPayableRiel = Math.round(totalPayableUSD * exchangeRate);

    // Update Total Payable display
    document.getElementById("totalPayableUSD").textContent = `${totalPayableUSD.toFixed(2)} $`;
    document.getElementById("totalPayableRiel").textContent = `${totalPayableRiel.toLocaleString()} ៛`;

    // Calculate Change/Balance
    const amountUSD = parseFloat(document.getElementById("amountUSD").value) || 0;
    const amountRiel = parseFloat(document.getElementById("amountRiel").value) || 0;
    const totalPaidUSD = amountUSD + (amountRiel / exchangeRate);
    const totalPaidRiel = (amountUSD * exchangeRate) + amountRiel;

    let balanceRiel = totalPaidRiel - totalPayableRiel;
    let balanceUSD = balanceRiel / exchangeRate;

    // Update Change or Balance fields
    document.getElementById("changeUSD").textContent = `${balanceUSD.toFixed(2)} $`;
    document.getElementById("changeRiel").textContent = `${Math.round(balanceRiel).toLocaleString()} ៛`;
    }

    // ----- Event Listeners for Input Focus and Changes -----
    let currentFocusedInput = null; // Track focused input field

    document.getElementById("amountUSD").addEventListener("focus", function () {
    currentFocusedInput = this;
    });
    document.getElementById("amountRiel").addEventListener("focus", function () {
    currentFocusedInput = this;
    });

    // Update totals when the input value changes.
    document.getElementById("amountUSD").addEventListener("input", updateTotals);
    document.getElementById("amountRiel").addEventListener("input", updateTotals);

    // ----- Handle Grand Total Button Click (Fills USD or Riel) -----
    document.getElementById("grandTotalButton").addEventListener("click", function () {
    if (!currentFocusedInput) {
      alert("Please focus on Amount ($) or Amount (៛) first.");
      return;
    }

    const grandTotal = getGrandTotalFromButton();
    currentFocusedInput.value = grandTotal.toFixed(2); // Fill the focused input with the total
    updateTotals();
    });

    // ----- Quick Payment Buttons -----
    document.querySelectorAll("[data-value]").forEach(function (button) {
    button.addEventListener("click", function () {
      const buttonValue = parseFloat(this.getAttribute("data-value")) || 0;

      if (currentFocusedInput) {
      let currentVal = parseFloat(currentFocusedInput.value) || 0;
      currentFocusedInput.value = currentVal + buttonValue; // Add the quick button value
      updateTotals();
      }
    });
    });

    // Clear button clears only the currently focused field.
    document.getElementById("clearButtonCustom").addEventListener("click", function () {
    if (currentFocusedInput) {
      currentFocusedInput.value = "";
      updateTotals();
    }
    });

    async function completePayment() {
    const totalPayableUSD = getGrandTotalFromButton();
    const totalPayableRiel = Math.round(totalPayableUSD * exchangeRate);

    const amountUSD = parseFloat(document.getElementById("amountUSD").value) || 0;
    const amountRiel = parseFloat(document.getElementById("amountRiel").value) || 0;
    const paymentMethodId = document.getElementById("paymentMethod").value;
    const note = document.getElementById("paymentNote").value || "";

    // Convert total paid to Riel, then to USD
    const totalPaidRiel = (amountUSD * exchangeRate) + amountRiel;
    const totalPaidUSD = totalPaidRiel / exchangeRate;

    if (totalPaidRiel < totalPayableRiel) {
      alert("Insufficient payment amount.");
      return;
    }

    const leftoverRiel = totalPaidRiel - totalPayableRiel;
    const leftoverUSD = leftoverRiel / exchangeRate;

    const payload = {
      order_id: window.currentOrderId || null,
      payment_method_id: paymentMethodId,
      amount: totalPaidUSD.toFixed(2),
      note: note,
      changeUSD: leftoverUSD.toFixed(2),
      changeRiel: Math.round(leftoverRiel),
      status: 'completed'
    };

    try {
      const response = await fetch("<?php echo e(route('payments.store')); ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
      },
      body: JSON.stringify(payload)
      });

      if (!response.ok) {
      const errorData = await response.json();
      alert("Error creating payment: " + (errorData.message || "Unknown error"));
      return;
      }

      const data = await response.json();


      // data should include `order_id` so we can show the receipt
      showReceiptPreview(data.order_id);

    } catch (err) {
      console.error("Payment error:", err);
      alert("An error occurred while creating the payment.");
      return;
    }

    // Clear fields and close popup
    document.getElementById("amountUSD").value = "";
    document.getElementById("amountRiel").value = "";
    document.getElementById("paymentNote").value = "";
    updateTotals();
    closePaymentPopup();
    }

    /**
     * If your back-end returns { order_id: 123 }, we can open a receipt page:
     */
    function showReceiptPreview(orderId) {
    // This will go to /pos/receipt/123 in the browser
    window.location.href = `/pos/receipt/${orderId}`;
    }

    async function updateHeldOrderPayment() {
  const totalPayableUSD = getGrandTotalFromButton();
  const totalPayableRiel = Math.round(totalPayableUSD * exchangeRate);

  const amountUSD = parseFloat(document.getElementById("amountUSD").value) || 0;
  const amountRiel = parseFloat(document.getElementById("amountRiel").value) || 0;
  const paymentMethodId = document.getElementById("paymentMethod").value;
  const note = document.getElementById("paymentNote").value || "";

  // Convert total paid to Riel then back to USD
  const totalPaidRiel = (amountUSD * exchangeRate) + amountRiel;
  const totalPaidUSD = totalPaidRiel / exchangeRate;

  if (totalPaidRiel < totalPayableRiel) {
    alert("Insufficient payment amount.");
    return;
  }

  const leftoverRiel = totalPaidRiel - totalPayableRiel;
  const leftoverUSD = leftoverRiel / exchangeRate;

  // Ensure we have a valid order id. Use window.heldOrderId if available, otherwise fallback to window.currentOrderId.
  const orderId = window.heldOrderId || window.currentOrderId;
  if (!orderId) {
    alert("No valid order ID is available.");
    return;
  }

  const payload = {
    order_id: orderId, // Now we have a valid order ID.
    payment_method_id: paymentMethodId,
    amount: totalPaidUSD.toFixed(2),
    note: note,
    changeUSD: leftoverUSD.toFixed(2),
    changeRiel: Math.round(leftoverRiel),
    status: 'completed'
  };

  console.log("Sending payload for updateHeldOrderPayment:", payload);

  try {
    const response = await fetch("<?php echo e(route('payments.updatePayment')); ?>", {
      method: "POST", // Ensure this matches your route.
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const errorData = await response.json();
      alert("Error updating payment: " + (errorData.message || "Unknown error"));
      return;
    }

    const data = await response.json();
    console.log("Held order updated, order ID =", data.order_id);
    window.currentOrderId = data.order_id;

    // Update table status to active (free)
    if (selectedTableId) {
      const tableCard = document.getElementById("table_" + selectedTableId);
      if (tableCard) {
        tableCard.style.backgroundColor = "";
        tableCard.dataset.status = "active";
      }
    }

    // Clear the held order id since the held order has been finalized.
    window.heldOrderId = null;

    // Redirect to receipt preview.
    showReceiptPreview(data.order_id);
  } catch (err) {
    console.error("Payment error:", err);
    alert("An error occurred while updating the held order payment: " + err.message);
    return;
  }

  // Clear payment fields and update totals, then close the popup.
  document.getElementById("amountUSD").value = "";
  document.getElementById("amountRiel").value = "";
  document.getElementById("paymentNote").value = "";
  updateTotals();
  closePaymentPopup();
}

function showReceiptPreview(orderId) {
  window.location.href = `/pos/receipt/${orderId}`;
}






    let currentPopupProduct = null; // The product being edited in the popup

function showProductPopup(product) {
  // Keep reference so calcPopupTotal() knows which product we’re editing
  currentPopupProduct = product;

  // Container references
  const sizeContainer = document.getElementById("popupSizeContainer");
  const toppingContainer = document.getElementById("popupToppingContainer");
  const popupTitleEl = document.getElementById("popupTitle");

  // Clear any old content
  sizeContainer.innerHTML = "";
  toppingContainer.innerHTML = "";

  // --- Build SIZE radio buttons ---
  if (product.product_sizes && product.product_sizes.length > 0) {
    product.product_sizes.forEach((ps, index) => {
      const price = parseFloat(ps.price) || 0;
      const sizeId = ps.size_id;
      const sizeName = ps.size?.name || "Size?";

      const label = document.createElement("label");
      label.className = "flex items-center mb-1";

      const radio = document.createElement("input");
      radio.type = "radio";
      radio.name = "popupSizeRadio"; // only one can be selected
      radio.value = sizeId;
      radio.dataset.price = price;

      // Optionally auto-check the first size:
      if (index === 0) radio.checked = true;

      label.appendChild(radio);

      const span = document.createElement("span");
      span.className = "ml-2";
      // Show price with two decimals
      span.textContent = `${sizeName}  $${price.toFixed(2)}`;
      label.appendChild(span);

      sizeContainer.appendChild(label);
    });
  } else {
    // If no sizes => single "No Size" radio
    const label = document.createElement("label");
    label.className = "flex items-center mb-1";

    const radio = document.createElement("input");
    radio.type = "radio";
    radio.name = "popupSizeRadio";
    radio.value = "";
    radio.dataset.price = 0;
    radio.checked = true; // auto-checked
    label.appendChild(radio);

    const span = document.createElement("span");
    span.className = "ml-2";
    span.textContent = "";
    label.appendChild(span);

    sizeContainer.appendChild(label);
  }

  // --- Build TOPPING checkboxes ---
  if (product.product_toppings && product.product_toppings.length > 0) {
    product.product_toppings.forEach((pt) => {
      const price = parseFloat(pt.price) || 0;
      const toppingId = pt.topping_id;
      const toppingName = pt.topping?.name || "Topping?";

      const label = document.createElement("label");
      label.className = "flex items-center justify-between w-full border rounded-md p-2 transition mb-1";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.name = "popupToppingCheckbox";
      checkbox.value = toppingId;
      checkbox.dataset.price = price;

      label.appendChild(checkbox);

      // Wrapper for text content
      const textWrapper = document.createElement("div");
      textWrapper.className = "flex justify-between w-full ml-2";

      // Left-aligned topping name
      const spanName = document.createElement("span");
      spanName.className = "text-gray-700 dark:text-gray-200 font-medium";
      spanName.textContent = toppingName;

      // Right-aligned price
      const spanPrice = document.createElement("span");
      spanPrice.className = "text-green-900 dark:text-green-300 font-semibold";
      spanPrice.textContent = ` + $${price.toFixed(2)}`;

      // Append spans to text wrapper
      textWrapper.appendChild(spanName);
      textWrapper.appendChild(spanPrice);

      label.appendChild(textWrapper);
      toppingContainer.appendChild(label);
    });
  } else {
    // No toppings => show a note
    const div = document.createElement("div");
    div.textContent = "No Toppings";
    toppingContainer.appendChild(div);
  }

  // Reset quantity
  document.getElementById("popupQty").value = 1;

  // Reset total display
  document.getElementById("popupTotalPrice").textContent = "$0.00";

  // Show the popup
  document.getElementById("productPopup").classList.remove("hidden");

  // Attach event listeners to recalc
  sizeContainer.addEventListener("change", calcPopupTotal);
  toppingContainer.addEventListener("change", calcPopupTotal);
  document.getElementById("popupQty").addEventListener("input", calcPopupTotal);

  // Initialize total
  calcPopupTotal();
}

function closeProductPopup() {
  document.getElementById("productPopup").classList.add("hidden");
  currentPopupProduct = null;
}

function calcPopupTotal() {
  if (!currentPopupProduct) return;

  // Base product price (if needed)
  const basePrice = parseFloat(currentPopupProduct.base_price) || 0;

  // Selected size price
  const sizeRadio = document.querySelector('input[name="popupSizeRadio"]:checked');
  const sizePrice = sizeRadio ? parseFloat(sizeRadio.dataset.price) : 0;

  // Sum of selected topping prices
  let toppingPriceTotal = 0;
  const toppingCheckboxes = document.querySelectorAll('input[name="popupToppingCheckbox"]:checked');
  toppingCheckboxes.forEach((checkbox) => {
    toppingPriceTotal += parseFloat(checkbox.dataset.price) || 0;
  });

  // Quantity
  const qty = parseInt(document.getElementById("popupQty").value) || 1;

  // Determine final unit price: if size is available, use its price; otherwise, use the base price.
  // Then add topping prices.
  let finalUnitPrice = (sizePrice > 0 ? sizePrice : basePrice) + toppingPriceTotal;

  // Total price calculation
  const total = finalUnitPrice * qty;

  // Update total display
  document.getElementById("popupTotalPrice").textContent = `$${total.toFixed(2)}`;

  // Update footer with size price and topping total
  document.getElementById('popupSizePrice').textContent = `$${sizePrice.toFixed(2)}`;
  document.getElementById('popupToppingTotal').textContent = `$${toppingPriceTotal.toFixed(2)}`;
}


    


    function addProductFromPopup() {
    if (!currentPopupProduct) return;

    const sizeRadio = document.querySelector('input[name="popupSizeRadio"]:checked');
    const chosenSizeId = sizeRadio ? sizeRadio.value : null;
    const sizePrice = sizeRadio ? parseFloat(sizeRadio.dataset.price) : 0;

    let toppingIds = [];
    let toppingNames = [];
    let toppingPriceTotal = 0;
    const toppingCheckboxes = document.querySelectorAll('input[name="popupToppingCheckbox"]:checked');
    toppingCheckboxes.forEach((checkbox) => {
      toppingIds.push(checkbox.value);
      const toppingLabelEl = checkbox.nextElementSibling;
      const toppingName = toppingLabelEl ? toppingLabelEl.textContent.trim() : 'Topping';
      toppingNames.push(toppingName);
      toppingPriceTotal += parseFloat(checkbox.dataset.price) || 0;
    });

    const basePrice = parseFloat(currentPopupProduct.base_price) || 0;
    const originalPrice = (sizePrice > 0 ? sizePrice : basePrice) + toppingPriceTotal;
    let qty = parseInt(document.getElementById("popupQty").value) || 1;
    const totalPrice = originalPrice * qty;
    const productNameEn = currentPopupProduct.name_en || "Unknown";
    const productNameKh = currentPopupProduct.name_kh || "";
    const displayProductName = productNameKh ? `${productNameEn} | ${productNameKh}` : productNameEn;
    const initialDiscountPct = 0;
    const unitDiscount = originalPrice * (initialDiscountPct / 100);
    const effectiveUnitPrice = originalPrice - unitDiscount;
    const rowTotal = effectiveUnitPrice * qty;
    const initialProductDiscountStr = initialDiscountPct.toString();

    const existingRow = [...document.querySelectorAll("#orderList tr")].find(tr =>
      tr.dataset.productId === String(currentPopupProduct.id) &&
      tr.dataset.sizeId === String(chosenSizeId || "") &&
      tr.dataset.toppingIds === JSON.stringify(toppingIds)
    );

    if (existingRow) {
      let existingQty = parseInt(existingRow.dataset.qty) || 1;
      existingQty += qty;
      existingRow.dataset.qty = existingQty;
      existingRow.dataset.price = originalPrice;
      existingRow.dataset.productDiscount = initialProductDiscountStr;
      const currentUnitDiscount = originalPrice * (parseFloat(existingRow.dataset.productDiscount) / 100);
      const newEffectiveUnitPrice = originalPrice - currentUnitDiscount;
      const updatedRowTotal = newEffectiveUnitPrice * existingQty;
      existingRow.querySelector("td:nth-child(5)").textContent = existingQty;
      existingRow.querySelector("td:nth-child(6)").textContent = `$${currentUnitDiscount.toFixed(2)}`;
      existingRow.querySelector("td:nth-child(7)").textContent = `$${updatedRowTotal.toFixed(2)}`;
    } else {
      const tr = document.createElement("tr");
      tr.dataset.productId = currentPopupProduct.id;
      tr.dataset.sizeId = chosenSizeId || "";
      tr.dataset.toppingIds = JSON.stringify(toppingIds);
      tr.dataset.qty = qty;
      tr.dataset.price = originalPrice;
      tr.dataset.productDiscount = initialProductDiscountStr;
      tr.innerHTML = `
      <td class="px-3 py-2">${displayProductName}</td>
      <td class="px-3 py-2">${sizeRadio ? sizeRadio.nextElementSibling.textContent : "No Size"}</td>
      <td class="px-3 py-2">${toppingNames.length ? toppingNames.join(", ") : ""}</td>
      <td class="px-3 py-2">$${originalPrice.toFixed(2)}</td>
      <td class="px-3 py-2">${qty}</td>
      <td class="px-3 py-2">$${unitDiscount.toFixed(2)}</td>
      <td class="px-3 py-2">$${rowTotal.toFixed(2)}</td>
      <td class="px-3 py-2">
      <button onclick="removeOrderItem(this)" class="text-red-500 hover:text-red-700">
      <i class="fas fa-trash"></i>
      </button>
      </td>
      `;
      document.getElementById("orderList").appendChild(tr);
    }

    updateOrderSummary();
    // Save order summary to localStorage.
    const order = {
      totalItems: document.getElementById("totalItems").innerText,
      subtotal: document.getElementById("subtotal").innerText,
      discount: document.getElementById("discount").innerText,
      totalPayable: document.getElementById("totalAmount").innerText
    };
    localStorage.setItem("orderSummary", JSON.stringify(order));
    // Save order rows details to localStorage.
    updateLocalStorageOrderData();

    closeProductPopup();
    }

    function removeOrderItem(btn) {
    // Remove the row from the order table
    const row = btn.closest("tr");
    row.remove();

    // Update the order summary (recalculate totals, etc.)
    updateOrderSummary();

    // Save the updated summary to localStorage
    const order = {
      totalItems: document.getElementById("totalItems").innerText,
      subtotal: document.getElementById("subtotal").innerText,
      discount: document.getElementById("discount").innerText,
      totalPayable: document.getElementById("totalAmount").innerText
    };
    localStorage.setItem("orderSummary", JSON.stringify(order));

    // Update the order rows data and save to localStorage
    updateLocalStorageOrderData();
    }
    // Updates the "orderRows" key by looping over every row in the order table.
    function updateLocalStorageOrderData() {
    const rows = document.querySelectorAll("#orderList tr");
    let orderRows = [];
    rows.forEach(row => {
      const cells = row.querySelectorAll("td");
      orderRows.push({
      productName: cells[0].innerText,
      size: cells[1].innerText,
      topping: cells[2].innerText,
      price: cells[3].innerText,
      qty: cells[4].innerText,
      discount: cells[5].innerText,
      total: cells[6].innerText
      });
    });
    localStorage.setItem("orderRows", JSON.stringify(orderRows));
    console.log("Order rows updated:", orderRows);
    }

    // Updates the order summary by looping through the order rows.
    function updateOrderSummary() {
    const orderRows = document.querySelectorAll("#orderList tr");
    let totalItems = 0;
    let subtotal = 0;
    orderRows.forEach((row) => {
      const qty = parseInt(row.dataset.qty) || 0;
      const price = parseFloat(row.dataset.price) || 0;
      // row.dataset.discount should be set (or computed) for each row.
      const rowDiscount = parseFloat(row.dataset.discount) || 0;
      const rowTotal = (price * qty) - rowDiscount;
      totalItems += qty;
      subtotal += rowTotal;

      // Update the table cells (assuming columns order as defined)
      const totalCell = row.querySelector("td:nth-child(7)");
      if (totalCell) totalCell.innerText = `$${rowTotal.toFixed(2)}`;

      const discountCell = row.querySelector("td:nth-child(6)");
      if (discountCell) {
      const prodDiscFrac = parseFloat(row.dataset.productDiscount) || 0;
      const rowDiscountDollars = rowDiscount > 0 ? rowDiscount : (price * qty) * prodDiscFrac;
      discountCell.innerText = rowDiscountDollars > 0 ? `$${rowDiscountDollars.toFixed(2)}` : `$0.00`;
      }
    });
    document.getElementById("totalItems").innerText = totalItems;
    document.getElementById("subtotal").innerText = subtotal.toFixed(2);
    // For simplicity, assume no additional discount is applied here:
    const totalPayable = subtotal;
    document.getElementById("totalAmount").innerText = totalPayable.toFixed(2);
    }

    // Remove an order row and update localStorage.
    function removeOrderItem(btn) {
    // Remove the row from the order table.
    const row = btn.closest("tr");
    row.remove();

    // Recalculate the summary.
    updateOrderSummary();

    // Save the updated summary to localStorage.
    const order = {
      totalItems: document.getElementById("totalItems").innerText,
      subtotal: document.getElementById("subtotal").innerText,
      discount: document.getElementById("discount").innerText,
      totalPayable: document.getElementById("totalAmount").innerText
    };
    localStorage.setItem("orderSummary", JSON.stringify(order));

    // Update the stored order rows.
    updateLocalStorageOrderData();
    }


    let couponDiscount = 0;

    // Update customer discount info when selection changes.
    document.getElementById('customerSelector').addEventListener('change', updateCustomerStatusIndicator);

    function updateCustomerStatusIndicator() {
    const customerSelector = document.getElementById('customerSelector');
    const indicator = document.getElementById('customerStatusIndicator');
    const selectedIndex = customerSelector.selectedIndex;
    const productDiscountInput = document.getElementById("productDiscount");

    if (selectedIndex <= 0) {
      indicator.classList.remove('bg-green-500', 'bg-blue-500');
      indicator.classList.add('bg-red-500');
      couponDiscount = 0;
      if (productDiscountInput) productDiscountInput.value = 0;
      updateOrderSummary();
      return;
    }

    const selectedOption = customerSelector.options[selectedIndex];
    const hasCoupon = selectedOption.dataset.hasCoupon === 'true';
    const discountValue = parseFloat(selectedOption.dataset.couponDiscount) || 0;

    if (hasCoupon) {
      indicator.classList.remove('bg-green-500', 'bg-red-500');
      indicator.classList.add('bg-green-500');
      couponDiscount = discountValue / 100;
      if (productDiscountInput) productDiscountInput.value = couponDiscount;
    } else {
      indicator.classList.remove('bg-blue-500', 'bg-red-500');
      indicator.classList.add('bg-green-500');
      couponDiscount = 0;
      if (productDiscountInput) productDiscountInput.value = 0;
    }

    updateOrderSummary();
    }

    // Updates Order Summary & Syncs Data Across Screens
    function updateOrderSummary() {
    const orderRows = document.querySelectorAll("#orderList tr");
    let totalItems = 0;
    let subtotal = 0;
    let totalDiscount = 0;

    orderRows.forEach((row) => {
      const qty = parseInt(row.dataset.qty) || 0;
      const price = parseFloat(row.dataset.price) || 0;
      const rowDiscount = parseFloat(row.dataset.discount) || 0;
      const rowTotal = (price * qty) - rowDiscount;

      totalItems += qty;
      subtotal += rowTotal;
      totalDiscount += rowDiscount;

      // Update Total Column (7th column)
      const totalCell = row.querySelector("td:nth-child(7)");
      if (totalCell) totalCell.innerText = `$${rowTotal.toFixed(2)}`;

      // Update Discount Column (6th column)
      const discountCell = row.querySelector("td:nth-child(6)");
      if (discountCell) {
      const prodDiscFrac = parseFloat(row.dataset.productDiscount) || 0;
      const rowDiscountDollars = rowDiscount > 0 ? rowDiscount : (price * qty) * prodDiscFrac;
      discountCell.innerText = rowDiscountDollars > 0 ? `$${rowDiscountDollars.toFixed(2)}` : `$0.00`;
      }
    });

    document.getElementById("totalItems").innerText = totalItems;
    document.getElementById("subtotal").innerText = subtotal.toFixed(2);

    // Global Discount Calculation
    const discountTargetElem = document.querySelector('input[name="discountTarget"]:checked');
    const discountTarget = discountTargetElem ? discountTargetElem.value : 'order';
    const productDiscountInput = document.getElementById("productDiscount");
    const discountTypeElem = document.getElementById("discountType");
    const discountType = discountTypeElem ? discountTypeElem.value : "percentage";

    let globalDiscountFraction = 0;
    let discountAmount = 0;

    if (discountTarget === "order") {
      if (couponDiscount > 0) {
      globalDiscountFraction = couponDiscount;
      discountAmount = subtotal * globalDiscountFraction;
      document.getElementById("discount").innerText = `$${discountAmount.toFixed(2)}`;
      } else {
      if (discountType === "percentage") {
        const manualPct = parseFloat(document.getElementById("discountValue").value) || 0;
        globalDiscountFraction = manualPct / 100;
        discountAmount = subtotal * globalDiscountFraction;
        document.getElementById("discount").innerText = `$${discountAmount.toFixed(2)} (${manualPct.toFixed(0)}%)`;
      } else if (discountType === "amount") {
        const manualAmount = parseFloat(document.getElementById("discountValue").value) || 0;
        discountAmount = manualAmount;
        globalDiscountFraction = subtotal > 0 ? discountAmount / subtotal : 0;
        document.getElementById("discount").innerText = `$${discountAmount.toFixed(2)}`;
      }
      }
      productDiscountInput.value = globalDiscountFraction;
    } else {
      discountAmount = 0;
      document.getElementById("discount").innerText = `$0.00`;
    }

    let totalPayable = subtotal - discountAmount;
    if (totalPayable < 0) totalPayable = 0;
    document.getElementById("totalAmount").innerText = totalPayable.toFixed(2);

    const grandTotalButton = document.getElementById("grandTotalButton");
    if (grandTotalButton) grandTotalButton.innerText = totalPayable.toFixed(2);

    if (typeof updateDiscountProductOptions === "function") {
      updateDiscountProductOptions();
    }

    // **Sync data across all screens**
    const orderSummary = {
      totalItems: totalItems,
      subtotal: subtotal.toFixed(2),
      discount: discountAmount.toFixed(2),
      totalPayable: totalPayable.toFixed(2),
    };

    localStorage.setItem("orderSummary", JSON.stringify(orderSummary));
    localStorage.setItem("discountUpdated", new Date().getTime()); // Notify other screens

    updateLocalStorageOrderData();
    }

    // Updates Order Data in localStorage
    function updateLocalStorageOrderData() {
    const rows = document.querySelectorAll("#orderList tr");
    let orderRows = [];

    rows.forEach((row) => {
      const cells = row.querySelectorAll("td");
      orderRows.push({
      productName: cells[0].innerText,
      size: cells[1].innerText,
      topping: cells[2].innerText,
      price: cells[3].innerText,
      qty: cells[4].innerText,
      discount: cells[5].innerText,
      total: cells[6].innerText,
      });
    });

    localStorage.setItem("orderRows", JSON.stringify(orderRows));
    }

    // **Listen for storage events across multiple screens**
    window.addEventListener("storage", function (e) {
    if (e.key === "orderSummary" || e.key === "discountUpdated") {
      try {
      const order = JSON.parse(localStorage.getItem("orderSummary"));
      updateCustomerOrderSummary(order);
      updateCustomerOrderTable();
      } catch (err) {
      console.error("Error parsing order summary:", err);
      }
    }
    });

    // **Initialize Data on Page Load**
    window.addEventListener("load", function () {
    const storedSummary = localStorage.getItem("orderSummary");
    if (storedSummary) {
      try {
      updateCustomerOrderSummary(JSON.parse(storedSummary));
      } catch (err) {
      console.error("Error loading stored order summary:", err);
      }
    }
    updateCustomerOrderTable();
    });


    // --- Discount Popup Functions ---

    function showDiscountPopup() {
    updateDiscountProductOptions();
    const popup = document.getElementById("discountPopup");
    popup.classList.remove("hidden");
    popup.classList.remove("scale-y-0");
    }

    function closeDiscountPopup() {
    const popup = document.getElementById("discountPopup");
    popup.classList.add("scale-y-0");
    setTimeout(() => {
      popup.classList.add("hidden");
    }, 300);
    }

    document.getElementsByName("discountTarget").forEach((radio) => {
    radio.addEventListener("change", function () {
      const discountProductSelector = document.getElementById("discountProductSelector");
      if (this.value === "product") {
      discountProductSelector.classList.remove("hidden");
      } else {
      discountProductSelector.classList.add("hidden");
      }
    });
    });

    // This function populates the discount product options as a list of checkboxes.
    function updateDiscountProductOptions() {
    const discountProductContainer = document.getElementById("discountProductOptions");
    if (!discountProductContainer) return;
    discountProductContainer.innerHTML = ""; // Clear existing options

    // Iterate over each order row and create a checkbox for it.
    const orderRows = document.querySelectorAll("#orderList tr");
    orderRows.forEach((row, index) => {
      const productName = row.cells[0].innerText || `Product ${index + 1}`;
      const wrapper = document.createElement("div");
      wrapper.className = "flex items-center";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.value = index;
      checkbox.className = "form-checkbox h-4 w-4 text-blue-600";
      checkbox.id = `discount_product_${index}`;

      const label = document.createElement("label");
      label.htmlFor = `discount_product_${index}`;
      label.className = "ml-2 text-sm text-gray-700 dark:text-gray-300";
      label.innerText = productName;

      wrapper.appendChild(checkbox);
      wrapper.appendChild(label);
      discountProductContainer.appendChild(wrapper);
    });
    }

    /* -------- applyDiscount() Function --------
     This function applies discount based on the selected discount target:
     - "order": applies discount to the entire order subtotal.
     - "all": applies discount to every order row.
     - "product": applies discount only to the selected product rows (multiple selections allowed).
     For "percentage", the manual input is interpreted as a percent (converted to a fraction);
     for "amount", it is interpreted as an absolute dollar value.
    */
    function applyDiscount() {
    const discountType = document.getElementById("discountType").value; // "percentage" or "amount"
    const discountValueInput = parseFloat(document.getElementById("discountValue").value) || 0;
    const target = document.querySelector('input[name="discountTarget"]:checked').value;
    const productDiscountInput = document.getElementById("productDiscount");

    if (target === "order") {
      const subtotal = parseFloat(document.getElementById("subtotal").innerText) || 0;
      let discountAmount = 0;
      if (couponDiscount > 0) {
      discountAmount = subtotal * couponDiscount;
      document.getElementById("discount").innerText =
        `$${discountAmount.toFixed(2)} (Coupon ${(couponDiscount * 100).toFixed(0)}% discount)`;
      productDiscountInput.value = couponDiscount;
      } else {
      if (discountType === "percentage") {
        discountAmount = subtotal * (discountValueInput / 100);
        document.getElementById("discount").innerText =
        `$${discountAmount.toFixed(2)} (${discountValueInput.toFixed(0)}% discount)`;
        productDiscountInput.value = discountValueInput / 100;
      } else { // "amount"
        discountAmount = discountValueInput;
        document.getElementById("discount").innerText = `$${discountAmount.toFixed(2)} (Amount discount)`;
        productDiscountInput.value = subtotal > 0 ? discountValueInput / subtotal : 0;
      }
      }
    } else if (target === "all") {
      const orderRows = document.querySelectorAll("#orderList tr");
      orderRows.forEach((row) => {
      const qty = parseInt(row.dataset.qty) || 0;
      const price = parseFloat(row.dataset.price) || 0;
      const rowTotalBefore = price * qty;
      let discountAmount = 0;
      if (discountType === "percentage") {
        discountAmount = rowTotalBefore * (discountValueInput / 100);
        row.dataset.productDiscount = (discountValueInput / 100).toString();
      } else {
        discountAmount = discountValueInput;
        row.dataset.productDiscount = discountValueInput.toString();
      }
      row.dataset.discount = discountAmount.toString();
      });
      productDiscountInput.value = discountType === "percentage" ? (discountValueInput / 100) : discountValueInput;
    } else if (target === "product") {
      // For product-level discount, update only the selected product rows (multiple selections allowed).
      const discountProductContainer = document.getElementById("discountProductOptions");
      const selectedCheckboxes = Array.from(discountProductContainer.querySelectorAll(
      "input[type='checkbox']:checked"));
      if (selectedCheckboxes.length === 0) {
      alert("Please select at least one product to discount.");
      return;
      }
      const orderRows = document.querySelectorAll("#orderList tr");
      selectedCheckboxes.forEach((checkbox) => {
      const index = parseInt(checkbox.value);
      if (isNaN(index)) return;
      const row = orderRows[index];
      if (row) {
        const qty = parseInt(row.dataset.qty) || 0;
        const price = parseFloat(row.dataset.price) || 0;
        const rowTotalBefore = price * qty;
        let discountAmount = 0;
        if (discountType === "percentage") {
        discountAmount = rowTotalBefore * (discountValueInput / 100);
        row.dataset.productDiscount = (discountValueInput / 100).toString();
        } else {
        discountAmount = discountValueInput;
        row.dataset.productDiscount = discountValueInput.toString();
        }
        row.dataset.discount = discountAmount.toString();
      }
      });
      productDiscountInput.value = discountType === "percentage" ? (discountValueInput / 100) : discountValueInput;

    }
    updateOrderSummary();
    closeDiscountPopup();
    }

    /* -------- Payment Popup Functions -------- */
    function showPaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    popup.classList.remove("hidden");
    void popup.offsetWidth; // Force reflow.
    popup.classList.add("show");
    updateTotals();
    document.getElementById("amountUSD").focus();
    }

    function closePaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    popup.classList.remove("show");
    setTimeout(() => {
      popup.classList.add("hidden");
    }, 300);
    }

    /* -------- Payment Totals Functions -------- */

    // Initialize display on page load.
    updateCustomerStatusIndicator();
    updateOrderSummary();



    // 1. Show/hide popup
    function showPaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    popup.classList.remove("hidden");
    void popup.offsetWidth; // force reflow
    popup.classList.add("show");
    updateTotals(); // Implement as needed.
    document.getElementById("amountUSD").focus();
    }

    function closePaymentPopup() {
    const popup = document.getElementById("paymentPopup");
    popup.classList.remove("show");
    setTimeout(() => {
      popup.classList.add("hidden");
    }, 300);
    }

    /* -------- Discount Popup Functions -------- */
    function showDiscountPopup() {
    updateDiscountProductOptions();
    const popup = document.getElementById("discountPopup");
    popup.classList.remove("hidden");
    popup.classList.remove("scale-y-0");
    }

    function closeDiscountPopup() {
    const popup = document.getElementById("discountPopup");
    popup.classList.add("scale-y-0");
    setTimeout(() => {
      popup.classList.add("hidden");
    }, 300);
    }

    function selectTable(tableId) {
    // Use the passed tableId directly.
    if (!tableId) {
      console.error("Table ID is null or undefined.");
      return;
    }

    const tableEl = document.getElementById("table_" + tableId);
    if (!tableEl) {
      console.warn("Table element not found for ID: table_" + tableId);
      return;
    }

    // Check the table's status
    if (tableEl.dataset.status === "occupid") {
      console.log("Table " + tableId + " is occupied. Loading held order...");
      loadHeldOrderForOrderSection(tableId);
    } else {
      // Set the global selectedTableId for new orders.
      selectedTableId = tableId;
      console.log("Table " + tableId + " selected for new order.");

      // Update the table dropdown to show the selected table.
      const tableSelector = document.getElementById("tableSelector");
      if (tableSelector) {
      tableSelector.value = tableId;
      }

      showProductGrid(); // Proceed to product selection for a new order.
    }
    }



    function showProductGrid() {
    document.getElementById('tableZone').classList.add('hidden');
    document.getElementById('productGrid').classList.remove('hidden');
    }

    function backToTableSelection() {
    document.getElementById('productGrid').classList.add('hidden');
    document.getElementById('tableZone').classList.remove('hidden');
    document.getElementById('categoryGrid').classList.add('hidden');
    }

    function backToTableSelection() {
    // Hide the product grid and category grid (if visible)
    document.getElementById('productGrid').classList.add('hidden');
    const categoryGrid = document.getElementById('categoryGrid');
    if (categoryGrid && !categoryGrid.classList.contains('hidden')) {
      categoryGrid.classList.add('hidden');
    }
    // Show the table zone.
    document.getElementById('tableZone').classList.remove('hidden');
    }

    function toggleCategoryGrid() {
    let catGrid = document.getElementById('categoryGrid');
    if (catGrid.classList.contains('hidden')) {
      catGrid.classList.remove('hidden');
      document.getElementById('tableZone').classList.add('hidden');
      document.getElementById('productGrid').classList.add('hidden');
    } else {
      catGrid.classList.add('hidden');
      document.getElementById('tableZone').classList.remove('hidden');
    }
    }

    function hideCategoryGrid() {
    document.getElementById('categoryGrid').classList.add('hidden');
    document.getElementById('tableZone').classList.remove('hidden');
    }

    function selectCategory(categoryId) {
  hideCategoryGrid();
  document.getElementById('tableZone').classList.add('hidden');
  document.getElementById('productGrid').classList.remove('hidden');
  let products = document.querySelectorAll('#productsGrid .product-card');

  if (categoryId === 'all') {
    // Show all products
    products.forEach(function (product) {
      product.classList.remove('hidden');
    });
  } else {
    // Filter products by category id
    products.forEach(function (product) {
      if (product.getAttribute('data-category-id') === categoryId) {
        product.classList.remove('hidden');
      } else {
        product.classList.add('hidden');
      }
    });
  }
}


    function filterTables(groupId) {
    let tables = document.querySelectorAll(".table-card");
    tables.forEach(function (table) {
      if (groupId === 'all' || table.dataset.group === groupId) {
      table.classList.remove("hidden");
      } else {
      table.classList.add("hidden");
      }
    });
    }




    // function toggleDarkMode() {
    // document.documentElement.classList.toggle('dark');
    // }
    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      if (document.documentElement.classList.contains('dark')) {
        localStorage.setItem('theme', 'dark');
      } else {
        localStorage.setItem('theme', 'light');
      }
    }

    function applyThemeFromLocalStorage() {
      const storedTheme = localStorage.getItem('theme');
      if (storedTheme === 'dark') {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }

    document.addEventListener('DOMContentLoaded', applyThemeFromLocalStorage);


    // Get the exchange rate from the displayed element.
    // (We remove any non-numeric characters so that "4100.00៛" becomes "4100.00")
    // Show Payment Popup
    function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('#discountProductOptions input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.checked = source.checked;
    });
    }

    window.addEventListener('load', function () {
    localStorage.setItem('screenOneRefresh', new Date().getTime());
    });

    let qrOpen = false;

    // Toggle function: if QR is closed, open it; if open, close it.
    function toggleQR() {
    if (!qrOpen) {
      // Retrieve total from your order summary element (adjust id if necessary)
      const total = document.getElementById("totalAmount").innerText;
      // Build a payload including the total price.
      const payload = {
      totalPrice: total,
      timestamp: new Date().getTime() // helps trigger storage event
      };
      // Save payload so that Screen Two's storage listener will show the QR modal.
      localStorage.setItem("qrPayload", JSON.stringify(payload));
      qrOpen = true;
      document.getElementById("toggleQRButton").innerText = "Close QR";
      console.log("QR payload set:", payload);
    } else {
      // If already open, send a signal to close the QR modal.
      localStorage.setItem("closeQR", new Date().getTime());
      qrOpen = false;
      document.getElementById("toggleQRButton").innerText = "Show QR";
      console.log("QR close signal sent.");
    }
    }

    // Function to display the shift details modal with dynamic data.

    function clearOrderTable() {
  // Remove all rows from the order table
  const orderList = document.getElementById("orderList");
  orderList.innerHTML = "";
  
  // Update the order summary to reflect that there are no items
  updateOrderSummary();
  
  // Optionally, clear the saved order data from localStorage
  localStorage.removeItem("orderRows");
  localStorage.removeItem("orderSummary");
}






// Global variables to track the selected table and held order ID.

window.heldOrderId = null; // Will be set if a held order is loaded

async function holdOrder() {
  if (!selectedTableId) {
    alert("Please select a table first.");
    return;
  }

  const orderRows = document.querySelectorAll("#orderList tr");
  if (orderRows.length === 0) {
    alert("No items in the order to hold.");
    return;
  }



  // Gather order data from the UI
  const customerId = document.getElementById("customerSelector")?.value || null;
  const total = parseFloat(document.getElementById("totalAmount")?.innerText) || 0;
  const totalPeople = document.getElementById("totalPeople")?.value || 0;
  const createdBy = "<?php echo e(Auth::user()->id); ?>"; // current user ID

  let items = [];
  let totalItemCount = 0;
  document.querySelectorAll("#orderList tr").forEach(row => {
    const productId = row.dataset.productId;
    const qty = parseInt(row.dataset.qty) || 0;
    const price = parseFloat(row.dataset.price) || 0;
    const sizeId = row.dataset.sizeId || null;
    const toppingIds = row.dataset.toppingIds ? JSON.parse(row.dataset.toppingIds) : [];
    if (productId && qty > 0 && price > 0) {
      items.push({
        product_id: productId,
        size_id: sizeId,
        quantity: qty,
        unit_price: price,
        topping_id: toppingIds
      });
      totalItemCount += qty;
    }
  });

  if (items.length === 0) {
    alert("No valid items to hold.");
    return;
  }

  const payload = {
    customer_id: customerId,
    table_id: selectedTableId,
    total: total,
    total_people: totalPeople,
    total_item: totalItemCount,
    created_by: createdBy,
    items: items
  };

  try {
    const response = await fetch("<?php echo e(route('pos.holdOrder')); ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const errorData = await response.json();
      alert("Error holding order: " + (errorData.message || "Unknown error"));
      return;
    }

    const data = await response.json();
    // Save held order ID so that payment later will update this order.
    window.heldOrderId = data.order_id;

    // Mark the selected table card as held:
    // Update its background to red and set its data-status attribute to "occupid"
    const tableCard = document.getElementById("table_" + selectedTableId);
    if (tableCard) {
      tableCard.style.backgroundColor = "red";
      tableCard.dataset.status = "occupid";
      // Optionally update the displayed held order total on the table card
      const totalElem = tableCard.querySelector(".table-total");
      if (totalElem) {
        totalElem.innerText = `Total: $${parseFloat(total).toFixed(2)}`;
      }
    }

    Swal.fire({
      icon: 'success',
      title: 'Order Held',
      text: 'Your order has been held successfully.',
      timer: 1500,
      showConfirmButton: false
    });

    // Clear the current order table and reload the page to refresh the UI.
    clearOrderTable();
    setTimeout(() => {
      location.reload();
    }, 1500);
  } catch (error) {
    console.error("Hold order error:", error);
    alert("An error occurred while holding the order.");
  }
}

/**
 * loadHeldOrder(tableId): Called when a held table (red) is clicked.
 * It fetches the held order data for that table and repopulates the order table.
 */
function selectTable(tableId) {
  if (!tableId) return;
  const tableEl = document.getElementById("table_" + tableId);
  if (!tableEl) {
    console.warn("Table element not found for ID: table_" + tableId);
    return;
  }
  
  // If the table is held, load the held order data.
  if (tableEl.dataset.status === "occupid") {
    console.log("Table " + tableId + " is held. Loading held order...");
    loadHeldOrder(tableId);
  } else {
    // For new orders, set the selected table ID and show product grid.
    selectedTableId = tableId;
    // Update table dropdown if needed:
    const tableSelector = document.getElementById("tableSelector");
    if (tableSelector) {
      tableSelector.value = tableId;
    }
    showProductGrid();
  }
}


// When a held order is loaded, update the global heldOrderId and change the Pay button text.
async function loadHeldOrder(tableId) {
  try {
    const response = await fetch("<?php echo e(route('pos.loadHeldOrder')); ?>?table_id=" + tableId);
    if (!response.ok) {
      alert("No held order found for this table.");
      return;
    }
    const data = await response.json();
    const order = data.order;
    const orderList = document.getElementById("orderList");
    orderList.innerHTML = ""; // Clear existing order rows

    // Update the disabled table selector with the held order's table_id.
    const tableSelector = document.getElementById("tableSelector");
    if (tableSelector) {
      tableSelector.value = order.table_id;
    }

    order.items.forEach(item => {
      const tr = document.createElement("tr");
      // Set dataset attributes for further use.
      tr.dataset.productId = item.product_id;
      // Note: We're assuming the API returns a "product_size_id" or the full productSize object.
      tr.dataset.sizeId = item.product_size_id || "";
      // Build topping IDs array safely.
      const toppingIds = Array.isArray(item.toppings)
        ? item.toppings.map(t => (t.topping && t.topping.id) ? t.topping.id : "")
        : [];
      tr.dataset.toppingIds = JSON.stringify(toppingIds);
      tr.dataset.qty = item.quantity;
      tr.dataset.price = item.unit_price;

      // Build the product name cell: include English and Khmer names.
      const productNameHtml = `
        ${item.product.name_en}${item.product.name_kh ? ' | ' + item.product.name_kh : ''}
      `;

      // Build the size cell: if productSize exists and has nested size details, display name and price.
     // Build the size cell: if productSize exists, show the size name from the related Size and its price from ProductSize.
     const sizeHtml = item.productSize && item.productSize.size 
  ? `${item.productSize.size.name} ($${parseFloat(item.productSize.price).toFixed(2)})`
  : "No Size";


      // Build the topping cell: list each topping's name and, if available, its price.
      const toppingHtml = (item.toppings && item.toppings.length > 0)
  ? item.toppings.map(t => {
      if (t.topping) {
        return t.topping.id + (t.topping.price ? ' ($' + parseFloat(t.topping.price).toFixed(2) + ')' : '');
      }
      return '';
    }).filter(val => val !== '').join(", ")
  : "";


      tr.innerHTML = `
        <td class="px-3 py-2">${productNameHtml}</td>
        <td class="px-3 py-2">${sizeHtml}</td>
        <td class="px-3 py-2">${toppingHtml}</td>
        <td class="px-3 py-2">$${parseFloat(item.unit_price).toFixed(2)}</td>
        <td class="px-3 py-2">${item.quantity}</td>
        <td class="px-3 py-2">$0.00</td>
        <td class="px-3 py-2">$${(parseFloat(item.unit_price) * item.quantity).toFixed(2)}</td>
        <td class="px-3 py-2">
          <button onclick="removeOrderItem(this)" class="text-red-500 hover:text-red-700">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
      orderList.appendChild(tr);
    });
    updateOrderSummary();

    // Set the global heldOrderId so that payment will update this order.
    window.heldOrderId = order.id;

    // Change the Pay button text to "Update Order".
    const payButton = document.getElementById("payButton");
    if (payButton) {
      payButton.textContent = "Update Order";
    }
  } catch (error) {
    console.error("Error loading held order:", error);
    alert("An error occurred while loading the held order.");
  }
}


// Function to clear the current order table (for new orders) and reset the Pay button.
function clearOrderTable() {
  const orderList = document.getElementById("orderList");
  orderList.innerHTML = "";
  updateOrderSummary();
  localStorage.removeItem("orderRows");
  localStorage.removeItem("orderSummary");
  const payButton = document.getElementById("payButton");
  if (payButton) {
    payButton.textContent = "Pay";
  }
  window.heldOrderId = null;
}

// Wrapper to handle Payment: If a held order is loaded, update it; otherwise create a new order.
// function handlePayment() {
//   if (window.heldOrderId) {
//     updateHeldOrderPayment();
//   } else {
//     createOrderAndShowPayment();
//   }
// }




// Method 1: Reload if the page was loaded from the cache.
window.addEventListener("pageshow", function(event) {
  if (event.persisted) {
    window.location.reload();
  }
});

// Method 2: Check for a flag that indicates the user is returning from the receipt page.
window.addEventListener("load", function() {
  if (localStorage.getItem("fromReceipt") === "true") {
    localStorage.removeItem("fromReceipt");
    window.location.reload();
  }
});

// When redirecting to the receipt preview, set the flag.
function showReceiptPreview(orderId) {
  localStorage.setItem("fromReceipt", "true");
  window.location.href = `/pos/receipt/${orderId}`;
}




  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.poss', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/pos/index.blade.php ENDPATH**/ ?>