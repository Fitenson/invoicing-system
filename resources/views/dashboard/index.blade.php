@extends('dashboard.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        <p>You are logged in!</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Invoices</h5>
                        <p>15</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Customers</h5>
                        <p>8</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
