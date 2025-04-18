@extends('dashboard.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">User Grid</h2>

    <div class="row">
        @forelse($users as $user)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text text-muted">{{ $user->email }}</p>
                        <p class="card-text"><small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small></p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end">
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No users found.</div>
            </div>
        @endforelse
    </div>

    {{-- Uncomment this for pagination --}}
    {{-- <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div> --}}
</div>
@endsection
