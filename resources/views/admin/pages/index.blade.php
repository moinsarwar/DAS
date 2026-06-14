@extends('layouts.app')

@section('title', 'Manage Dynamic Pages')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-richtext-fill text-primary me-2"></i> Manage Pages</h5>
                <small class="text-muted">Create and manage dynamic public pages</small>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Create Page
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug / URL</th>
                            <th>Active</th>
                            <th>Show in Navbar</th>
                            <th>Show in Footer</th>
                            <th>Last Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $page->title }}</span>
                                </td>
                                <td>
                                    <code class="small text-primary">/page/{{ $page->slug }}</code>
                                    <a href="{{ url('page/' . $page->slug) }}" target="_blank" class="ms-1 text-muted" title="View Page">
                                        <i class="bi bi-box-arrow-up-right small"></i>
                                    </a>
                                </td>
                                <td>
                                    @if($page->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1.5"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1.5"><i class="bi bi-x-circle-fill me-1"></i> Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $page->show_in_navbar ? '<i class="bi bi-check2 text-success fs-5"></i>' : '<i class="bi bi-dash text-muted"></i>' !!}
                                </td>
                                <td>
                                    {!! $page->show_in_footer ? '<i class="bi bi-check2 text-success fs-5"></i>' : '<i class="bi bi-dash text-muted"></i>' !!}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $page->updated_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-fill me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pages.delete', $page->id) }}" method="POST" id="delete-page-form-{{ $page->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="confirmAction(event, 'delete-page-form-{{ $page->id }}', 'Delete Page?', 'Are you sure you want to delete this dynamic page?')">
                                                <i class="bi bi-trash-fill me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-inline-block p-4 bg-light rounded-circle mb-3">
                                        <i class="bi bi-file-earmark-x text-muted fs-1"></i>
                                    </div>
                                    <h5 class="text-muted">No Dynamic Pages Created</h5>
                                    <p class="text-muted small mb-0">Create new custom pages to add content to your header and footer menus.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
