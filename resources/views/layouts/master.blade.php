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
           PAGE HEADER — PROFESSIONAL DESIGN
        ============================================= */
        .page-header {
            background:
                linear-gradient(135deg, #9b1530 0%, var(--primary) 40%, #b8263d 70%, var(--primary-dark) 100%) !important;
            padding: 20px 0 104px !important;
            position: relative;
            overflow: hidden;
            border-bottom: none !important;
        }

        /* Noise texture overlay */
        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(ellipse at 15% 60%, rgba(255,255,255,0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 15%, rgba(255,180,180,0.08) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Decorative large circle - top right */
        .page-header::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Inner elements above pseudo-elements */
        .page-header .container-xl,
        .page-header .container-fluid {
            position: relative;
            z-index: 1;
        }

        /* Content vertical spacing */
        .page-header .page-header-content {
            padding-top: 0.75rem !important;
            padding-bottom: 1rem !important;
        }

        /* Left separator line accent */
        .page-header .page-header-content .row {
            position: relative;
        }

        /* Title */
        .page-header-title {
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            color: #fff !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            letter-spacing: -0.02em !important;
            line-height: 1.25 !important;
            margin-bottom: 0 !important;
        }

        /* Icon container */
        .page-header-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.18);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.2);
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .page-header-icon:hover {
            transform: scale(1.05);
        }

        .page-header-icon svg,
        .page-header-icon .feather,
        .page-header-icon i {
            width: 20px !important;
            height: 20px !important;
            color: #fff !important;
            font-size: 18px;
        }

        /* Subtitle / description */
        .page-header .page-header-subtitle,
        .page-header p.text-muted,
        .page-header p.mb-0,
        .page-header p.mt-2 {
            color: rgba(255,255,255,0.72) !important;
            font-size: 13px !important;
            font-weight: 400 !important;
            margin-top: 5px !important;
            line-height: 1.5 !important;
        }

        /* Action buttons in header */
        .page-header .btn {
            font-size: 13px !important;
            font-weight: 500 !important;
            padding: 0.45rem 1rem !important;
            border-radius: 9px !important;
            transition: all 0.18s ease !important;
            letter-spacing: 0 !important;
        }

        .page-header .btn-light {
            background: rgba(255,255,255,0.95) !important;
            color: var(--primary-dark) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12) !important;
        }

        .page-header .btn-light:hover {
            background: #fff !important;
            color: var(--primary) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(0,0,0,0.18) !important;
        }

        .page-header .btn-dark {
            background: rgba(0,0,0,0.28) !important;
            color: rgba(255,255,255,0.92) !important;
            border: 1px solid rgba(255,255,255,0.18) !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
        }

        .page-header .btn-dark:hover {
            background: rgba(0,0,0,0.45) !important;
            color: #fff !important;
            transform: translateY(-2px) !important;
            border-color: rgba(255,255,255,0.3) !important;
        }

        .page-header .btn-outline-light,
        .page-header .btn-outline-white {
            color: rgba(255,255,255,0.9) !important;
            border-color: rgba(255,255,255,0.4) !important;
        }

        .page-header .btn-outline-light:hover,
        .page-header .btn-outline-white:hover {
            background: rgba(255,255,255,0.15) !important;
            color: #fff !important;
            border-color: rgba(255,255,255,0.6) !important;
            transform: translateY(-2px) !important;
        }

        /* Badges / tags in header (like annee-badge) */
        .page-header .annee-badge,
        .page-header .badge {
            background: rgba(255,255,255,0.15) !important;
            color: rgba(255,255,255,0.9) !important;
            border: 1px solid rgba(255,255,255,0.22) !important;
            border-radius: 20px !important;
            padding: 4px 10px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            backdrop-filter: blur(4px);
        }

        /* Breadcrumb inside header */
        .page-header .breadcrumb {
            background: rgba(0,0,0,0.12) !important;
            border-radius: 8px !important;
            padding: 6px 14px !important;
            margin-bottom: 0 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }

        .page-header .breadcrumb-item,
        .page-header .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.55) !important;
            font-size: 12px !important;
        }

        .page-header .breadcrumb-item a {
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none !important;
        }

        .page-header .breadcrumb-item a:hover {
            color: #fff !important;
        }

        .page-header .breadcrumb-item.active {
            color: rgba(255,255,255,0.55) !important;
        }

        /* Decorative bottom shimmer line */
        .page-header-dark {
            border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        }

        /* Left vertical accent bar on title group */
        .page-header .page-header-title::before {
            content: none;
        }

        /* Decorative dot grid — top left quadrant */
        .page-header .container-xl::before,
        .page-header .container-fluid::before {
            content: '';
            position: absolute;
            top: 0;
            left: -20px;
            width: 160px;
            height: 80px;
            background-image: radial-gradient(circle, rgba(255,255,255,0.18) 1px, transparent 1px);
            background-size: 16px 16px;
            pointer-events: none;
            opacity: 0.4;
        }

        /* Small decorative circle — bottom right */
        .page-header .page-header-content::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,0.04);
            pointer-events: none;
        }

        /* Subtle inner glow on the left */
        .page-header .col-auto:first-child {
            position: relative;
        }

        /* Form controls inside header (stats pages filters) */
        .page-header .form-select,
        .page-header .form-control {
            background: rgba(255,255,255,0.15) !important;
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.28) !important;
            border-radius: 9px !important;
            font-size: 13px !important;
            padding: 0.4rem 2rem 0.4rem 0.85rem !important;
            backdrop-filter: blur(4px);
        }

        .page-header .form-select:focus,
        .page-header .form-control:focus {
            background: rgba(255,255,255,0.22) !important;
            border-color: rgba(255,255,255,0.5) !important;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.12) !important;
            color: #fff !important;
        }

        .page-header .form-select option {
            color: var(--text-primary) !important;
            background: #fff !important;
        }

        /* Input group inside header */
        .page-header .input-group-text {
            background: rgba(255,255,255,0.12) !important;
            border-color: rgba(255,255,255,0.25) !important;
            color: rgba(255,255,255,0.8) !important;
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
