<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
<nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70"><?php echo e(__('sales.home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary"><?php echo e(__('sales.todays_sales')); ?></a>
            </li>
        </ol>
    </nav>

    <h2 class="text-xl font-bold mb-4 dongrek-font"><?php echo e(__('sales.todays_sales')); ?> - <?php echo e(\Carbon\Carbon::today()->format('d-m-Y')); ?></h2>

    <!-- Search Bar -->
    <div class="mb-4 flex justify-between items-center dongrek-font">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" placeholder="<?php echo e(__('sales.search_placeholder')); ?>" value="<?php echo e(request('search')); ?>"
                   class="input w-64" />

        </form>
    </div>

    <!-- Sales Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 font-bold dongrek-font">
                <tr>
                    <th class="px-4 py-2"><?php echo e(__('sales.date')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.table')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.order_status')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.discount')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.total')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.payment')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.paid')); ?></th>
                    <th class="px-4 py-2"><?php echo e(__('sales.change')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo e($sale->sale_date); ?></td>
                        <td class="px-4 py-2"><?php echo e($sale->table_name ?? __('sales.pending')); ?></td>
                        <td class="px-4 py-2">
                            <span class="status-badge <?php echo e(match(strtolower($sale->order_status)) {
                                    'complete' => 'status-completed',
                                    'hold' => 'status-pending',
                                    default => 'status-warning'
                                }); ?>">
                                <?php echo e(ucfirst($sale->order_status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-2">$<?php echo e(number_format($sale->discount, 2)); ?></td>
                        <td class="px-4 py-2">$<?php echo e(number_format($sale->total, 2)); ?></td>
                        <td class="px-4 py-2">
                            <span class="status-badge <?php echo e(match(strtolower($sale->payment_status)) {
                                    'completed' => 'status-completed',
                                    'pending' => 'status-pending',
                                    default => 'status-unknown'
                                }); ?>">
                                <?php echo e(ucfirst($sale->payment_status ?? __('sales.pending'))); ?>

                            </span>
                        </td>
                        <td class="px-4 py-2">$<?php echo e(number_format($sale->paid, 2)); ?></td>
                        <td class="px-4 py-2">$<?php echo e(number_format($sale->change_usd, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center py-4 dongrek-font"><?php echo e(__('sales.no_sales_found')); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
        <?php if($sales->total() > 0): ?>
            <!-- Per-Page Dropdown -->
            <div>
                <select id="per_page" class="input p-2 border rounded"
                        onchange="window.location.href='<?php echo e($sales->url(1)); ?>&per_page=' + this.value">
                    <option value="2" <?php echo e(request('per_page') == 2 ? 'selected' : ''); ?>>2</option>
                    <option value="4" <?php echo e(request('per_page') == 4 ? 'selected' : ''); ?>>4</option>
                    <option value="5" <?php echo e(request('per_page') == 5 ? 'selected' : ''); ?>>5</option>
                    <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10</option>
                    <option value="20" <?php echo e(request('per_page') == 20 ? 'selected' : ''); ?>>20</option>
                </select>
            </div>

            <!-- Simple Pagination -->
            <div class="pagination">
                <?php if($sales->onFirstPage()): ?>
                    <span class="btn-disabled ">←</span>
                <?php else: ?>
                    <a href="<?php echo e($sales->previousPageUrl()); ?>&per_page=<?php echo e(request('per_page')); ?>" class="btn-pagination ">←</a>
                <?php endif; ?>

                <span class="btn-page "><?php echo e($sales->currentPage()); ?></span>

                <?php if($sales->hasMorePages()): ?>
                    <a href="<?php echo e($sales->nextPageUrl()); ?>&per_page=<?php echo e(request('per_page')); ?>" class="btn-pagination ">→</a>
                <?php else: ?>
                    <span class="btn-disabled ">→</span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600 dark:text-gray-300 dongrek-font"><?php echo e(__('sales.no_sales_available')); ?></p>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Input & Select Styling */
    .input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        outline: none;
        background-color: #ffffff;
        color: #333;
        transition: border-color 0.2s;
    }

    .input:focus {
        border-color: #3b82f6;
    }

    /* Dark Mode */
    .dark .input {
        background-color: #1f2937;
        color: #f9fafb;
        border-color: #4b5563;
    }

    .dark .input:focus {
        border-color: #3b82f6;
    }

    /* Buttons */
    .btn-primary {
        background-color: #005ff5;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #1e40af;
    }

    /* Table Styling */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        text-align: left;
        padding: 10px;

    }

    th {
    background: #3b82f2; /* Blue gradient */
    color: white;
    padding: 16px;
    font-weight: 600;
    text-align: left;
}

    .dark .table thead {
        background: linear-gradient(90deg, #14532d, #1e3a8a);
    }

    /* Footer Total Row */
    .tfoot-total {
        background-color: #f3f4f6;
        font-weight: bold;
    }

    .dark .tfoot-total {
        background-color: #374151;
    }

    /* Pagination */
    .pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-pagination {
        background-color: #f3f4f6;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-pagination:hover {
        background-color: #e5e7eb;
    }

    .btn-disabled {
        background-color: #d1d5db;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        color: gray;
    }

    .btn-page {
        font-weight: bold;
        padding: 0.6rem 1rem;
        background-color: #2563eb;
        color: white;
        border-radius: 6px;
    }
    .status-badge {
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.status-completed {
    background-color: #d1fae5; /* green-100 */
    color: #065f46;           /* green-800 */
}

.status-pending {
    background-color: #fee2e2; /* red-100 */
    color: #991b1b;            /* red-700 */
}

.status-warning {
    background-color: #fef3c7; /* yellow-100 */
    color: #92400e;            /* yellow-800 */
}

.status-unknown {
    background-color: #e5e7eb; /* gray-100 */
    color: #4b5563;            /* gray-600 */
}

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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/sales/today.blade.php ENDPATH**/ ?>