@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <!-- Top Action Buttons -->
    <div class="mb-3 d-flex justify-content-start gap-2">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <button form="createInvoiceForm" type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Create
        </button>
    </div>

    <h3 class="mb-4">Create New Invoice</h3>

    <form id="createInvoiceForm" action="{{ route('invoices.store') }}" method="POST">
        @csrf

        <!-- Invoice number -->
        <div class="mb-3">
            <label for="invoice_number" class="form-label">Invoice Number</label>
            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" readonly>
        </div>

        <!-- Client -->
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-select" id="client" name="invoice[client]" required>
                <option value="">-- Select Client --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="invoice[description]" rows="3">{{ old('description') }}</textarea>
        </div>

        <!-- GridView: Invoice Projects Table -->
        <h5 class="mb-2">Invoice Projects</h5>
        <div class="mb-3">
            <div class="d-flex gap-2">
                <select id="projectSelect" class="form-select" style="max-width: 300px;">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option
                            value="{{ $project['id'] }}"
                            data-name="{{ $project['name'] }}"
                            data-rate="{{ $project['rate_per_hour'] }}"
                            data-hours="{{ $project['total_hours'] }}"
                        >
                            {{ $project['name'] }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary" onclick="addProjectRow()">Add Project</button>
            </div>
        </div>

        <table class="table table-bordered" id="projectTable">
            <thead class="table-light">
                <tr>
                    <th>Project Name</th>
                    <th>Rate/Hour</th>
                    <th>Total Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="projectTableBody">
                <!-- Rows will be added here -->
            </tbody>
        </table>
    </form>
</div>

<!-- Inline Script -->
<script>
    function addProjectRow() {
        const select = document.getElementById('projectSelect');
        const projectId = select.value;
        const selectedOption = select.options[select.selectedIndex];

        const projectName = selectedOption.getAttribute('data-name');
        const ratePerHour = selectedOption.getAttribute('data-rate');
        const totalHours = selectedOption.getAttribute('data-hours');

        if (!projectId) return alert('Please select a project.');

        // Prevent duplicates
        if (document.querySelector(`input[name="invoice_has_projects[][project]"][value="${projectId}"]`)) {
            return alert('This project has already been added.');
        }

        const tbody = document.getElementById('projectTableBody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                ${projectName}
                <input type="hidden" name="invoice_has_projects[][project]" value="${projectId}">
            </td>
            <td>
                <input type="number" name="invoice_has_projects[][rate_per_hour]" class="form-control" value="${ratePerHour}" step="any" min="0">
            </td>
            <td>
                <input type="number" name="invoice_has_projects[][total_hours]" class="form-control" value="${totalHours}" step="any" min="0">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Delete</button>
            </td>
        `;

        tbody.appendChild(row);
        select.value = '';
    }

</script>
@endsection
