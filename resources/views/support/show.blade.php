@extends('layouts.master')

@section('title', 'Support Ticket')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Support Ticket</h3>
                    <a href="{{ route('support.list') }}" class="btn btn-secondary">Back to Tickets</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Ticket Details</h4>
                        <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $ticket->status === 'open' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <h4>Your Message</h4>
                        <div class="p-3 bg-light rounded">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                        <div class="mb-4">
                            <h4>Support Response</h4>
                            <div class="p-3 bg-light rounded">
                                {{ $ticket->admin_reply }}
                            </div>
                            <small class="text-secondary">Replied by: {{ $ticket->admin->name ?? 'Support Team' }}</small>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Waiting for support team response...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection