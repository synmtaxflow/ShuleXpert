<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        #left-panel,
        #left-panel .navbar,
        #left-panel .navbar-default,
        #left-panel .main-menu,
        #left-panel .navbar-nav,
        #left-panel ul {
            background-color: #ffffff !important;
            color: #2f2f2f !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .nav-link,
        #left-panel a.nav-link,
        #left-panel li a,
        #left-panel .navbar-nav li a,
        #left-panel .navbar-nav > li > a {
            color: #2f2f2f !important;
            font-weight: 600;
            background-color: transparent !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .nav-link i,
        #left-panel a.nav-link i,
        #left-panel li a i,
        #left-panel .navbar-nav li a i,
        #left-panel .fa {
            color: #940000 !important;
        }

        #left-panel .nav-link:hover,
        #left-panel a.nav-link:hover,
        #left-panel li a:hover,
        #left-panel .navbar-nav li a:hover,
        #left-panel .navbar-nav > li > a:hover,
        #left-panel li:hover {
            background-color: #f5f5f5 !important;
            color: #2f2f2f !important;
        }

        .brand-text {
            color: #940000 !important;
            font-weight: 800;
        }

        body,
        body * {
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        /* Keep Font Awesome icons working (do not override their font-family) */
        .fa,
        .fa:before,
        i.fa,
        [class*="fa-"],
        [class^="fa-"],
        [class*="fa-"]:before,
        [class^="fa-"]:before {
            font-family: FontAwesome !important;
        }

        .bg-primary-custom {
            background-color: #940000 !important;
        }

        .btn-primary-custom {
            background-color: #940000;
            border-color: #940000;
            color: white;
            border-radius: 0 !important;
        }

        .btn-primary-custom:hover {
            background-color: #b30000;
            border-color: #b30000;
            color: white;
        }

        .btn-outline-primary-custom {
            border-color: #940000;
            color: #940000;
            border-radius: 0 !important;
        }

        .btn-outline-primary-custom:hover {
            background-color: #940000;
            border-color: #940000;
            color: white;
        }

        div, .card, .alert, .btn {
            border-radius: 0 !important;
        }

        .superadmin-sidebar-title {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .superadmin-brand-logo {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid rgba(148, 0, 0, 0.15);
            background: #fff;
        }

        .superadmin-brand-block {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .superadmin-sidebar-title .subtitle {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 2px;
        }
    </style>
</head>

<body>

<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div class="navbar-header superadmin-sidebar-title">
            <img src="{{ asset('images/shuleXpert.jpg') }}" alt="ShuleXpert" class="superadmin-brand-logo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
            <div class="superadmin-brand-block">
                <div class="brand-text">ShuleXpert</div>
                <div class="subtitle">Super Admin</div>
            </div>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a class="nav-link" href="{{ route('superAdminDashboard') }}">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.schools.register') }}">
                        <i class="fa fa-plus"></i> School Registration
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.schools.index') }}">
                        <i class="fa fa-building"></i> Registered Schools
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.change_password') }}">
                        <i class="fa fa-lock"></i> Change Password
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.user_password') }}">
                        <i class="fa fa-key"></i> User Password
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.customer_care') }}">
                        <i class="fa fa-comments"></i> Customer Care
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('superadmin.system_alerts') }}">
                        <i class="fa fa-bullhorn"></i> System Alerts
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<div id="right-panel" class="right-panel">
    <header id="header" class="header">
        <div class="header-menu">
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                <div class="header-left">
                    <span class="brand-text">Super Admin Panel</span>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="user-area dropdown float-right">
                    <a href="{{ route('logout') }}" class="btn btn-outline-primary-custom btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </header>

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
(function() {
    const IDLE_MS = 60 * 1000;
    const WARN_SECONDS = 30;
    const LOGOUT_URL = '{{ route('logout') }}';

    let idleTimer = null;
    let countdownTimer = null;
    let remaining = WARN_SECONDS;
    let overlay = null;

    function ensureOverlay() {
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'idle-logout-overlay';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;z-index:99999;';
        overlay.innerHTML =
            '<div style="width:min(520px,92vw);background:#fff;border-radius:12px;padding:18px 18px 14px;box-shadow:0 20px 60px rgba(0,0,0,.25);font-family:inherit;">'
            + '<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">'
            + '<div style="font-weight:800;color:#940000;">Security Warning</div>'
            + '<button type="button" id="idleStayBtn" style="border:1px solid rgba(148,0,0,.3);background:#fff;color:#940000;border-radius:8px;padding:6px 10px;cursor:pointer;">Stay Logged In</button>'
            + '</div>'
            + '<div style="margin-top:10px;color:#333;line-height:1.4;">System is idle. You will be logged out after <b id="idleCountdown">30</b> seconds.</div>'
            + '<div style="margin-top:10px;color:#666;font-size:.9rem;">Move the mouse, type, or click to continue.</div>'
            + '</div>';
        document.body.appendChild(overlay);
        const stayBtn = overlay.querySelector('#idleStayBtn');
        if (stayBtn) stayBtn.addEventListener('click', resetAll);
        return overlay;
    }

    function showWarning() {
        ensureOverlay();
        remaining = WARN_SECONDS;
        overlay.style.display = 'flex';
        const c = overlay.querySelector('#idleCountdown');
        if (c) c.textContent = String(remaining);
        if (countdownTimer) clearInterval(countdownTimer);
        countdownTimer = setInterval(() => {
            remaining -= 1;
            if (c) c.textContent = String(Math.max(0, remaining));
            if (remaining <= 0) {
                logoutNow();
            }
        }, 1000);
    }

    function hideWarning() {
        if (overlay) overlay.style.display = 'none';
        if (countdownTimer) clearInterval(countdownTimer);
        countdownTimer = null;
    }

    function logoutNow() {
        hideWarning();
        window.location.href = LOGOUT_URL;
    }

    function scheduleIdle() {
        if (idleTimer) clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_MS);
    }

    function resetAll() {
        hideWarning();
        scheduleIdle();
    }

    ['mousemove','mousedown','keydown','scroll','touchstart','click'].forEach(evt => {
        window.addEventListener(evt, resetAll, { passive: true });
    });

    scheduleIdle();
})();
</script>

</body>
</html>
