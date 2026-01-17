@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Contact Messages</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="messagesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $message)
                                    <tr class="{{ !$message->is_read ? 'bg-light' : '' }}">
                                        <td class="ps-4 text-nowrap text-muted small">
                                            {{ $message->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="fw-medium">{{ $message->name }}</td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span>{{ $message->email }}</span>
                                                @if($message->phone)
                                                    <span class="text-muted">{{ $message->phone }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $message->subject }}</td>
                                        <td style="max-width: 300px;">
                                            <div class="text-truncate" title="{{ $message->message }}">
                                                {{ Str::limit($message->message, 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($message->is_read)
                                                <span class="badge bg-success bg-opacity-10 text-success">Read</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning">Unread</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                @if(!$message->is_read)
                                                    <form action="{{ route('admin.messages.read', $message->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="Mark as Read">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#viewMessage{{ $message->id }}"
                                                    title="View Full Message">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- View Message Modal --}}
                                    <div class="modal fade" id="viewMessage{{ $message->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Message Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block">From</small>
                                                        <div class="fw-bold">{{ $message->name }}</div>
                                                        <div class="small text-muted">{{ $message->email }} |
                                                            {{ $message->phone ?? 'No Phone' }}</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block">Date</small>
                                                        <div>{{ $message->created_at->format('F d, Y h:i A') }}</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block">Subject</small>
                                                        <div class="fw-medium">{{ $message->subject }}</div>
                                                    </div>
                                                    <div class="p-3 bg-light rounded">
                                                        <small class="text-muted d-block mb-1">Message</small>
                                                        <p class="mb-0" style="white-space: pre-line;">{{ $message->message }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="mailto:{{ $message->email }}" class="btn btn-primary">
                                                        <i class="bi bi-reply-fill me-1"></i> Reply via Email
                                                    </a>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            No messages found.
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

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#messagesTable').DataTable({
                responsive: true,
                order: [[0, 'desc']] // Order by Date desc
            });
        });
    </script>
@endsection