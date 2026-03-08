<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.meta')
    <title>SCHOOL MANAGER</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @yield('style')
    @include('partials.style')
    <style>
        /* =============================================
           DESIGN SYSTEM - SCHOOL MANAGER 2025
        ============================================= */
        :root {
            --primary:        #c41e3a;
            --primary-dark:   #8b1a2e;
            --primary-light:  rgba(196, 30, 58, 0.1);
            --sidebar-bg:     #0f172a;
            --sidebar-hover:  rgba(255, 255, 255, 0.06);
            --sidebar-active: rgba(196, 30, 58, 0.18);
            --sidebar-text:   #94a3b8;
            --sidebar-text-hover: #f1f5f9;
            --sidebar-heading: #475569;
            --topbar-bg:      #ffffff;
            --body-bg:        #f1f5f9;
            --card-radius:    12px;
            --card-shadow:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --card-shadow-hover: 0 10px 25px rgba(0,0,0,0.1);
            --border-color:   #e2e8f0;
            --text-primary:   #0f172a;
            --text-secondary: #64748b;
            --radius-sm:      6px;
            --radius-md:      10px;
            --radius-lg:      14px;
            --transition:     0.2s ease;
        }

        /* =============================================
           BASE
        ============================================= */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            background-color: var(--body-bg) !important;
            color: var(--text-primary) !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
        }

        #layoutSidenav_content {
            background-color: var(--body-bg) !important;
            top: 0 !important;
        }

        /* =============================================
           TOPBAR
        ============================================= */
        .topnav {
            background-color: var(--topbar-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05) !important;
            height: 64px !important;
            padding: 0 1.25rem !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1030 !important;
        }

        .topnav .navbar-brand {
            font-weight: 700 !important;
            font-size: 1rem !important;
            color: var(--primary) !important;
            letter-spacing: -0.02em !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .topnav .navbar-brand .brand-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .topnav #sidebarToggle {
            color: var(--text-secondary) !important;
            border: none !important;
            background: none !important;
            padding: 8px !important;
            border-radius: var(--radius-sm) !important;
            transition: background var(--transition) !important;
        }

        .topnav #sidebarToggle:hover {
            background: var(--body-bg) !important;
            color: var(--text-primary) !important;
        }

        /* User Avatar */
        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.02em;
            flex-shrink: 0;
        }

        .user-avatar-lg {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-meta-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-meta-role {
            font-size: 11px;
            color: var(--text-secondary);
            line-height: 1.2;
        }

        /* Topbar dropdown */
        .topnav .dropdown-menu {
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
            padding: 6px !important;
            margin-top: 8px !important;
        }

        .topnav .dropdown-item {
            border-radius: var(--radius-sm) !important;
            padding: 8px 12px !important;
            font-size: 13px !important;
            color: var(--text-primary) !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: background var(--transition) !important;
        }

        .topnav .dropdown-item:hover {
            background: var(--body-bg) !important;
            color: var(--primary) !important;
        }

        .topnav .dropdown-item.text-danger:hover {
            background: rgba(239,68,68,0.06) !important;
            color: #dc2626 !important;
        }

        .topnav .dropdown-divider {
            border-color: var(--border-color) !important;
            margin: 4px 0 !important;
        }

        .topnav .dropdown-header-info {
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topnav .btn-icon-topbar {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md) !important;
            background: none !important;
            border: none !important;
            color: var(--text-secondary) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: background var(--transition) !important;
            position: relative;
        }

        .topnav .btn-icon-topbar:hover {
            background: var(--body-bg) !important;
            color: var(--primary) !important;
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            border: 2px solid white;
        }

        .topnav .user-toggle {
            border: none !important;
            background: none !important;
            padding: 4px 8px !important;
            border-radius: var(--radius-md) !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: background var(--transition) !important;
        }

        .topnav .user-toggle:hover {
            background: var(--body-bg) !important;
        }

        /* =============================================
           SIDEBAR
        ============================================= */
        #layoutSidenav #layoutSidenav_nav .sidenav {
            background-color: var(--sidebar-bg) !important;
            border-right: none !important;
            box-shadow: 2px 0 20px rgba(0,0,0,0.15) !important;
            padding-top: 0 !important;
        }

        .sidenav .sidenav-menu {
            padding: 0 0 8px !important;
        }

        /* Sidebar Brand */
        .sidenav-brand-area {
            padding: 8px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidenav-brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidenav-brand-text {
            font-size: 13px;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: -0.01em;
        }

        .sidenav-brand-sub {
            font-size: 10px;
            color: var(--sidebar-text);
            font-weight: 400;
            margin-top: 1px;
        }

        /* Section headings */
        .sidenav .sidenav-menu-heading {
            color: var(--sidebar-heading) !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            padding: 16px 20px 4px !important;
            margin: 0 !important;
            background: none !important;
        }

        /* Nav links */
        .sidenav .nav-link {
            color: var(--sidebar-text) !important;
            padding: 9px 12px 9px 16px !important;
            border-radius: 8px !important;
            margin: 1px 8px !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            transition: all var(--transition) !important;
            border-left: 3px solid transparent !important;
            text-decoration: none !important;
        }

        .sidenav .nav-link:hover {
            color: var(--sidebar-text-hover) !important;
            background-color: var(--sidebar-hover) !important;
        }

        .sidenav .nav-link.active,
        .sidenav .nav-link:not(.collapsed) {
            color: #fff !important;
            background-color: var(--sidebar-active) !important;
            border-left-color: var(--primary) !important;
        }

        .sidenav .nav-link .nav-link-icon {
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            opacity: 0.8 !important;
        }

        .sidenav .nav-link .nav-link-icon svg {
            width: 15px !important;
            height: 15px !important;
        }

        .sidenav .sidenav-collapse-arrow {
            margin-left: auto !important;
            color: var(--sidebar-text) !important;
            font-size: 11px !important;
            transition: transform var(--transition) !important;
        }

        .sidenav .nav-link:not(.collapsed) .sidenav-collapse-arrow {
            transform: rotate(180deg) !important;
        }

        /* Nested nav */
        .sidenav-menu-nested {
            padding: 2px 0 6px !important;
            margin-left: 8px !important;
        }

        .sidenav-menu-nested .nav-link {
            color: var(--sidebar-text) !important;
            padding: 7px 12px 7px 36px !important;
            font-size: 12.5px !important;
            font-weight: 400 !important;
            border-radius: 6px !important;
            margin: 1px 8px !important;
            border-left: none !important;
            position: relative;
        }

        .sidenav-menu-nested .nav-link::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--sidebar-heading);
            transition: background var(--transition);
        }

        .sidenav-menu-nested .nav-link:hover::before,
        .sidenav-menu-nested .nav-link.active::before {
            background: var(--primary) !important;
        }

        .sidenav-menu-nested .nav-link:hover {
            color: var(--sidebar-text-hover) !important;
            background: rgba(255,255,255,0.04) !important;
        }

        .sidenav-menu-nested .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.06) !important;
        }

        /* Sidebar footer */
        .sidenav .sidenav-footer {
            background: rgba(0,0,0,0.25) !important;
            border-top: 1px solid rgba(255,255,255,0.06) !important;
            padding: 14px 20px !important;
        }

        .sidenav .sidenav-footer-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidenav-footer-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidenav .sidenav-footer-subtitle {
            font-size: 10px !important;
            color: var(--sidebar-text) !important;
            line-height: 1.2;
        }

        .sidenav .sidenav-footer-title {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #f1f5f9 !important;
            line-height: 1.2;
        }

        /* =============================================
           CARDS
        ============================================= */
        .card {
            border: 1px solid var(--border-color) !important;
            border-radius: var(--card-radius) !important;
            box-shadow: var(--card-shadow) !important;
            background: #fff !important;
            transition: box-shadow var(--transition) !important;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 14px 20px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            color: var(--text-primary) !important;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
        }

        .card-body {
            padding: 20px !important;
        }

        /* =============================================
           PAGE HEADER
        ============================================= */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            padding: 28px 0 60px !important;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .page-header-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #fff !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }

        .page-header-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        /* =============================================
           FORMS
        ============================================= */
        .form-control,
        .form-select {
            padding: 9px 14px !important;
            border: 1.5px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            font-size: 13.5px !important;
            color: var(--text-primary) !important;
            background-color: #fff !important;
            transition: all var(--transition) !important;
            font-family: 'Inter', sans-serif !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            outline: none !important;
        }

        .form-label {
            font-size: 13px !important;
            font-weight: 500 !important;
            color: var(--text-primary) !important;
            margin-bottom: 6px !important;
        }

        /* =============================================
           BUTTONS
        ============================================= */
        .btn-1,
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            color: white !important;
            border: none !important;
            border-radius: var(--radius-md) !important;
            padding: 9px 18px !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            transition: all var(--transition) !important;
            box-shadow: 0 2px 6px rgba(196,30,58,0.3) !important;
        }

        .btn-1:hover,
        .btn-primary:hover {
            opacity: 0.92 !important;
            box-shadow: 0 4px 12px rgba(196,30,58,0.4) !important;
            transform: translateY(-1px) !important;
            color: white !important;
        }

        .btn {
            font-family: 'Inter', sans-serif !important;
            font-size: 13.5px !important;
            border-radius: var(--radius-md) !important;
        }

        /* =============================================
           TABLES
        ============================================= */
        table.dataTable thead th,
        table thead th {
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: var(--text-secondary) !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 10px 14px !important;
            background: #fafafa !important;
        }

        table.dataTable tbody td,
        table tbody td {
            font-size: 13.5px !important;
            padding: 12px 14px !important;
            border-bottom: 1px solid #f8fafc !important;
            color: var(--text-primary) !important;
            vertical-align: middle !important;
        }

        table tbody tr:hover {
            background: #fafafa !important;
        }

        /* =============================================
           BADGES
        ============================================= */
        .badge {
            font-family: 'Inter', sans-serif !important;
            font-weight: 500 !important;
            border-radius: 20px !important;
        }

        /* =============================================
           ALERTS
        ============================================= */
        .alert {
            border-radius: var(--radius-md) !important;
            border: none !important;
            font-size: 13.5px !important;
        }

        /* =============================================
           FOOTER
        ============================================= */
        .footer-admin {
            background: transparent !important;
            border-top: 1px solid var(--border-color) !important;
            padding: 16px 0 !important;
            font-size: 12px !important;
            color: var(--text-secondary) !important;
        }

        /* =============================================
           SCROLLBAR
        ============================================= */
        .sidenav::-webkit-scrollbar {
            width: 4px;
        }

        .sidenav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidenav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.12);
            border-radius: 4px;
        }

        /* =============================================
           DIVIDER
        ============================================= */
        hr {
            border-color: var(--border-color) !important;
            opacity: 1 !important;
        }

        /* =============================================
           UTILITY
        ============================================= */
        .fw-600 { font-weight: 600; }
        .text-primary-custom { color: var(--primary) !important; }
    </style>
</head>

<body class="nav-fixed">
    @include('partials.header')
    <div id="layoutSidenav_content">

        @yield('content')

        @include('partials.footer')
    </div>
    </div>
    @include('partials.script')
    @yield('script')
</body>

</html>
