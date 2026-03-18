@extends('includes.superadmin_nav')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .dashboard-hero {
        background: #fff7f7;
        border: 1px solid #f0dada;
        padding: 18px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .dashboard-hero .hero-title {
        font-size: 1.4rem;
        margin-bottom: 6px;
    }
    .dashboard-hero .hero-subtitle {
        color: #6c757d;
        margin-bottom: 0;
    }
    .dashboard-hero .hero-actions .btn {
        margin-left: 8px;
    }
    .dashboard-card {
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        height: 100%;
    }
    .dashboard-card .card-title {
        font-weight: 600;
        color: #940000;
        margin-bottom: 8px;
    }
    .dashboard-card .stat-note {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    .dashboard-card .card-icon {
        font-size: 2rem;
        color: rgba(148, 0, 0, 0.35);
    }
</style>

<div class="container-fluid mt-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="dashboard-hero">
        <div>
            <div class="text-muted" style="font-size: 0.9rem;">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</div>
            <div class="hero-title">Hello, Super Admin</div>
            <p class="hero-subtitle">Manage school registrations and view all registered schools.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('superadmin.schools.register') }}" class="btn btn-primary-custom btn-sm">
                <i class="fa fa-plus"></i> Register School
            </a>
            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-primary-custom btn-sm">
                <i class="fa fa-building"></i> View Schools
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-title">Demo Schools</div>
                            <p class="stat-note">Schools running in Demo environment.</p>
                        </div>
                        <div class="card-icon"><i class="fa fa-flask"></i></div>
                    </div>
                    <div class="mt-2" style="font-size: 1.8rem; font-weight: 800; color: #2f2f2f;">
                        {{ $demoSchoolsCount ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-title">Live Schools</div>
                            <p class="stat-note">Schools running in Live environment.</p>
                        </div>
                        <div class="card-icon"><i class="fa fa-rocket"></i></div>
                    </div>
                    <div class="mt-2" style="font-size: 1.8rem; font-weight: 800; color: #2f2f2f;">
                        {{ $liveSchoolsCount ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-title">School Registration</div>
                            <p class="stat-note">Create new school accounts and admin logins.</p>
                        </div>
                        <div class="card-icon"><i class="fa fa-plus"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('superadmin.schools.register') }}" class="btn btn-primary-custom btn-sm">Open</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-title">Registered Schools</div>
                            <p class="stat-note">View all schools in the system.</p>
                        </div>
                        <div class="card-icon"><i class="fa fa-building"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('superadmin.schools.index') }}" class="btn btn-primary-custom btn-sm">Open</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-title">Security</div>
                            <p class="stat-note">Change your Super Admin password.</p>
                        </div>
                        <div class="card-icon"><i class="fa fa-lock"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('superadmin.change_password') }}" class="btn btn-outline-primary-custom btn-sm">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
