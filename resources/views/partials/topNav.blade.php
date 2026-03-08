<nav class="topnav navbar navbar-expand shadow" id="sidenavAccordion">

    {{-- Toggle sidebar --}}
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-0 me-lg-0" id="sidebarToggle">
        <i data-feather="menu" style="width:20px;height:20px;"></i>
    </button>

    {{-- Right side --}}
    <ul class="navbar-nav align-items-center ms-auto gap-1">

        {{-- Notifications --}}
        <li class="nav-item dropdown no-caret">
            <a class="btn-icon-topbar dropdown-toggle" href="javascript:void(0);" role="button"
                data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;color:#64748b;transition:all .2s;">
                <i data-feather="bell" style="width:18px;height:18px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow" style="min-width:300px;">
                <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-weight:600;font-size:13px;">Notifications</span>
                    <span class="badge rounded-pill" style="background:var(--primary);font-size:10px;">0</span>
                </div>
                <div class="text-center py-4" style="color:#94a3b8;">
                    <i data-feather="bell-off" style="width:28px;height:28px;margin-bottom:8px;opacity:0.4;display:block;margin-inline:auto;"></i>
                    <p class="mb-0" style="font-size:12px;">Aucune notification</p>
                </div>
            </div>
        </li>

        {{-- Divider --}}
        <li class="nav-item d-none d-md-block">
            <div style="width:1px;height:24px;background:#e2e8f0;margin:0 4px;"></div>
        </li>

        {{-- User dropdown --}}
        <li class="nav-item dropdown no-caret dropdown-user me-2">
            <a class="user-toggle dropdown-toggle" id="navbarDropdownUser"
                href="javascript:void(0);" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="text-decoration:none;border:none;background:none;padding:4px 8px;border-radius:10px;display:flex;align-items:center;gap:8px;cursor:pointer;transition:background .2s;">
                <div class="user-avatar-sm">
                    {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? '', 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="user-meta-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <div class="user-meta-role">{{ Auth::user()->role->libelle ?? 'Aucun rôle assigné' }}</div>
                </div>
                <i data-feather="chevron-down" class="d-none d-md-block" style="width:14px;height:14px;color:#94a3b8;margin-left:2px;"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownUser" style="min-width:220px;">
                {{-- User info header --}}
                <div class="dropdown-header-info">
                    <div class="user-avatar-lg">
                        {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13px;color:#0f172a;line-height:1.2;">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</div>
                        <div style="font-size:11px;color:#64748b;line-height:1.2;margin-top:2px;">{{ Auth::user()->role->libelle ?? 'Aucun rôle assigné' }}</div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile', ['id' => Auth::user()->id]) }}">
                    <i data-feather="user" style="width:14px;height:14px;"></i>
                    Mon profil
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item text-danger" href="#"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i data-feather="log-out" style="width:14px;height:14px;"></i>
                        Se déconnecter
                    </a>
                </form>
            </div>
        </li>

    </ul>
</nav>
