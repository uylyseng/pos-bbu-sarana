<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
  <!-- Breadcrumb Navigation -->
  <nav aria-label="breadcrumb" class="mb-4 flex">
    <ol class="flex text-gray-500 font-semibold dark:text-white">
      <li>
        <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-white-dark/70"><?php echo e(__('expenses.home')); ?></a>
      </li>
      <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
        <a href="javascript:;" class="text-primary"><?php echo e(__('expenses.expenses')); ?></a>
      </li>
    </ol>
  </nav>

  <!-- Flash Message -->
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

  <!-- Header & Add New Expense Button -->
  <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
    <h2 class="text-xl font-semibold dark:text-white dongrek-font"><?php echo e(__('expenses.expenses_list')); ?></h2>
    <div class="flex space-x-2">
      <!-- Restore Button -->
      <!-- <a href="<?php echo e(route('expenses.recovery')); ?>" class="btn-gray flex items-center px-4 py-2 rounded">
        <i class="fa-solid fa-trash-restore mr-2"></i>
        <span class="font-semibold"><?php echo e(__('expenses.restore')); ?></span>
      </a> -->

      <!-- Add New Expense Button -->
      <button class="btn-green flex items-center px-4 py-2 rounded" onclick="openCreateModal()">
        <i class="fas fa-plus-circle mr-2" style="color: white;"></i>
        <span class="font-semibold text-white dongrek-font"><?php echo e(__('expenses.add_new')); ?></span>
      </button>
    </div>
  </div>

  <!-- Expenses Table -->
  <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
    <table class="w-full whitespace-nowrap shadow-sm">
      <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
        <tr>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.id')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.expense_type')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.payment_method')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.reference')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.date')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.amount')); ?></th>
          <th class="px-4 py-2 dongrek-font"><?php echo e(__('expenses.description')); ?></th>
          <th class="px-4 py-2 text-center dongrek-font"><?php echo e(__('expenses.actions')); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-4 py-2 dark:text-white"><?php echo e($expense->id); ?></td>
          <td class="px-4 py-2 dark:text-white dongrek-font"><?php echo e($expense->expenseType->name ?? __('expenses.na')); ?></td>
          <td class="px-4 py-2 dark:text-white dongrek-font"><?php echo e($expense->paymentMethod->name ?? __('expenses.na')); ?></td>
          <td class="px-4 py-2 dark:text-white"><?php echo e($expense->reference); ?></td>
          <td class="px-4 py-2 dark:text-white"><?php echo e($expense->expense_date); ?></td>
          <td class="px-4 py-2 dark:text-white"><?php echo e($expense->amount); ?></td>
          <td class="px-4 py-2 dark:text-white dongrek-font"><?php echo e($expense->description); ?></td>
          <td class="px-4 py-2 text-center">
            <?php if($expense->trashed()): ?>
              <!-- Restore Button for Soft Deleted Expense -->
              <form action="<?php echo e(route('expenses.restore', $expense->id)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <button type="submit" class="btn-green inline-flex items-center px-3 py-1 rounded">
                  <i class="fa-solid fa-rotate-left mr-1"></i>
                  <span class="dongrek-font"><?php echo e(__('expenses.restore')); ?></span>
                </button>
              </form>
            <?php else: ?>
              <!-- Edit & Delete Buttons -->
              <button type="button" onclick="openEditModal(<?php echo e(json_encode($expense)); ?>)" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i>
                <span class="dongrek-font"><?php echo e(__('expenses.edit')); ?></span>
              </button>
              <button type="button" onclick="confirmDelete('<?php echo e(route('expenses.destroy', $expense->id)); ?>')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                <i class="fa-solid fa-trash mr-1" style="color: red;"></i>
                <span class="dongrek-font"><?php echo e(__('expenses.delete')); ?></span>
              </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div >
    <?php echo e($expenses->links('layouts.pagination')); ?>

  </div>
</div>

