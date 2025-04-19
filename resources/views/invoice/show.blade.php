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
            <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $invoice['invoice_number']) }}" readonly>
            @error('invoice_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- User Dropdown -->
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-select @error('client') is-invalid @enderror" id="client" name="client" required>
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
                      id="description" name="description" rows="4">{{ old('description', $invoice['description']) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
@endsection

@push('scripts')
@endpush
