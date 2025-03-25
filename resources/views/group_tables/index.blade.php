@extends('layouts.app')

@section('content')

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

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Group Tables</a>
            </li>
        </ol>
    </nav>

    <!-- Add New Group Table Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Group Tables List</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold">Add New</span>
        </button>
    </div>

    <!-- Group Tables Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupTables as $groupTable)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2">{{ $groupTable->id }}</td>
                    <td class="px-4 py-2">{{ $groupTable->name }}</td>
                    <td class="px-4 py-2">{{ $groupTable->descript }}</td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal({{ json_encode($groupTable) }})">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> Edit
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('{{ route('group_tables.destroy', $groupTable->id) }}')">
                            <i class="fa-solid fa-trash" style="color: red;"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div >
        
        @if ($groupTables->total() > 0)
<div>
    {{ $groupTables->links('layouts.pagination') }}
</div>
@else
    <p>No GroupTables available.</p>
@endif
    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Group Table Modal -->
    <div id="groupTableModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white">Create New Group Table</h2>
        <form id="groupTableForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Group Table Name</label>
                <input type="text" name="name" id="groupTableName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Description</label>
                <textarea name="descript" id="groupTableDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
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
        document.getElementById('groupTableModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Create New Group Table';
        document.getElementById('groupTableForm').action = "{{ route('group_tables.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('groupTableName').value = '';
        document.getElementById('groupTableDescription').value = '';
        document.getElementById('saveButton').innerText = 'Save';
    }

    function openEditModal(groupTable) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('groupTableModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Edit Group Table';
        document.getElementById('groupTableForm').action = `/group_tables/${groupTable.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('groupTableName').value = groupTable.name;
        document.getElementById('groupTableDescription').value = groupTable.descript;
        document.getElementById('saveButton').innerText = 'Update';
    }

    function closeModal() {
        document.getElementById('groupTableModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }
</script>

    
@endsection
