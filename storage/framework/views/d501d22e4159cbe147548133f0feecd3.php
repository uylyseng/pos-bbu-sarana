<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 <?php echo e(app()->getLocale() == 'km' ? 'khmer-dangrek' : ''); ?>">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70"><?php echo e(__('customers.home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary"><?php echo e(__('customers.assign_coupon')); ?></a>
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
    <!-- Outer Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-md p-6">
        <h1 class="text-xl font-bold mb-4 dark:text-white">
            <?php echo e(__('customers.manage_coupons')); ?> <?php echo e($customer->name); ?>

        </h1>



        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex -mb-px">
                <li class="mr-4">
                    <button onclick="showTab('assignTab')" id="assignTabBtn"
                        class="py-2 px-4 border-b-2 border-blue-500 text-blue-500 font-semibold">
                        <?php echo e(__('customers.assign_tab')); ?>

                    </button>
                </li>
                <li>
                    <button onclick="showTab('editTab')" id="editTabBtn"
                        class="py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-semibold">
                        <?php echo e(__('customers.edit_tab')); ?>

                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="assignTab" class="tab-content">
            <!-- Form to assign a single coupon -->
            <form
                action="<?php echo e(route('customers.assignCoupon', $customer->id)); ?>"
                method="POST"
                class="space-y-3"
            >
                <?php echo csrf_field(); ?>
                <div>
                    <label for="coupon_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                        <?php echo e(__('customers.select_coupon')); ?>

                    </label>
                    <select
                        name="coupon_id"
                        id="coupon_id"
                        class="border border-gray-300 dark:border-gray-600 p-2 w-full rounded dark:bg-gray-700 dark:text-white"
                    >
                        <option value=""><?php echo e(__('customers.choose_coupon')); ?></option>
                        <?php $__currentLoopData = $allCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($coupon->id); ?>">
                                <?php echo e($coupon->code); ?> (<?php echo e($coupon->discount); ?>%)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex items-center space-x-3 mt-4">
                    <!-- Assign Button -->
                    <button type="submit" class="assign-btn"><?php echo e(__('customers.assign')); ?></button>

                    <!-- Back Link -->
                    <a href="<?php echo e(route('customers.index')); ?>" class="back-btn"><?php echo e(__('customers.back')); ?></a>
                </div>
            </form>
        </div>

        <div id="editTab" class="tab-content hidden">
            <!-- List Already Assigned Coupons with Edit/Remove options -->
            <h2 class="text-lg font-semibold mb-2 dark:text-white"><?php echo e(__('customers.assigned_coupons')); ?></h2>
            <?php if($customer->coupons->count() > 0): ?>
                <ul class="space-y-3">
                    <?php $__currentLoopData = $customer->coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-center justify-between dark:text-gray-100 border p-3 rounded">
                            <div>
                                <strong><?php echo e($c->code); ?></strong>
                                <span class="ml-2">(<?php echo e(__('customers.discount')); ?>: <?php echo e($c->discount); ?>%)</span>
                            </div>
                            <div class="flex space-x-3">
                                <!-- Edit Link -->
                                <a href="<?php echo e(route('customers.editCoupon', [$customer->id, $c->id])); ?>"
                                   class="text-blue-500 hover:underline">
                                    <?php echo e(__('customers.edit')); ?>

                                </a>
                                <!-- Remove Form -->
                                <form action="<?php echo e(route('customers.removeCoupon', [$customer->id, $c->id])); ?>" method="POST"
                                      onsubmit="return confirm('<?php echo e(__('customers.confirm_remove_coupon')); ?>');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-600 hover:underline"><?php echo e(__('customers.remove')); ?></button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 dark:text-gray-300"><?php echo e(__('customers.no_coupons_assigned')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for Tab Handling -->
<script>
    function showTab(tabId) {
        // Hide all tab content divs
        document.querySelectorAll('.tab-content').forEach(function(el) {
            el.classList.add('hidden');
        });
        // Remove active classes from tab buttons
        document.getElementById('assignTabBtn').classList.remove('border-blue-500', 'text-blue-500');
        document.getElementById('assignTabBtn').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('editTabBtn').classList.remove('border-blue-500', 'text-blue-500');
        document.getElementById('editTabBtn').classList.add('border-transparent', 'text-gray-500');

        // Show selected tab
        document.getElementById(tabId).classList.remove('hidden');

        // Set active styling on the selected tab button
        if(tabId === 'assignTab'){
            document.getElementById('assignTabBtn').classList.add('border-blue-500', 'text-blue-500');
            document.getElementById('assignTabBtn').classList.remove('border-transparent', 'text-gray-500');
        } else if(tabId === 'editTab'){
            document.getElementById('editTabBtn').classList.add('border-blue-500', 'text-blue-500');
            document.getElementById('editTabBtn').classList.remove('border-transparent', 'text-gray-500');
        }
    }

    // Show the assign tab by default
    showTab('assignTab');
</script>

<!-- CSS for Buttons and Modal (same as before) -->
<style>
    .assign-btn {
        padding: 0.5rem 1rem;
        background-color: #2563EB;
        color: #FFFFFF;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    .assign-btn:hover {
        background-color: #1D4ED8;
    }
    .back-btn {
        padding: 0.5rem 1rem;
        background-color: #D1D5DB;
        color: #111827;
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .back-btn:hover {
        background-color: #E5E7EB;
        color: #000000;
    }
    /* Optional additional styling for tabs */
    .tab-content {
        padding-top: 1rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/customers/choose-coupon.blade.php ENDPATH**/ ?>