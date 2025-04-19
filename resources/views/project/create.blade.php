@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <!-- Top Action Buttons -->
    <div class="mb-3 d-flex justify-content-start gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <button form="createUserForm" type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Create
        </button>
    </div>

    <h3 class="mb-4">Create New Project</h3>

    <!-- Form Section -->
    <form id="createUserForm" action="{{ route('projects.store') }}" method="POST">
        @csrf

        <!-- Project Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- User Dropdown -->
        <div class="mb-3">
            <label for="user_id" class="form-label">Client</label>
            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                <option value="">-- Select Client --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                        {{ $user['name'] }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea type="description" class="form-control @error('description') is-invalid @enderror"
                id="description" name="description" value="{{ old('description') }}" rows="3"></textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Rate per hour -->
        <div class="mb-3">
            <label for="rate_per_hour" class="form-label">Rate / Hour</label>
            <input type="number" id="rate_per_hour" name="rate_per_hour">{{ old('rate_per_hour') }}</input>
            @error('rate_per_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Total hour -->
        <div class="mb-3">
            <label for="total_hour" class="form-label">Total Hour</label>
            <input type="number" id="total_hour" name="total_hour">{{ old('total_hour') }}</input>
            @error('total_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addressField = document.getElementById('address');
        addressField.addEventListener('blur', function () {
            this.value = this.value
                .split('\n')
                .map(line => line.trim())
                .filter(line => line)
                .join('\n');
        });
    });
</script>
@endpush
