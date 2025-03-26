@extends('layouts.app')

@section('content')


    <div class="container mx-auto px-4 py-2">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark dongrek-font">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">{{ __('text.Home') }}</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">{{ __('text.Category') }}</a>
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

        <!-- Add New Category Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 dongrek-font">
            <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Categories List') }}</h2>
            <button class="btn-green flex items-center justify-end" onclick="openCreateModal()">
                <i class="fas fa-plus-circle mr-2"></i>
                <span class="font-semibold {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Add New') }}</span>
            </button>
        </div>

        <!-- Categories Table -->
        <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
            <table id="categoryTable" class="w-full whitespace-nowrap shadow-sm table-auto">
                <thead class="bg-blue-500 text-white">
                    <tr class="dongrek-font">
                        <th class="align-middle px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Image') }}</th>
                        <th class="align-middle px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Name') }}</th>
                        <th class="align-middle px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Description') }}</th>
                        <th class="align-middle px-4 py-2 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Status') }}</th>
                        <th class="align-middle px-4 py-2 text-center {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="align-middle px-4 py-2">
                            @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" class="category-img">
                            @else
                            <img src="{{ asset('assets/images/nophoto.png' . $category->image) }}" alt="Category Image" class="category-img">
                            @endif
                        </td>
                        <td class="align-middle px-4 py-2">{{ $category->name }}</td>
                        <td class="align-middle px-4 py-2">{{ $category->description }}</td>
                        <td class="align-middle px-4 py-2">
                            <span class="inline-block px-3 py-1 rounded-full border-2 font-bold {{ $category->status == 'active' ? 'border-green-600 text-green-600' : 'border-red-600 text-red-600' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>
                        <td class="align-middle px-4 py-2 text-center dongrek-font">
                            <button class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700" onclick="openEditModal({{ json_encode($category) }})">
                                <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> {{ __('text.Edit') }}
                            </button>
                            <button class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700" onclick="confirmDelete('{{ route('categories.destroy', $category->id) }}')">
                                <i class="fa-solid fa-trash mr-1" style="color: red;"></i> {{ __('text.Delete') }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
</div>

<!-- Pagination -->
@if ($categories->total() > 0)
<div>
    {{ $categories->links('layouts.pagination') }}
</div>
@else
    <p class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.No categories available.') }}</p>
@endif


    <!-- MODAL BACKDROP (Blur Effect) -->
    <div
  id="modalBackdrop"
  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
>
  <!-- Modal Container -->
  <div
    id="categoryModal"
    class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
  >
    <!-- Close Button -->


    <!-- Modal Title -->
    <h2 id="modalTitle" class="text-lg font-semibold mb-4 dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
      {{ __('text.Create New Category') }}
    </h2>

            <form id="categoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Category Name') }}</label>
                    <input type="text" name="name" id="categoryName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Image') }}</label>
                    <input type="file" name="image" id="categoryImage" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                    <!-- Image preview -->
                    <img id="imagePreview" src="" alt="Image Preview" class="mt-2 h-24 w-auto rounded hidden">
                </div>
                <div class="mb-4">
                <label for="categoryStatus" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200 {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
                    {{ __('text.Status') }}
                </label>
                <select name="status" id="categoryStatus"
                    class="form-status w-full px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('text.Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('text.Inactive') }}</option>
                </select>
            </div>


                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 dark:text-white {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Description') }}</label>
                    <textarea name="description" id="categoryDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal()" class="btn-gray {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Cancel') }}</button>
                    <button type="submit" class="btn-green {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}" id="saveButton">{{ __('text.Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal Handling and Image Preview -->
    <script>

// Show (slide down) the modal for Create
function openCreateModal() {
  const backdrop = document.getElementById('modalBackdrop');
  const modal = document.getElementById('categoryModal');

  // Remove hidden on backdrop so it's visible
  backdrop.classList.remove('hidden');

  // Remove the classes that keep it invisible & up
  modal.classList.remove('opacity-0', '-translate-y-12');

  // Set modal title with translation
  document.getElementById('modalTitle').innerText = '{{ __('text.Create New Category') }}';
  document.getElementById('categoryForm').action = "{{ route('categories.store') }}";
  document.getElementById('formMethod').value = "POST";
  document.getElementById('categoryName').value = '';
  document.getElementById('categoryDescription').value = '';
  document.getElementById('categoryStatus').value = 'active';
  document.getElementById('imagePreview').classList.add('hidden');
  document.getElementById('imagePreview').src = '';
  document.getElementById('categoryImage').value = '';
  document.getElementById('saveButton').innerText = '{{ __('text.Save') }}';
}

// Show (slide down) the modal for Edit
function openEditModal(category) {
  const backdrop = document.getElementById('modalBackdrop');
  const modal = document.getElementById('categoryModal');

  // Remove hidden from backdrop
  backdrop.classList.remove('hidden');

  // Remove invisible/up classes so it slides down
  modal.classList.remove('opacity-0', '-translate-y-12');

  // Set modal title with translation
  document.getElementById('modalTitle').innerText = '{{ __('text.Edit Category') }}';
  document.getElementById('categoryForm').action = `/categories/${category.id}`;
  document.getElementById('formMethod').value = "PUT";
  document.getElementById('categoryName').value = category.name;
  document.getElementById('categoryDescription').value = category.description;
  document.getElementById('categoryStatus').value = category.status;
  document.getElementById('saveButton').innerText = '{{ __('text.Update') }}';

  // If an image exists, show it in the preview; otherwise, hide the preview.
  if (category.image) {
    document.getElementById('imagePreview').src = `{{ asset('storage') }}/${category.image}`;
    document.getElementById('imagePreview').classList.remove('hidden');
  } else {
    document.getElementById('imagePreview').src = '';
    document.getElementById('imagePreview').classList.add('hidden');
  }
}

// Hide (slide up) the modal
function closeModal() {
  const backdrop = document.getElementById('modalBackdrop');
  const modal = document.getElementById('categoryModal');

  // Add classes to fade out & slide up
  modal.classList.add('opacity-0', '-translate-y-12');

  // Wait for transition to finish, then hide backdrop
  setTimeout(() => {
    backdrop.classList.add('hidden');
  }, 300); // match the duration-300 in the modal classes
}

        async function confirmDelete(deleteUrl) {
            const result = await Swal.fire({
                title: '{{ __('text.Are you sure?') }}',
                text: '{{ __('text.This action cannot be undone!') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('text.Yes, delete it!') }}',
                cancelButtonText: '{{ __('text.Cancel') }}',
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
        document.getElementById('categoryImage').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('imagePreview');
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    </script>

<style>
    /* Import Dongrek font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');

    .dongrek-font {
        font-family: 'Dangrek', 'Arial', sans-serif;
        letter-spacing: 0.01em;
        font-feature-settings: "kern" 1;
        text-rendering: optimizeLegibility;
        font-weight: 500;
    }
</style>
@endsection
