<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.purchases')); ?></a>
            </li>
        </ol>
    </nav>
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
    <!-- Header & Add New Purchase Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 dongrek-font">
        <h2 class="text-xl font-semibold dark:text-white <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.purchases_list')); ?></h2>
        <a href="<?php echo e(route('purchases.create')); ?>" class="btn btn-green btn-sm">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Add New')); ?></span>
        </a>
    </div>

    <!-- Purchases Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr class="dongrek-font">
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.id')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.date')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.supplier')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.reference')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.discount')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.subtotal')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.grand_total')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Status')); ?></th>
                    <th class="px-4 py-2 text-center <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('text.Actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-2 dark:text-white"><?php echo e($purchase->id); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : __('text.na')); ?></td>

                    <td class="px-4 py-2 dark:text-white">
                        <?php echo e($purchase->supplier ? $purchase->supplier->name : __('text.na')); ?>

                    </td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($purchase->reference); ?></td>

                    <td class="px-4 py-2 dark:text-white"><?php echo e($purchase->discount); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e(number_format($purchase->subtotal, 2)); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e(number_format($purchase->total, 2)); ?></td>
                    <td class="px-4 py-2">
                    <span class="inline-block px-3 py-1 rounded-full border-2 font-bold
                        <?php echo e($purchase->status === 'Received' ? 'border-green-600 text-green-600' : 'border-red-600 text-red-600'); ?>">
                        <?php echo e($purchase->status === 'Received' ? __('text.received') : __('text.pending')); ?>

                    </span>
                </td>

                    <td class="px-4 py-2 text-center">
                        <a href="<?php echo e(route('purchases.edit', $purchase->id)); ?>" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                        <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> <?php echo e(__('text.Edit')); ?>

                        </a>

                        <button class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700" onclick="confirmDelete('<?php echo e(route('purchases.destroy', $purchase->id)); ?>')">
                                <i class="fa-solid fa-trash mr-1" style="color: red;"></i> <?php echo e(__('text.Delete')); ?>

                            </button>
                    </td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div >
        <?php echo e($purchases->links('layouts.pagination')); ?>

    </div>
    <!-- Pagination -->

</div>
<script>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/purchases/index.blade.php ENDPATH**/ ?>