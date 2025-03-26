<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Shifts</h1>
    <?php if(session('success')): ?>
      <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

   

    <div class="table-responsive">
        <table id="shiftsTable" class="table table-striped table-bordered display" style="width:100%">
            <thead class="bg-blue-200">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Time Open</th>
                    <th>Time Close</th>
                    <th>Cash In Hand</th>
                    <th>Cash Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($shift->id); ?></td>
                    <td><?php echo e($shift->user->name); ?></td>
                    <td><?php echo e($shift->time_open); ?></td>
                    <td><?php echo e($shift->time_close ?? 'N/A'); ?></td>
                    <td><?php echo e($shift->cash_in_hand); ?></td>
                    <td><?php echo e($shift->cash_submitted); ?></td>
                    <td>
                       
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#shiftsTable').DataTable({
            "order": [[0, "asc"]], // Default sorting by ID ascending
            "paging": true,
            "searching": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/shifts/index.blade.php ENDPATH**/ ?>