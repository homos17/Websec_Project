@extends('layouts.master')

@section('title', 'Support Tickets')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Support Tickets</h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($tickets->isEmpty())
                        <p class="text-center">No support tickets found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>#{{ $ticket->id }}</td>
                                            <td>{{ $ticket->user->name }}</td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ticket->status === 'open' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                            <td>{{ $ticket->updated_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
