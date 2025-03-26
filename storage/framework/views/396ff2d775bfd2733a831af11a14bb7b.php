<?php if($paginator->count() > 0): ?>
    <div class="flex justify-between items-center mt-4">
        <!-- Per-Page Dropdown -->
        <div>
            <select id="per_page" class="px-3 py-2 border rounded-md bg-white text-gray-700 shadow-md"
                onchange="window.location.href='<?php echo e($paginator->url(1)); ?>&perPage=' + this.value">
                <option value="2"  <?php echo e(request('perPage') == 2  ? 'selected' : ''); ?>>2</option>
                <option value="5"  <?php echo e(request('perPage') == 5  ? 'selected' : ''); ?>>5</option>
                <option value="10" <?php echo e(request('perPage') == 10 ? 'selected' : ''); ?>>10</option>
                <option value="20" <?php echo e(request('perPage') == 20 ? 'selected' : ''); ?>>20</option>
            </select>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-2">
            <?php if($paginator->onFirstPage()): ?>
                <span class="btn-disabled">←</span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>&perPage=<?php echo e(request('perPage')); ?>" 
                   class="btn-pagination">←</a>
            <?php endif; ?>

            <span class="btn-page">
                <?php echo e($paginator->currentPage()); ?>

            </span>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>&perPage=<?php echo e(request('perPage')); ?>" 
                   class="btn-pagination">→</a>
            <?php else: ?>
                <span class="btn-disabled">→</span>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p class="text-center text-gray-600 dark:text-gray-300 mt-4">No records available.</p>
<?php endif; ?>


<style>     
 /* General Pagination Styles */
/* General Pagination Styles */

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

    .btn-blue {
        background-color: #005ff5;
        color: white;
        padding: 8px 12px;
        border-radius: 51px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .btn-blue:hover {
        background-color: darkblue;
    }


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
        border-radius: 1px;
        color: gray;
    }

    .btn-page {
        font-weight: bold;
        padding: 0.6rem 1rem;
        background-color: #2563eb;
        color: white;
        border-radius: 6px;
    }



</style><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/layouts/pagination.blade.php ENDPATH**/ ?>