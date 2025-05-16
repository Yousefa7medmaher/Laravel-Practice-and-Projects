@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">User Management</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Current Credit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($user->getCreditBalance(), 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#creditModal{{ $user->id }}">
                                            Manage Credit
                                        </button>
                                        <a href="{{ route('users.profile', $user->id) }}" class="btn btn-info btn-sm">View Profile</a>
                                    </td>
                                </tr>

                                <!-- Credit Management Modal -->
                                <div class="modal fade" id="creditModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('users.manage-credit', $user->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Manage Credit for {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Current Credit: ${{ number_format($user->getCreditBalance(), 2) }}</label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Action</label>
                                                        <select name="action" class="form-select" required>
                                                            <option value="add">Add Credit</option>
                                                            <option value="subtract">Subtract Credit</option>
                                                            <option value="set">Set Credit</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Amount</label>
                                                        <input type="number" name="amount" class="form-control" required min="0" step="0.01">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason</label>
                                                        <textarea name="reason" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 