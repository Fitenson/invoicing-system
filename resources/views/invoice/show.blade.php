@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <!-- Top Action Buttons -->
    <div class="mb-3 d-flex justify-content-start gap-2">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <button form="updateInvoiceForm" type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update
        </button>
    </div>

    <h3 class="mb-4">Edit Invoice</h3>

    <!-- Form Section -->
    <form id="updateInvoiceForm" action="{{ route('invoices.update', $invoice['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Invoice number -->
        <div class="mb-3">
            <label for="invoice_number" class="form-label">Invoice Number</label>
            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $invoice['invoice_number']) }}" readonly>
        </div>


        <!-- Client -->
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-select @error('client') is-invalid @enderror" id="client" name="invoice[client]" required>
                <option value="">-- Select Client --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('client', $invoice['client']) == $user['id'] ? 'selected' : '' }}>
                        {{ $user['name'] }}
                    </option>
                @endforeach
            </select>
            @error('client')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                id="description" name="invoice[description]" rows="3">{{ old('description', $invoice['description']) }}</textarea>
        </div>


        <!-- Invoice Projects Section -->
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
                {{-- Show either old values (on validation error) or saved invoice projects --}}
                @if(old('invoice_has_projects'))
                    @foreach(old('invoice_has_projects') as $item)
                        @php
                            $project = collect($projects)->firstWhere('id', $item['project']);
                        @endphp
                        <tr>
                            <td>
                                {{ $project['name'] ?? 'Unknown' }}
                                <input type="hidden" name="invoice_has_projects[][project]" value="{{ $item['project'] }}">
                            </td>
                            <td>
                                <input type="number" name="invoice_has_projects[][rate_per_hour]" class="form-control" value="{{ $item['rate_per_hour'] }}" step="any" min="0">
                            </td>
                            <td>
                                <input type="number" name="invoice_has_projects[][total_hours]" class="form-control" value="{{ $item['total_hours'] }}" step="any" min="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    @foreach($invoice['projects'] as $invoice_project)
                        @if(!empty($invoice_project['project']))
                            <tr data-id="{{ $invoice_project['id'] }}">
                                <td>
                                    {{ $invoice_project['project']['name'] ?? 'Unknown' }}
                                    <input type="hidden" name="project" value="{{ $invoice_project['project']['id'] }}">
                                </td>
                                <td>
                                    <input type="number" name="rate_per_hour" class="form-control" value="{{ $invoice_project['project']['rate_per_hour'] }}" step="any" min="0" readonly>
                                </td>
                                <td>
                                    <input type="number" name="total_hours" class="form-control" value="{{ $invoice_project['project']['total_hours'] }}" step="any" min="0" readonly>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm delete-project-btn"
                                        data-id="{{ $invoice_project['id'] }}"
                                    >Delete</button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-project-btn').forEach(button => {
        button.addEventListener('click', function () {
            const projectId = this.getAttribute('data-id');
            const row = this.closest('tr');

            if (confirm('Are you sure you want to delete this project from the invoice?')) {
                fetch(`/show/${projectId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        row.remove();
                    } else {
                        return response.json().then(data => {
                            alert(data.message || 'Failed to delete project.');
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Something went wrong.');
                });
            }
        });
    });
});
</script>
@endpush
