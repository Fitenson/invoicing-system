@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <!-- Top Action Buttons -->
    <div class="mb-3 d-flex justify-content-start gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <button form="updateProjectForm" type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update
        </button>
    </div>

    <h3 class="mb-4">Edit Project</h3>

    <!-- Form Section -->
    <form id="updateProjectForm" action="{{ route('projects.create') }}" method="POST">
        @csrf

        <!-- Project -->
        <div class="mb-3">
            <label for="project" class="form-label">Project</label>
            <input type="text" class="form-control @error('project') is-invalid @enderror"
                id="project" name="project" value="{{ old('project') }}" required>
            @error('project')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Project Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="description" class="form-control @error('description') is-invalid @enderror"
                id="description" name="description" value="{{ old('description') }}" required>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Rate per hour -->
        <div class="mb-3">
            <label for="rate_per_hour" class="form-label">Rate / Hour</label>
            <textarea class="form-control @error('rate_per_hour') is-invalid @enderror"
                id="rate_per_hour" name="rate_per_hour" rows="3">{{ old('rate_per_hour') }}</textarea>
            @error('rate_per_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Total hour -->
        <div class="mb-3">
            <label for="total_hour" class="form-label">Total Hour</label>
            <textarea class="form-control @error('total_hour') is-invalid @enderror"
                id="total_hour" name="total_hour" rows="3">{{ old('total_hour') }}</textarea>
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
