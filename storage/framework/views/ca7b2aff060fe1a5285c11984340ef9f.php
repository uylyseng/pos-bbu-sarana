<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
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
            text: "<?php echo e(session('success')); ?>",
            padding: '2em',
        });
    });
</script>
<?php endif; ?>

<div class="container ">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.product')); ?></a>
            </li>
        </ol>
    </nav>

    <!-- Header: Add New Product & Filters -->
    <div class="flex justify-between items-center mb-4 dongrek-font">
        <h2 class="text-xl font-semibold <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.products_list')); ?></h2>
        <div class="flex items-center gap-3">
            <!-- Search Bar for Live Filtering -->
            <div class="relative">
                <!-- Search input with right padding to accommodate the icon -->
                <input
                    type="text"
                    id="search-input"
                    name="search_name"
                    placeholder="<?php echo e(__('text.search_name_barcode')); ?>"
                    class="search-input pr-15"
                    autocomplete="off"
                >

                <!-- Icon container, positioned absolutely at the right end of the input -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <!-- Simple magnifying glass icon (e.g., from Heroicons) -->
                    <svg
                        class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5a7 7 0 016.32 4.394A7 7 0 1111 5zm10 16l-4.35-4.35"
                        ></path>
                    </svg>
                </span>
            </div>

            <!-- Filters (if needed) -->
            <form method="GET" action="<?php echo e(route('products.index')); ?>" id="filterForm" class="flex gap-3">
                <select name="is_stock" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                    <option value=""><?php echo e(__('text.all_stock')); ?></option>
                    <option value="have_stock" <?php echo e(request('is_stock') == 'have_stock' ? 'selected' : ''); ?>><?php echo e(__('text.has_stock')); ?></option>
                    <option value="none_stock" <?php echo e(request('is_stock') == 'none_stock' ? 'selected' : ''); ?>><?php echo e(__('text.no_stock')); ?></option>
                </select>

                <select name="has_size" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                    <option value=""><?php echo e(__('text.all_sizes')); ?></option>
                    <option value="has" <?php echo e(request('has_size') == 'has' ? 'selected' : ''); ?>><?php echo e(__('text.has_size')); ?></option>
                    <option value="none" <?php echo e(request('has_size') == 'none' ? 'selected' : ''); ?>><?php echo e(__('text.no_size')); ?></option>
                </select>

                <select name="has_topping" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                    <option value=""><?php echo e(__('text.all_toppings')); ?></option>
                    <option value="has" <?php echo e(request('has_topping') == 'has' ? 'selected' : ''); ?>><?php echo e(__('text.has_topping')); ?></option>
                    <option value="none" <?php echo e(request('has_topping') == 'none' ? 'selected' : ''); ?>><?php echo e(__('text.no_topping')); ?></option>
                </select>
            </form>

            <!-- Add New Product Button -->
            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-green btn-sm">
                <i class="fas fa-plus-circle mr-1"></i>
                <span class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Add New')); ?></span>
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr class="dongrek-font">
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Image')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.name_en_name_kh')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.category')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.stock_unit')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.sizes')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.toppings')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Status')); ?></th>
                    <th class="align-middle <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Actions')); ?></th>
                </tr>
            </thead>
            <tbody id="products-table-body">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="align-middle text-center">
                        <?php if($product->image): ?>
                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name_en); ?>" class="h-10 w-10 object-cover rounded">
                        <?php else: ?>
                        <img
                            src="<?php echo e(asset('assets/images/nophoto.png' .$product->image)); ?>"
                            alt="Products Image"
                            class="Products-img">
                        <?php endif; ?>
                    </td>
                    <td class="align-middle"><?php echo e($product->name_en); ?> | <?php echo e($product->name_kh); ?></td>
                    <td class="align-middle"><?php echo e($product->category ? $product->category->name : 'N/A'); ?></td>
                    <td class="align-middle">
                    <?php if($product->is_stock === 'have_stock'): ?>
                        <?php
                            $isLowStock = isset($product->low_stock) && ($product->qty <= $product->low_stock);
                        ?>

                        <?php if($isLowStock): ?>
                            <span class="stock-low">
                                <?php echo e($product->qty); ?>

                            </span>
                            <?php if($product->sale_unit_id && optional($product->saleUnit)->name): ?>
                                - <?php echo e(optional($product->saleUnit)->name); ?>

                            <?php endif; ?>
                            <small class="ml-1 font-bold text-red-600" style=" font-size: 13px;">(<?php echo e(__('text.low')); ?>)</small>
                        <?php else: ?>
                            <span class="stock-available">
                                <?php echo e($product->qty); ?>

                                <?php if($product->sale_unit_id && optional($product->saleUnit)->name): ?>
                                    - <?php echo e(optional($product->saleUnit)->name); ?>

                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="stock-unavailable dongrek-font <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.none_stock')); ?></span>
                    <?php endif; ?>
                </td>

                    <td class="align-middle text-center">
                        <i class="fa-solid <?php echo e($product->has_size === 'has' ? 'fa-check has-size' : 'fa-xmark no-size'); ?>"></i>
                    </td>
                    <td class="align-middle text-center">
                        <i class="fa-solid <?php echo e($product->has_topping === 'has' ? 'fa-check has-topping' : 'fa-xmark no-topping'); ?>"></i>
                    </td>
                    <td class="align-middle px-4 py-2">
                        <span
                            class="inline-block px-3 py-1 rounded-full border-2 font-bold
                                <?php echo e($product->active === 'active' ? 'border-green-600 text-green-600' : 'border-red-600 text-red-600'); ?>"
                        >
                            <?php echo e($product->active === 'active' ? __('text.Active') : __('text.Inactive')); ?>

                        </span>
                    </td>

                    <td class="align-middle text-center dongrek-font">
                        <a href="<?php echo e(route('products.edit', $product->id)); ?>"class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                        <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> <?php echo e(__('text.Edit')); ?>

                        </a>
                        <button type="button" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700" onclick="confirmDelete('<?php echo e(route('products.destroy', $product->id)); ?>')">
                        <i class="fa-solid fa-trash mr-1" style="color: red;"></i> <?php echo e(__('text.Delete')); ?>

                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (optional; live filtering may disable pagination) -->
    <div class="d-flex justify-center mt-4" id="pagination-container">
        <?php echo e($products->appends(request()->query())->links('layouts.pagination')); ?>

    </div>
