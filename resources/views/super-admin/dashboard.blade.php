@extends('layouts.super-admin.app')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard Overview')

@section('content')

<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>0</h3>
                <p>Total Branches</p>
            </div>
            <i class="small-box-icon bi bi-shop"></i>
            <a href="#" class="small-box-footer">
                Manage branches <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>0</h3>
                <p>Total Users</p>
            </div>
            <i class="small-box-icon bi bi-people"></i>
            <a href="#" class="small-box-footer">
                Manage users <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>0</h3>
                <p>Total Products</p>
            </div>
            <i class="small-box-icon bi bi-box-seam"></i>
            <a href="#" class="small-box-footer">
                Manage products <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>0</h3>
                <p>Total Orders</p>
            </div>
            <i class="small-box-icon bi bi-cart-check"></i>
            <a href="#" class="small-box-footer">
                View orders <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

</div>

<div class="row g-3">

    <!-- Sales Overview -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-semibold">Business Overview</h3>
            </div>

            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-bar-chart-line display-3 text-secondary"></i>
                    <h5 class="mt-3">Sales Analytics</h5>
                    <p class="text-secondary mb-0">
                        Sales analytics will appear here once order data is available.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-semibold">Quick Actions</h3>
            </div>

            <div class="card-body">
                <div class="d-grid gap-3">

                    <a href="#" class="btn btn-outline-primary text-start">
                        <i class="bi bi-shop me-2"></i>
                        Add New Branch
                    </a>

                    <a href="#" class="btn btn-outline-success text-start">
                        <i class="bi bi-person-plus me-2"></i>
                        Add New User
                    </a>

                    <a href="#" class="btn btn-outline-warning text-start">
                        <i class="bi bi-box-seam me-2"></i>
                        Add New Product
                    </a>

                    <a href="#" class="btn btn-outline-secondary text-start">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>
                        Generate Reports
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Recent Orders -->
<div class="card mt-3">

    <div class="card-header">
        <h3 class="card-title fw-semibold">Recent Orders</h3>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No orders available yet.
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

</div>

@endsection
