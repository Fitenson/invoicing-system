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
    <form id="updateProjectForm" action="{{ route('projects.update', $project['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Project Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $project['name']) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- User Dropdown -->
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-select @error('client') is-invalid @enderror" id="client" name="client" required>
                <option value="">-- Select Client --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('client', $project['client']) == $user['id'] ? 'selected' : '' }}>
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
                      id="description" name="description" rows="4">{{ old('description', $project['description']) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Rate per hour -->
        <div class="mb-3">
            <label for="rate_per_hour" class="form-label">Rate / Hour</label>
            <input type="number" class="form-control @error('rate_per_hour') is-invalid @enderror"
                   id="rate_per_hour" name="rate_per_hour" value="{{ old('rate_per_hour', $project['rate_per_hour']) }}">
            @error('rate_per_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Total hour -->
        <div class="mb-3">
            <label for="total_hours" class="form-label">Total Hour</label>
            <input type="number" class="form-control @error('total_hours') is-invalid @enderror"
                   id="total_hours" name="total_hours" value="{{ old('total_hours', $project['total_hours']) }}">
            @error('total_hours')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
@endsection

@push('scripts')
@endpush
