@extends('layouts.master')

@section('title', 'Support Ticket')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Complaint #{{ $ticket->id }}</h3>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">Back to Complaints</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Complaint Details</h4>
                        <p><strong>User:</strong> {{ $ticket->user->name }}</p>
                        <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $ticket->status === 'sent' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <h4>User's Message</h4>
                        <div class="p-3 bg-light rounded">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                        <div class="mb-4">
                            <h4>Previous Response</h4>
                            <div class="p-3 bg-light rounded">
                                {{ $ticket->admin_reply }}
                            </div>
                            <small class="text-muted">Replied by: {{ $ticket->admin->name ?? 'Support Team' }}</small>
                        </div>
                    @endif

                    @if($ticket->status !== 'done')
                        <div class="mb-4">
                            <h4>Reply to Complaint</h4>
                            <form method="POST" action="{{ route('admin.support.reply', $ticket) }}">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control @error('admin_reply') is-invalid @enderror"
                                        name="admin_reply" rows="4" required>{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                                    @error('admin_reply')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                    <form method="POST" action="{{ route('admin.support.close', $ticket) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to close this complaint?')">
                                            Close Complaint
                                        </button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            This complaint has been closed.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
