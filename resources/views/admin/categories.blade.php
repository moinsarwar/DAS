@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-heading mb-1">Doctor Specialties & Categories</h2>
            <p class="text-muted">Manage the medical categories, descriptions, and icons/images displayed on the public Doctors page.</p>
        </div>
    </div>

    <div class="row">
        <!-- Add Category Form -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Add Category</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Cardiologist">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="e.g. Highly qualified experts specialized in cardiologist treatments & consultations"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Icon Class (Bootstrap Icons)</label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. bi-heart-pulse-fill">
                            <div class="form-text text-muted small mb-2">
                                Use class from <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> (e.g. <code>bi-heart-pulse-fill</code>).
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Or Upload Custom Icon/Image</label>
                            <input type="file" name="category_image" class="form-control" accept="image/*">
                            <div class="form-text text-muted small">If uploaded, this custom image will be used instead of the Bootstrap icon class.</div>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg me-1"></i> Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">All Categories</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 100px;">Icon / Image</th>
                                    <th>Category Details</th>
                                    <th>Doctors Count</th>
                                    <th class="text-end pe-4" style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="ps-4">
                                            @if($category->image_path)
                                                <img src="{{ asset('storage/' . $category->image_path) }}" class="rounded shadow-sm border" style="width: 42px; height: 42px; object-fit: cover;" alt="Icon">
                                            @else
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                    <i class="bi {{ $category->icon ?? 'bi-heart-pulse-fill' }} fs-5"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="fw-bold mb-1">{{ $category->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $category->description ?? 'No description provided.' }}</p>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-primary border px-2.5 py-1.5 rounded-pill fw-semibold">
                                                {{ $category->doctors_count }} Doctors
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCategoryModal"
                                                    data-bs-id="{{ $category->id }}"
                                                    data-bs-name="{{ $category->name }}"
                                                    data-bs-description="{{ $category->description }}"
                                                    data-bs-icon="{{ $category->icon }}"
                                                    data-bs-image="{{ $category->image_path }}">
                                                    <i class="bi bi-pencil me-1"></i> Edit
                                                </button>
                                                
                                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                                        <i class="bi bi-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-folder-x fs-1 opacity-50"></i>
                                            <p class="mt-2 mb-0">No categories found. Add one on the left.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required placeholder="e.g. Oncologist">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" placeholder="e.g. Highly qualified experts specialized in oncologists treatments & consultations"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Icon Class (Bootstrap Icons)</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control" placeholder="e.g. bi-heart-pulse-fill">
                            <div class="form-text text-muted small">
                                Use class from <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> (e.g. <code>bi-heart-pulse-fill</code>).
                            </div>
                        </div>
                        
                        <!-- Image Preview and Toggle -->
                        <div class="mb-3" id="edit_image_preview_container" style="display: none;">
                            <label class="form-label fw-semibold">Current Custom Image</label>
                            <div class="d-flex align-items-center gap-3 border p-2.5 rounded bg-light">
                                <img id="edit_image_preview" src="" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Category">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="edit_remove_image">
                                    <label class="form-check-label fw-semibold text-danger small" for="edit_remove_image">
                                        Delete this image & fallback to Bootstrap icon class
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload New Custom Image (Optional)</label>
                            <input type="file" name="category_image" class="form-control" accept="image/*">
                            <div class="form-text text-muted small">Upload an image to override/replace the Bootstrap icon.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const editCategoryModal = document.getElementById('editCategoryModal');
        if (editCategoryModal) {
            editCategoryModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-bs-id');
                const name = button.getAttribute('data-bs-name');
                const description = button.getAttribute('data-bs-description') || '';
                const icon = button.getAttribute('data-bs-icon') || '';
                const imagePath = button.getAttribute('data-bs-image') || '';
                
                const form = editCategoryModal.querySelector('#editCategoryForm');
                form.action = `/admin/categories/${id}`;
                
                editCategoryModal.querySelector('#edit_name').value = name;
                editCategoryModal.querySelector('#edit_description').value = description;
                editCategoryModal.querySelector('#edit_icon').value = icon;

                const previewContainer = editCategoryModal.querySelector('#edit_image_preview_container');
                const previewImg = editCategoryModal.querySelector('#edit_image_preview');
                const removeCheckbox = editCategoryModal.querySelector('#edit_remove_image');

                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }

                if (imagePath) {
                    previewContainer.style.display = 'block';
                    previewImg.src = `/storage/${imagePath}`;
                } else {
                    previewContainer.style.display = 'none';
                    previewImg.src = '';
                }
            });
        }
    });
    </script>
@endsection