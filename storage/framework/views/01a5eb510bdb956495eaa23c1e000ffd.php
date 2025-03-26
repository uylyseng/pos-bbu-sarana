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

<style>
    @import url('https://fonts.googleapis.com/css2?family=Dongrek&display=swap');

    .khmer-text {
        font-family: 'Dongrek', cursive;
    }

    /* Apply Khmer font to specific elements */
    .khmer-font {
        font-family: 'Dongrek', cursive;
    }

    /* Language selector styling */
    .lang-selector {
        display: inline-flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .lang-selector select {
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-left: 10px;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Language Selector -->
    <div class="lang-selector">
        <label for="language-select" class="khmer-font">ភាសា / Language:</label>
        <select id="language-select" onchange="changeLanguage(this.value)">
            <option value="en" <?php echo e(app()->getLocale() == 'en' ? 'selected' : ''); ?>>English</option>
            <option value="km" <?php echo e(app()->getLocale() == 'km' ? 'selected' : ''); ?>>ខ្មែរ (Khmer)</option>
        </select>
    </div>

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.breadcrumb_home') : 'Home'); ?>

                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="<?php echo e(route('exchange-rates.index')); ?>" class="text-primary khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.title') : 'Exchange Rates'); ?>

                </a>
            </li>
        </ol>
    </nav>

    <!-- Add New Exchange Rate Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white khmer-font">
            <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.list') : 'Exchange Rates List'); ?>

        </h2>
        <button class="btn-green flex items-center khmer-font" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.add_new') : 'Add New'); ?></span>
        </button>
    </div>

    <!-- Exchange Rates Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.id') : 'ID'); ?></th>
                    <th class="px-4 py-2 khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.from_currency') : 'From Currency'); ?></th>
                    <th class="px-4 py-2 khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.to_currency') : 'To Currency'); ?></th>
                    <th class="px-4 py-2 khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.rate') : 'Rate'); ?></th>
                    <th class="px-4 py-2 khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.date') : 'Date'); ?></th>
                    <th class="px-4 py-2 text-center khmer-font"><?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.actions') : 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $exchangeRates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchangeRate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2"><?php echo e($exchangeRate->id); ?></td>
                    <td class="px-4 py-2"><?php echo e($exchangeRate->fromCurrency->name); ?> (<?php echo e($exchangeRate->fromCurrency->code); ?>)</td>
                    <td class="px-4 py-2"><?php echo e($exchangeRate->toCurrency->name); ?> (<?php echo e($exchangeRate->toCurrency->code); ?>)</td>
                    <td class="px-4 py-2"><?php echo e($exchangeRate->rate); ?></td>
                    <td class="px-4 py-2"><?php echo e($exchangeRate->date); ?></td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2 khmer-font" onclick="openEditModal(<?php echo e(json_encode($exchangeRate)); ?>)">
                        <i class="fa-solid fa-pen-to-square" style="color: blue;"></i>
                        <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.edit') : 'Edit'); ?>

                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        <?php echo e($exchangeRates->links('layouts.pagination')); ?>

    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
   >
    <!-- Create/Edit Exchange Rate Modal -->
    <div id="exchangeRateModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white khmer-font">
            <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.create_new') : 'Create New Exchange Rate'); ?>

        </h2>
        <form id="exchangeRateForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.from_currency') : 'From Currency'); ?>

                </label>
                <select name="currency_from" id="currencyFrom" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($currency->id); ?>"><?php echo e($currency->name); ?> (<?php echo e($currency->code); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.to_currency') : 'To Currency'); ?>

                </label>
                <select name="currency_to" id="currencyTo" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($currency->id); ?>"><?php echo e($currency->name); ?> (<?php echo e($currency->code); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.exchange_rate') : 'Exchange Rate'); ?>

                </label>
                <input type="text" name="rate" id="exchangeRate" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.date') : 'Date'); ?>

                </label>
                <input type="date" name="date" id="exchangeRateDate" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" value="<?php echo e(date('Y-m-d')); ?>" required>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray khmer-font">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.cancel') : 'Cancel'); ?>

                </button>
                <button type="submit" class="btn-green khmer-font" id="saveButton">
                    <?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.save') : 'Save'); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling and Language -->
<script>
   function openCreateModal() {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('exchangeRateModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = "<?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.create_new') : 'Create New Exchange Rate'); ?>";
    document.getElementById('exchangeRateForm').action = "<?php echo e(route('exchange-rates.store')); ?>";
    document.getElementById('formMethod').value = "POST";

    // Reset form fields
    document.getElementById('currencyFrom').value = '';
    document.getElementById('currencyTo').value = '';
    document.getElementById('exchangeRate').value = '';
    document.getElementById('exchangeRateDate').value = '';
    document.getElementById('saveButton').innerText = "<?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.save') : 'Save'); ?>";
}

function openEditModal(exchangeRate) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('exchangeRateModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = "<?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.edit_rate') : 'Edit Exchange Rate'); ?>";

    // Set form action for update
    document.getElementById('exchangeRateForm').action = `/exchange-rates/${exchangeRate.id}`;
    document.getElementById('formMethod').value = "PUT";

    // Fill form fields with existing data
    document.getElementById('currencyFrom').value = exchangeRate.currency_from;
    document.getElementById('currencyTo').value = exchangeRate.currency_to;
    document.getElementById('exchangeRate').value = exchangeRate.rate;
    document.getElementById('exchangeRateDate').value = exchangeRate.date;
    document.getElementById('saveButton').innerText = "<?php echo e(app()->getLocale() == 'km' ? __('exchange-rates.update') : 'Update'); ?>";
}

function closeModal() {
    document.getElementById('exchangeRateModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
    setTimeout(() => {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }, 300);
}

function changeLanguage(lang) {
    window.location.href = "<?php echo e(route('exchange-rates.index')); ?>?lang=" + lang;
}
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/exchange-rates/index.blade.php ENDPATH**/ ?>