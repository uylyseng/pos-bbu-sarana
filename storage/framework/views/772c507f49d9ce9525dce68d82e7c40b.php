<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>
        Swal.fire({
            title: "Success!",
            text: "<?php echo e(session('success')); ?>",
            icon: "success",
            timer: 1000,
            confirmButtonText: "OK"
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        Swal.fire({
            title: "Error!",
            text: "<?php echo e(session('error')); ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
    </script>
<?php endif; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <!-- Title dynamically changes font based on locale -->
                <h1 class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>">
                    <?php echo e(__('text.welcome')); ?>

                </h1>

                <!-- Description dynamically changes font based on locale -->
                <p class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>">
                    <?php echo e(__('text.description')); ?>

                </p>

                <!-- Language Switcher -->

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/home.blade.php ENDPATH**/ ?>