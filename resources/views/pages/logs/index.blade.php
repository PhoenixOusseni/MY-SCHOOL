@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-clipboard-list"></i></div>
                            Logs Système
                        </h1>
                        <p class="page-header-subtitle text-white-50">Historique des actions utilisateurs</p>
                    </div>
                    <div class="col-auto mt-4">
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#purgeModal">
                            <i class="fas fa-trash me-1"></i>&nbsp; Purger les logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-start-5 border-start-primary shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total logs</p>
                            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                        </div>
                        <div class="bg-primary rounded p-3"><i class="fas fa-database text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-start-5 border-start-success shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Aujourd'hui</p>
                            <h3 class="mb-0 text-success">{{ $stats['aujourd_hui'] }}</h3>
                        </div>
                        <div class="bg-success rounded p-3"><i class="fas fa-calendar-day text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-start-5 border-start-info shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Cette semaine</p>
                            <h3 class="mb-0 text-info">{{ $stats['cette_semaine'] }}</h3>
                        </div>
                        <div class="bg-info rounded p-3"><i class="fas fa-calendar-week text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-start-5 border-start-warning shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Connexions</p>
                            <h3 class="mb-0 text-warning">{{ $stats['connexions'] }}</h3>
                        </div>
                        <div class="bg-warning rounded p-3"><i class="fas fa-sign-in-alt text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('gestion_logs.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Module</label>
                        <select name="module" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($modules as $m)
                                <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Action</label>
                        <select name="action" class="form-select form-select-sm">
                            <option value="">Toutes</option>
                            @foreach ($actions as $a)
                                <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Utilisateur</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->prenom }} {{ $u->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Du</label>
                        <input type="date" name="date_debut" class="form-control form-control-sm"
                            value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Au</label>
                        <input type="date" name="date_fin" class="form-control form-control-sm"
                            value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-1 flex-fill">
                            <i class="fas fa-filter me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('gestion_logs.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Journal des événements</h6>
                <span class="badge bg-secondary">{{ $logs->total() }} entrée(s)</span>
            </div>
            <div class="card-body p-0">
                @if ($logs->isEmpty())
                    <div class="text-center text-muted p-5">
                        <i class="fas fa-clipboard-list fa-3x opacity-25 d-block mb-3"></i>
                        Aucun log trouvé.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-bordered table-striped m-3">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:160px">Date / Heure</th>
                                    <th style="width:120px">Utilisateur</th>
                                    <th style="width:110px">Action</th>
                                    <th style="width:130px">Module</th>
                                    <th>Description</th>
                                    <th style="width:130px">IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    @php
                                        $actionColors = [
                                            'connexion'    => 'success',
                                            'déconnexion'  => 'secondary',
                                            'création'     => 'primary',
                                            'modification' => 'warning',
                                            'suppression'  => 'danger',
                                            'purge'        => 'danger',
                                        ];
                                        $color = $actionColors[$log->action] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td class="small text-muted text-nowrap">
                                            <i class="fas fa-clock me-1 opacity-50"></i>
                                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="small">
                                            @if ($log->user)
                                                <a href="{{ route('gestion_utilisateurs.show', $log->user->id) }}"
                                                    class="text-decoration-none fw-semibold">
                                                    {{ $log->user->prenom }} {{ $log->user->nom }}
                                                </a>
                                                <small class="d-block text-muted">{{ $log->user->login }}</small>
                                            @else
                                                <span class="text-muted fst-italic">Système</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $color }}">{{ $log->action }}</span>
                                        </td>
                                        <td class="small">{{ $log->module }}</td>
                                        <td class="small text-muted">{{ $log->description }}</td>
                                        <td class="small text-muted text-nowrap">{{ $log->ip_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Purge -->
    <div class="modal fade" id="purgeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Purger les logs</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('gestion_logs.clear') }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Supprimer les logs antérieurs au :</label>
                            <input type="date" name="avant_date" class="form-control"
                                value="{{ now()->subMonth()->format('Y-m-d') }}">
                            <small class="text-muted">Laisser vide pour supprimer TOUS les logs.</small>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention</strong> — Cette action est irréversible.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Purger
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
