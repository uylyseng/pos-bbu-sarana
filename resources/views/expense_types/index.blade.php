@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-gray-300">
                    {{ __("Home") }}
                </a>
            </li>
            <li class="before:content-[''] before:block before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">
                    {{ __("Expense Types") }}
                </a>
            </li>
        </ol>
    </nav>
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

    <!-- Add New Expense Type Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Expense Types List</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2" style="color: white;"></i>
            <span class="font-semibold text-white">Add New</span>
        </button>
    </div>

    <!-- Expense Types Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Expense Type Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseTypes as $expenseType)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 dark:text-white">{{ $expenseType->id }}</td>
                    <td class="px-4 py-2 dark:text-white">{{ $expenseType->name }}</td>
                    <td class="px-4 py-2 dark:text-white">{{ $expenseType->description }}</td>
                    <td class="px-4 py-2 text-center">
                        <button type="button" onclick="openEditModal({{ json_encode($expenseType) }})" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                            <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> Edit
                        </button>
                        <button type="button" onclick="confirmDelete('{{ route('expense_types.destroy', $expenseType->id) }}')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                            <i class="fa-solid fa-trash mr-1" style="color: red;"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div >
        {{ $expenseTypes->links('layouts.pagination') }}
    </div>
</div>

<!-- Modal Backdrop -->
<div id="modalBackdrop"   class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Expense Type Modal -->
    <div id="expenseTypeModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white">Create New Expense Type</h2>
        <form id="expenseTypeForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <!-- Expense Type Name Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Expense Type Name</label>
                <input type="text" name="name" id="expenseTypeName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <!-- Description Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Description</label>
                <textarea name="description" id="expenseTypeDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
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
        const modal = document.getElementById('expenseTypeModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Create New Expense Type';
        document.getElementById('expenseTypeForm').action = "{{ route('expense_types.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('expenseTypeName').value = '';
        document.getElementById('expenseTypeDescription').value = '';
        document.getElementById('saveButton').innerText = 'Save';
    }

    function openEditModal(expenseType) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        const modal = document.getElementById('expenseTypeModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Edit Expense Type';
        document.getElementById('expenseTypeForm').action = `/expense_types/${expenseType.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('expenseTypeName').value = expenseType.name;
        document.getElementById('expenseTypeDescription').value = expenseType.description;
        document.getElementById('saveButton').innerText = 'Update';
    }

    function closeModal() {
        document.getElementById('expenseTypeModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }
</script>

@endsection
