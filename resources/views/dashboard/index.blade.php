@extends('dashboard.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        <div class="row mt-2">
            <p>You are logged in!</p>
            <h5>Total Income (RM): <span class="fw-normal">{{ $data['total_income'] }}</span></h5>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Clients</h5>
                        <p>{{ $data['total_client'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Projects</h5>
                        <p>{{ $data['total_project'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Invoices</h5>
                        <p>{{ $data['total_invoice'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
