@extends('layouts.app')

@section('title', 'Edit Dynamic Page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Edit Dynamic Page
                    </h5>
                    <small class="text-muted">Modify page layout, title, content, or routing</small>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Page Title</label>
                            <input type="text" name="title" id="page-title" class="form-control" value="{{ old('title', $page->title) }}" placeholder="e.g. Oncology Services" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Page Slug / URL path</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">/page/</span>
                                <input type="text" name="slug" id="page-slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="oncology-services" required>
                            </div>
                            <div class="form-text small">Used for routing. Lowercase letters, numbers, and dashes only.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Page Content (HTML supported)</label>
                            <textarea name="content" class="form-control" rows="12" placeholder="Page content... ">{{ old('content', $page->content) }}</textarea>
                            <div class="form-text small">You can use standard HTML tags like <code>&lt;h3&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>, <code>&lt;strong&gt;</code> to style your content.</div>
                        </div>

                        <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                            <h6 class="fw-bold mb-3">Visibility Settings</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $page->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">Publish Page (Active)</label>
                                    </div>
                                    <div class="form-text text-muted" style="font-size:0.75rem;">Inactive pages are inaccessible.</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="show_in_navbar" id="show_in_navbar" value="1" {{ $page->show_in_navbar ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="show_in_navbar">Show in Header Navbar</label>
                                    </div>
                                    <div class="form-text text-muted" style="font-size:0.75rem;">Adds link to the top navigation header.</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="show_in_footer" id="show_in_footer" value="1" {{ $page->show_in_footer ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="show_in_footer">Show in Footer Links</label>
                                    </div>
                                    <div class="form-text text-muted" style="font-size:0.75rem;">Adds link to the footer navigation panel.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Automatic Slug Generator (Only run if users type and slug is empty)
        $('#page-title').on('input', function() {
            if ($('#page-slug').val() === '') {
                let title = $(this).val();
                let slug = title.toLowerCase()
                                .replace(/[^a-z0-9\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-');
                $('#page-slug').val(slug);
            }
        });
    });
</script>
@endpush
