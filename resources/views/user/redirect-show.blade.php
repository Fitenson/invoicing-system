@extends('dashboard.app')

@section('content')
<script>
    // Use location.replace() so it replaces the current history entry
    window.location.replace("{{ route('users.show', $id) }}");
</script>
@endsection