</div>

<!-- JavaScript for Live Filtering -->
<script>
document.getElementById('search-input').addEventListener('input', function(e) {
  e.preventDefault(); // Prevent any default behavior (if the input is in a form)

  let query = e.target.value.trim();
  let tableBody = document.getElementById('products-table-body');

  // When query is cleared or less than 2 characters, fetch all products
  if(query === '') {
    window.location.reload();
    return;
  }

  // Otherwise, perform the search.

  const url = window.location.origin + `/products/search?query=${encodeURIComponent(query)}`;
  console.log("Fetching filtered products from:", url);


  fetch(url)
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      console.log("Products data:", data);
      tableBody.innerHTML = '';

      if (Array.isArray(data) && data.length > 0) {
        data.forEach(product => {
          let row = document.createElement('tr');
          row.innerHTML = `
            <td class="align-middle text-center">
              ${ product.image
                  ? `<img src="/storage/${product.image}" alt="${product.name_en}" class="h-10 w-10 object-cover rounded">`
                  : `<img src="<?php echo e(asset('assets/images/nophoto.png')); ?>" alt="No Image" class="category-img">` }
            </td>
            <td class="align-middle">${ product.name_en } | ${ product.name_kh }</td>
            <td class="align-middle">
              ${ product.category ? product.category.name : 'N/A' }
            </td>
             <td class="align-middle">
                    ${ product.is_stock === 'have_stock'
                            ? `<span class="stock-available">
                                <?php echo e($product->qty ?? '0'); ?>

                                <?php if($product->sale_unit_id && optional($product->saleUnit)->name): ?>
                                - <?php echo e(optional($product->saleUnit)->name); ?>

                                <?php endif; ?>
                            </span>
                            `
                            : '<span class="stock-unavailable"><?php echo e(__("text.none_stock")); ?></span>' }
                </td>
            <td class="align-middle text-center">
              ${ product.has_size === 'has' ? '<i class="fa-solid fa-check has-size"></i>' : '<i class="fa-solid fa-xmark no-size"></i>' }
            </td>
            <td class="align-middle text-center">
              ${ product.has_topping === 'has' ? '<i class="fa-solid fa-check has-topping"></i>' : '<i class="fa-solid fa-xmark no-topping"></i>' }
            </td>
            <td class="align-middle text-center">
              <span class="status-badge ${ product.active === 'active' ? 'status-active' : 'status-inactive' }">
                ${ product.active === 'active' ? '<?php echo e(__("text.Active")); ?>' : '<?php echo e(__("text.Inactive")); ?>' }
              </span>
            </td>
            <td class="align-middle text-center actions dongrek-font">
              <a href="/products/edit/${product.id}" class="edit-btn">
                <i class="fa-solid fa-pen-to-square text-lg"></i> <?php echo e(__('text.Edit')); ?>

              </a>
              <button type="button" class="delete-btn" onclick="confirmDelete('/products/destroy/${product.id}')">
                <i class="fa-solid fa-trash text-lg"></i> <?php echo e(__('text.Delete')); ?>

              </button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      } else {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center p-4 dongrek-font <?php echo e(app()->getLocale() == "en" ? "font-en" : "font-kh"); ?>"><?php echo e(__("text.no_matching_products")); ?></td></tr>';
      }
    })
    .catch(error => {
      console.error("Error fetching products:", error);
    });
});

    async function confirmDelete(deleteUrl) {
        const result = await Swal.fire({
            icon: 'warning',
            title: '<?php echo e(__("text.are_you_sure")); ?>',
            text: '<?php echo e(__("text.this_action_cannot_be_undone")); ?>',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?php echo e(__("text.yes_delete_it")); ?>',
            cancelButtonText: '<?php echo e(__("text.cancel")); ?>',
        });

        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '<?php echo e(csrf_token()); ?>';
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/products/index.blade.php ENDPATH**/ ?>