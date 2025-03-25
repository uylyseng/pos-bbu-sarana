@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white/70">
                    {{ __("Home") }}
                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">
                    {{ __("Stores") }}
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

    <!-- Add New Store Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Stores List</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold">Add New</span>
        </button>
    </div>

    <!-- Stores Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm table-auto">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="align-middle px-4 py-2">Image</th>
                    <th class="align-middle px-4 py-2">Name</th>
                    <th class="align-middle px-4 py-2">Contact</th>
                    <th class="align-middle px-4 py-2">Address</th>
                    <th class="align-middle px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($stores as $store)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="align-middle px-4 py-2">
                        @if($store->image)
                        <img
                            src="{{ asset('storage/' . $store->image) }}"
                            alt="Store Image"
                            class="store-img">
                        @else
                        <img
                            src="{{ asset('assets/images/nophoto.png') }}"
                            alt="Store Image"
                            class="store-img">
                        @endif
                    </td>
                    <td class="align-middle px-4 py-2">{{ $store->name }}</td>
                    <td class="align-middle px-4 py-2">{{ $store->contact }}</td>
                    <td class="align-middle px-4 py-2">{{ $store->address }}</td>
                    <td class="align-middle px-4 py-2 text-center">
                        <button class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700" onclick="openEditModal({{ json_encode($store) }})">
                            <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> Edit
                        </button>
                        <button class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700" onclick="confirmDelete('{{ route('stores.destroy', $store->id) }}')">
                            <i class="fa-solid fa-trash mr-1" style="color: red;"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $stores->links() }}
    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"   class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Store Modal -->
    <div id="storeModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white">Create New Store</h2>
        <form id="storeForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Store Name</label>
                <input type="text" name="name" id="storeName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Image</label>
                <input type="file" name="image" id="storeImage" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                <!-- Image preview -->
                <img id="imagePreview" src="" alt="Image Preview" class="mt-2 h-24 w-auto rounded hidden">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Contact</label>
                <input type="text" name="contact" id="storeContact" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Address</label>
                <textarea name="address" id="storeAddress" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Receipt Header</label>
                <textarea name="receipt_header" id="storeReceiptHeader" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white">Receipt Footer</label>
                <textarea name="receipt_footer" id="storeReceiptFooter" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray">Cancel</button>
                <button type="submit" class="btn-green" id="saveButton">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling and Image Preview -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('storeModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Create New Store';
        document.getElementById('storeForm').action = "{{ route('stores.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('storeName').value = '';
        document.getElementById('storeContact').value = '';
        document.getElementById('storeAddress').value = '';
        document.getElementById('storeReceiptHeader').value = '';
        document.getElementById('storeReceiptFooter').value = '';
        document.getElementById('storeStatus').value = 'active';

        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('imagePreview').src = '';
        document.getElementById('storeImage').value = '';
        document.getElementById('saveButton').innerText = 'Save';
    }

    function openEditModal(store) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('storeModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = 'Edit Store';
        document.getElementById('storeForm').action = `/stores/${store.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('storeName').value = store.name;
        document.getElementById('storeContact').value = store.contact;
        document.getElementById('storeAddress').value = store.address;
        document.getElementById('storeReceiptHeader').value = store.receipt_header;
        document.getElementById('storeReceiptFooter').value = store.receipt_footer;
        document.getElementById('storeStatus').value = store.status;
        document.getElementById('saveButton').innerText = 'Update';

        // If an image exists, show it in the preview; otherwise, hide the preview.
        if (store.image) {
            document.getElementById('imagePreview').src = `{{ asset('storage') }}/${store.image}`;
            document.getElementById('imagePreview').classList.remove('hidden');
        } else {
            document.getElementById('imagePreview').src = '';
            document.getElementById('imagePreview').classList.add('hidden');
        }
    }

    function closeModal() {
        document.getElementById('storeModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
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

    // Image preview when uploading a file
    document.getElementById('storeImage').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
</script>

@endsection
