<?php $__env->startSection('content'); ?>

<style>
    /* Dongrek font for Khmer text */
    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');

    .khmer-font {
        font-family: 'Dangrek', cursive;
    }

    .btn-green {
        background-color: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }

    .btn-gray {
        background-color: #333;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
</style>

<?php if(session('success')): ?>
<script>
Swal.fire({
    title: "<?php echo e(__('shifts.success')); ?>",
    text: "<?php echo e(session('success')); ?>",
    icon: "success",
    timer: 1000,
    confirmButtonText: "OK"
});
</script>
<?php endif; ?>

<?php if(session('error')): ?>
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
        icon: 'error',
        text: "<?php echo e(session('error')); ?>",
        padding: '2em',
    });
});
</script>
<?php endif; ?>

<div class="container mx-auto px-4 py-6">
    <?php if(session('active_shift')): ?>
    <div class="bg-yellow-500 text-white p-3 rounded-md flex justify-between items-center">
        <p class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.already_open_shift')); ?></span>
            <a href="<?php echo e(route('pos.index')); ?>" class="underline ml-2 font-semibold <?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.continue_pos')); ?></a>
        </p>
    </div>
    <?php else: ?>
    <?php if(auth()->check()): ?>
    <h1 class="text-2xl font-bold text-gray-700 dark:text-white mb-4 flex items-center space-x-2">
        <i class="fas fa-clock text-blue-500"></i>
        <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.create_new_shift')); ?></span>
        <img src="<?php echo e(asset('icons/shifts.png')); ?>" alt="Create New Shift" class="w-6 h-6">
    </h1>

    <form action="<?php echo e(route('shifts.store')); ?>" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <?php echo csrf_field(); ?>
        <!-- Hidden Fields -->
        <input type="hidden" name="user_id" value="<?php echo e(auth()->user()->id); ?>">
        <input type="hidden" name="time_open" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
        <input type="hidden" name="cash_submitted" value="0">

        <!-- Cash In Hand Input -->
        <div class="mb-4">
            <label for="cash_in_hand" class="block text-sm font-medium text-gray-700 dark:text-white flex items-center">
                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.cash_in_hand_label')); ?></span>
            </label>
            <input type="number" step="0.01" name="cash_in_hand" id="cash_in_hand"
                class="w-full px-4 py-2 mt-1 border rounded-lg text-gray-900 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring focus:ring-blue-300 focus:outline-none transition-all"
                value=" " required>
        </div>

        <!-- Buttons: Cancel & Submit -->
        <div class="flex justify-end mt-4 space-x-2">
            <a href="<?php echo e(route('shifts.index')); ?>"
                class="btn btn-gray flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-all">
                <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.cancel')); ?></span>
            </a>
            <button type="submit" class="btn-green flex items-center px-4 py-2">
                <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.create_shift')); ?></span>
            </button>
        </div>
    </form>
    <?php else: ?>
    <p class="text-red-500 font-semibold mt-4 flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span class="<?php if(app()->getLocale() == 'km'): ?> khmer-font <?php endif; ?>"><?php echo e(__('shifts.login_required')); ?></span>
    </p>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/shifts/create.blade.php ENDPATH**/ ?>