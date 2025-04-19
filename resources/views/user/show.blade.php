@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <!-- Top Action Buttons -->
    <div class="mb-3 d-flex justify-content-start gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
        <button form="updateUserForm" type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update
        </button>
    </div>

    <h3 class="mb-4">Edit Client</h3>

    <!-- Form Section -->
    <form id="updateUserForm" action="{{ route('users.update', $user['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Username/Login Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name', $user['name']) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Full Name -->
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                id="full_name" name="full_name" value="{{ old('full_name', $user['full_name']) }}" required>
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{ old('email', $user['email']) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone number -->
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                id="phone_number" name="phone_number" value="{{ old('phone_number', $user['phone_number']) }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Company -->
        <div class="mb-3">
            <label for="company" class="form-label">Company</label>
            <input type="text" class="form-control @error('company') is-invalid @enderror"
                id="company" name="company" value="{{ old('company', $user['company']) }}">
            @error('company')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Address -->
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror"
                id="address" name="address" rows="3">{{ old('address', $user['address']) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
@endsection
