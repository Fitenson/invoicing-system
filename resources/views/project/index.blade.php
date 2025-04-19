@extends('dashboard.app')


@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Projects</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                        <a href="{{ route('projects.create') }}" class="btn btn-success create-btn m-2 mx-2">Create</a>
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Client</th>
                            <th scope="col">Rate/Hour</th>
                            <th scope="col">Total Hour</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Updated By</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $index => $project)
                            <tr class="user-row" data-id="{{ $project->id }}">
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->description }}</td>
                                <td>{{ $project->client_name }}</td>
                                <td>{{ $project->rate_per_hour }}</td>
                                <td>{{ $project->total_hours }}</td>
                                <td>{{ $project->created_at->format('d M  Y') }}</td>
                                <td>{{ $project->created_by_name }}</td>
                                <td>{{ $project->updated_at->format('d M  Y') }}</td>
                                <td>{{ $project->updated_by_name }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-warning edit-btn" data-id="{{ $project->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form id="delete-form-{{ $project->id }}" action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button type="button" class="btn btn-danger delete-btn" onclick="document.getElementById('delete-form-{{ $project->id }}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">No projects found.</td>
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
            <small class="text-muted">Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} entries</small>
        </div>
        <div>
            {{ $projects->links() }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
