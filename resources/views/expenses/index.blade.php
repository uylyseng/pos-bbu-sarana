@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Breadcrumb Navigation -->
  <nav aria-label="breadcrumb" class="mb-4 flex">
    <ol class="flex text-gray-500 font-semibold dark:text-white">
      <li>
        <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
      </li>
      <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
        <a href="javascript:;" class="text-primary">Expenses</a>
      </li>
    </ol>
  </nav>

  <!-- Flash Message -->
  @if(session('success'))
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
        text: "{{ session('success') }}",
        padding: '2em',
      });
    });
  </script>
  @endif

  <!-- Header & Add New Expense Button -->
  <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
    <h2 class="text-xl font-semibold dark:text-white">Expenses List</h2>
    <div class="flex space-x-2">
      <!-- Restore Button -->
      <!-- <a href="{{ route('expenses.recovery') }}" class="btn-gray flex items-center px-4 py-2 rounded">
    <i class="fa-solid fa-trash-restore mr-2"></i>
    <span class="font-semibold">Restore</span>
</a> -->

      <!-- Add New Expense Button -->
      <button class="btn-green flex items-center px-4 py-2 rounded" onclick="openCreateModal()">
        <i class="fas fa-plus-circle mr-2" style="color: white;"></i>
        <span class="font-semibold text-white">Add New</span>
      </button>
    </div>
</div>

  <!-- Expenses Table -->
  <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
    <table class="w-full whitespace-nowrap shadow-sm">
      <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Expense Type</th>
          <th class="px-4 py-2">Payment Method</th>
          <th class="px-4 py-2">Reference</th>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Description</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($expenses as $expense)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-4 py-2 dark:text-white">{{ $expense->id }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->expenseType->name ?? 'N/A' }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->paymentMethod->name ?? 'N/A' }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->reference }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->expense_date }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->amount }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->description }}</td>
          <td class="px-4 py-2 text-center">
            @if($expense->trashed())
              <!-- Restore Button for Soft Deleted Expense -->
              <form action="{{ route('expenses.restore', $expense->id) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-green inline-flex items-center px-3 py-1 rounded">
                  <i class="fa-solid fa-rotate-left mr-1"></i> Restore
                </button>
              </form>
            @else
              <!-- Edit & Delete Buttons -->
              <button type="button" onclick="openEditModal({{ json_encode($expense) }})" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> Edit
              </button>
              <button type="button" onclick="confirmDelete('{{ route('expenses.destroy', $expense->id) }}')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                <i class="fa-solid fa-trash mr-1" style="color: red;"></i> Delete
              </button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div >
    {{ $expenses->links('layouts.pagination') }}
  </div>
</div>

<!-- Modal Backdrop for Create/Edit -->
<div id="modalBackdrop"  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
  <!-- Create/Edit Expense Modal -->
  <div id="expenseModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
    <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white">Create New Expense</h2>
    <form id="expenseForm" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <!-- Expense Type Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Expense Type</label>
        <select name="expense_type_id" id="expenseTypeId" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
          <option value="">Select Expense Type</option>
          @foreach($expenseTypes as $type)
          <option value="{{ $type->id }}">{{ $type->name }}</option>
          @endforeach
        </select>
      </div>
      <!-- Payment Method Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Payment Method</label>
        <select name="payment_method_id" id="paymentMethodId" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
          <option value="">Select Payment Method</option>
          @foreach($paymentMethods as $method)
          <option value="{{ $method->id }}">{{ $method->name }}</option>
          @endforeach
        </select>
      </div>
      <!-- Reference Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Reference</label>
        <input type="text" name="reference" id="reference" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Enter reference (optional)">
      </div>
      <!-- Expense Date Field (Auto-set to Today) -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Expense Date</label>
        <input type="date" name="expense_date" id="expenseDate" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" value="{{ date('Y-m-d') }}" required>
      </div>
      <!-- Amount Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Amount</label>
        <input type="number" name="amount" id="amount" step="0.01" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
      </div>
      <!-- Description Field -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1 dark:text-white">Description</label>
        <textarea name="description" id="description" rows="4" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Enter expense details (optional)"></textarea>
      </div>
      <!-- Attachment Field -->
     
      <!-- Action Buttons -->
      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" onclick="closeModal()" class="btn-gray">Cancel</button>
        <button type="submit" class="btn-green" id="saveButton">Save</button>
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
    document.getElementById('modalTitle').innerText = 'Create New Expense';
    document.getElementById('expenseForm').action = "{{ route('expenses.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('expenseTypeId').value = '';
    document.getElementById('paymentMethodId').value = '';
    document.getElementById('reference').value = '';
    document.getElementById('expenseDate').value = "{{ date('Y-m-d') }}";
    document.getElementById('amount').value = '';
    document.getElementById('description').value = '';
    document.getElementById('saveButton').innerText = 'Create Expense';
  }

  function openEditModal(expense) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    const modal = document.getElementById('expenseModal');
    modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
    document.getElementById('modalTitle').innerText = 'Edit Expense';
    document.getElementById('expenseForm').action = `/expenses/${expense.id}`;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('expenseTypeId').value = expense.expense_type_id;
    document.getElementById('paymentMethodId').value = expense.payment_method_id;
    document.getElementById('reference').value = expense.reference;
    document.getElementById('expenseDate').value = expense.expense_date;
    document.getElementById('amount').value = expense.amount;
    document.getElementById('description').value = expense.description;
    document.getElementById('saveButton').innerText = 'Update Expense';
  }

  function closeModal() {
    document.getElementById('expenseModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
    setTimeout(() => {
      document.getElementById('modalBackdrop').classList.add('hidden');
    }, 300);
  }

  async function confirmDelete(deleteUrl) {
    const result = await Swal.fire({
      title: 'Are you sure?',
      text: 'This action cannot be undone!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
    });
    if (result.isConfirmed) {
      let form = document.createElement('form');
      form.method = 'POST';
      form.action = deleteUrl;
      let csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';
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
@endsection
