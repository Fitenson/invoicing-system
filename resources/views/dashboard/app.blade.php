<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Add this in your layout's head section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 220px;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column text-white" style="height: 100vh; background-color: #343a40; padding: 1rem;">
        <h4 class="text-white text-center mb-4">My App</h4>

        <a href="{{ url('/') }}" class="text-white mb-2 text-decoration-none">Dashboard</a>
        <a href="/user" class="text-white mb-2 text-decoration-none">Clients</a>
        <a href="/project" class="text-white mb-2 text-decoration-none">Projects</a>
        <a href="/invoice" class="text-white mb-2 text-decoration-none">Invoices</a>

        <div class="mt-auto text-center">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-white text-decoration-none">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>
