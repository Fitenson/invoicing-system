@extends('dashboard.app')


@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Clients</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                        <a href="{{ route('users.create') }}" class="btn btn-success create-btn m-2 mx-2">Create</a>
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Actions</th>
                            <th scope="col">Name</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Address</th>
                            <th scope="col">Company</th>
                            <th scope="col">Phone no</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="user-row" data-id="{{ $user->id }}">
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning edit-btn" data-id="{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button type="button" class="btn btn-danger delete-btn" onclick="document.getElementById('delete-form-{{ $user->id }}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->company }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>{{ $user->created_by_name }}</td>
                                <td>{{ $user->created_at->format('d M  Y') }}</td>
                                <td>{{ $user->updated_by_name }}</td>
                                <td>{{ $user->updated_at->format('d M  Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination links --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <small class="text-muted">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries</small>
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Delete confirmation modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected client(s)?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Row selection and highlighting
        const selectAll = document.getElementById('selectAll');
        const deleteSelectedBtn = document.getElementById('deleteSelected');
        const viewSelectedBtn = document.getElementById('viewSelected');
        const editSelectedBtn = document.getElementById('editSelected');
        const userRows = document.querySelectorAll('.user-row');
        // Individual edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const userId = this.getAttribute('data-id');
                window.location.href = `/users/${userId}/edit`;
            });
        });


        // Individual delete buttons
    // Add event listeners to all delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            destroyUser(userId);
        });
    });

    // Function to handle user deletion
    function destroyUser(userId) {
        // Create a POST request with CSRF protection (for Laravel)
        fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                // Remove the user row from the DOM or refresh the page
                const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                if (userRow) {
                    userRow.remove();
                } else {
                    // If can't find the specific row, reload the page
                    window.location.reload();
                }
            } else {
                console.error('Failed to delete user');
                alert('Failed to delete user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user');
        });
    }
    });
</script>
@endpush
