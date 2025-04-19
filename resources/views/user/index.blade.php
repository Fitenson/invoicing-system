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
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Updated By</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="user-row" data-id="{{ $user->id }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_by_name }}</td>
                                <td>{{ $user->created_at->format('d M  Y') }}</td>
                                <td>{{ $user->updated_by_name }}</td>
                                <td>{{ $user->updated_at->format('d M  Y') }}</td>
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
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const deleteSelectedBtn = document.getElementById('deleteSelected');
        const viewSelectedBtn = document.getElementById('viewSelected');
        const editSelectedBtn = document.getElementById('editSelected');
        const userRows = document.querySelectorAll('.user-row');
        const deleteModalElement = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const deleteForm = document.getElementById('deleteForm');
        const columnSearchInputs = document.querySelectorAll('.column-search');


        // Handle select all checkbox
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                updateRowHighlight(checkbox);
            });
            updateActionButtons();
        });

        // Handle individual row checkboxes
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateRowHighlight(this);
                updateActionButtons();

                // Update "select all" checkbox status
                const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            });
        });

        // Edit button functionality
        editSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(rowCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 1) {
                window.location.href = `/users/${selectedIds[0]}/edit`;
            }
        });

        // Make entire row clickable to toggle checkbox
        userRows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't toggle if clicking on a button or link
                if (e.target.type !== 'checkbox' &&
                    !e.target.closest('.form-check') &&
                    !e.target.closest('.btn') &&
                    !e.target.closest('a')) {
                    const checkbox = this.querySelector('.row-checkbox');
                    checkbox.checked = !checkbox.checked;

                    // Trigger the change event to run our event handlers
                    const event = new Event('change');
                    checkbox.dispatchEvent(event);
                }
            });
        });

        // Double click on row to view user directly
        userRows.forEach(row => {
            row.addEventListener('dblclick', function() {
                const userId = this.getAttribute('data-id');
                window.location.href = `/users/${userId}`;
            });
        });

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


        // Main search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            filterTable();
        });

        // Column search functionality
        columnSearchInputs.forEach(input => {
            input.addEventListener('keyup', function() {
                filterTable();
            });
        });

        // Combined filter function for both main search and column search
        function filterTable() {
            const mainSearchTerm = searchInput.value.toLowerCase();
            const columnFilters = {};

            // Gather all column filter values
            columnSearchInputs.forEach(input => {
                const column = input.getAttribute('data-column');
                const value = input.value.toLowerCase();
                if (value) {
                    columnFilters[column] = value;
                }
            });

            userRows.forEach(row => {
                const rowData = {
                    name: row.querySelector('td:nth-child(2)').textContent.toLowerCase(),
                    email: row.querySelector('td:nth-child(3)').textContent.toLowerCase(),
                    date: row.querySelector('td:nth-child(4)').textContent.toLowerCase()
                };

                // Check if row matches the main search
                const matchesMainSearch = mainSearchTerm === '' ||
                    Object.values(rowData).some(text => text.includes(mainSearchTerm));

                // Check if row matches all column filters
                let matchesColumnFilters = true;
                for (const [column, value] of Object.entries(columnFilters)) {
                    if (column in rowData && !rowData[column].includes(value)) {
                        matchesColumnFilters = false;
                        break;
                    }
                }

                // Show row only if it matches both main search and column filters
                row.style.display = (matchesMainSearch && matchesColumnFilters) ? '' : 'none';
            });
        }
    });
</script>
@endpush