<!-- Modal Backdrop for Create/Edit -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-start justify-center transition-opacity duration-300">
  <!-- Create/Edit Expense Modal -->
  <div id="expenseModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg transform transition-all duration-300 ease-out opacity-0 -translate-y-12">
    <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white dongrek-font"><?php echo e(__('expenses.create_new_expense')); ?></h2>
    <form id="expenseForm" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <!-- Expense Type Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.expense_type')); ?></label>
        <select name="expense_type_id" id="expenseTypeId" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
          <option value=""><?php echo e(__('expenses.select_expense_type')); ?></option>
          <?php $__currentLoopData = $expenseTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <!-- Payment Method Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.payment_method')); ?></label>
        <select name="payment_method_id" id="paymentMethodId" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
          <option value=""><?php echo e(__('expenses.select_payment_method')); ?></option>
          <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <!-- Reference Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.reference')); ?></label>
        <input type="text" name="reference" id="reference" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="<?php echo e(__('expenses.enter_reference')); ?>">
      </div>
      <!-- Expense Date Field (Auto-set to Today) -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.expense_date')); ?></label>
        <input type="date" name="expense_date" id="expenseDate" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" value="<?php echo e(date('Y-m-d')); ?>" required>
      </div>
      <!-- Amount Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.amount')); ?></label>
        <input type="number" name="amount" id="amount" step="0.01" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
      </div>
      <!-- Description Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('expenses.description')); ?></label>
        <textarea name="description" id="description" rows="4" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="<?php echo e(__('expenses.enter_details')); ?>"></textarea>
      </div>
      <!-- Action Buttons -->
      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" onclick="closeModal()" class="btn-gray dongrek-font"><?php echo e(__('expenses.cancel')); ?></button>
        <button type="submit" class="btn-green dongrek-font" id="saveButton"><?php echo e(__('expenses.save')); ?></button>
      </div>
    </form>
  </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
  function openCreateModal() {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    const modal = document.getElementById('expenseModal');
    modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = '<?php echo e(__('expenses.create_new_expense')); ?>';
    document.getElementById('expenseForm').action = "<?php echo e(route('expenses.store')); ?>";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('expenseTypeId').value = '';
    document.getElementById('paymentMethodId').value = '';
    document.getElementById('reference').value = '';
    document.getElementById('expenseDate').value = "<?php echo e(date('Y-m-d')); ?>";
    document.getElementById('amount').value = '';
    document.getElementById('description').value = '';
    document.getElementById('saveButton').innerText = '<?php echo e(__('expenses.create_expense')); ?>';
  }

  function openEditModal(expense) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    const modal = document.getElementById('expenseModal');
    modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = '<?php echo e(__('expenses.edit_expense')); ?>';
    document.getElementById('expenseForm').action = `/expenses/${expense.id}`;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('expenseTypeId').value = expense.expense_type_id;
    document.getElementById('paymentMethodId').value = expense.payment_method_id;
    document.getElementById('reference').value = expense.reference;
    document.getElementById('expenseDate').value = expense.expense_date;
    document.getElementById('amount').value = expense.amount;
    document.getElementById('description').value = expense.description;
    document.getElementById('saveButton').innerText = '<?php echo e(__('expenses.update_expense')); ?>';
  }

  function closeModal() {
    document.getElementById('expenseModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
    setTimeout(() => {
      document.getElementById('modalBackdrop').classList.add('hidden');
    }, 300);
  }

  async function confirmDelete(deleteUrl) {
    const result = await Swal.fire({
      title: '<?php echo e(__('expenses.are_you_sure')); ?>',
      text: '<?php echo e(__('expenses.this_action_cannot_be_undone')); ?>',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: '<?php echo e(__('expenses.yes_delete_it')); ?>',
      cancelButtonText: '<?php echo e(__('expenses.cancel')); ?>',
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
  /* Add Dongrek font for Khmer text */
  .dongrek-font {
    font-family: 'Dangrek', 'Noto Sans Khmer', sans-serif;
  }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/expenses/index.blade.php ENDPATH**/ ?>