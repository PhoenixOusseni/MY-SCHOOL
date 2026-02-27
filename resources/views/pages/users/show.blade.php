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
                            <div class="page-header-icon"><i class="fas fa-user"></i></div>
                            {{ $user->prenom }} {{ strtoupper($user->nom) }}
                        </h1>
                        <p class="page-header-subtitle text-white-50">
                            <code class="text-white-50">{{ $user->login }}</code>
                            &nbsp;·&nbsp;
                            {{ $user->role ? $user->role->libelle : 'Aucun rôle' }}
                        </p>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('gestion_utilisateurs.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_utilisateurs.edit', $user->id) }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-edit me-1"></i>&nbsp; Modifier
                        </a>
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
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Détails -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center py-4">
                        <div class="rounded-circle bg-{{ $user->actif ? 'primary' : 'secondary' }} text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width:80px;height:80px;font-size:28px;font-weight:700;">
                            {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                        </div>
                        <h5 class="mb-1">{{ $user->prenom }} {{ strtoupper($user->nom) }}</h5>
                        <code class="text-muted">{{ $user->login }}</code>
                        <div class="mt-2">
                            @if ($user->actif)
                                <span class="badge bg-success px-3 py-2"><i class="fas fa-circle me-1" style="font-size:8px"></i>Actif</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2"><i class="fas fa-circle me-1" style="font-size:8px"></i>Inactif</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-light p-0">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted small ps-3">Email</td>
                                <td class="small pe-3">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small ps-3">Téléphone</td>
                                <td class="small pe-3">{{ $user->telephone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small ps-3">Rôle</td>
                                <td class="small pe-3">
                                    @if ($user->role)
                                        <a href="{{ route('gestion_roles.show', $user->role->id) }}" class="badge bg-info text-dark text-decoration-none">
                                            {{ $user->role->libelle }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small ps-3">Créé le</td>
                                <td class="small pe-3">{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light"><h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6></div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('gestion_utilisateurs.edit', $user->id) }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-edit me-2"></i>&nbsp; Modifier les informations
                        </a>
                        @if ($user->id !== Auth::id())
                            <form action="{{ route('gestion_utilisateurs.toggle', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 btn-{{ $user->actif ? 'secondary' : 'success' }}">
                                    <i class="fas fa-{{ $user->actif ? 'ban' : 'check-circle' }} me-2"></i>
                                    {{ $user->actif ? 'Désactiver le compte' : 'Activer le compte' }}
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>&nbsp; Supprimer l'utilisateur
                            </button>
                        @endif
                    </div>
                </div>

                @if ($user->notes)
                    <div class="card border-0 shadow-sm mt-3 border-start-5 border-start-warning">
                        <div class="card-body">
                            <h6 class="mb-2"><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                            <p class="small text-muted mb-0">{{ $user->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Permissions & Logs -->
            <div class="col-lg-8">
                @if ($user->role && $user->role->permissions->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Permissions (via rôle « {{ $user->role->libelle }} »)</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($user->role->permissions->groupBy('module') as $module => $perms)
                                <div class="mb-3">
                                    <p class="text-muted small fw-semibold mb-2 text-uppercase">{{ $module }}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($perms as $perm)
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ $perm->label }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif ($user->role)
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Le rôle <strong>{{ $user->role->libelle }}</strong> n'a aucune permission assignée.
                        <a href="{{ route('gestion_roles.edit', $user->role->id) }}" class="alert-link">Configurer les permissions</a>
                    </div>
                @else
                    <div class="alert alert-secondary mb-4">
                        <i class="fas fa-info-circle me-2"></i>Aucun rôle assigné — l'utilisateur n'a aucune permission spécifique.
                    </div>
                @endif

                <!-- Activité récente -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Activité récente</h6>
                        <span class="badge bg-secondary">20 dernières actions</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($logs->isEmpty())
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-history fa-2x opacity-25 d-block mb-2"></i>Aucune activité enregistrée.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered table-striped m-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Action</th>
                                            <th>Module</th>
                                            <th>Description</th>
                                            <th>IP</th>
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
                                                <td class="small text-muted text-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                                <td><span class="badge bg-{{ $color }}">{{ $log->action }}</span></td>
                                                <td class="small">{{ $log->module }}</td>
                                                <td class="small text-muted">{{ substr($log->description ?? '', 0, 60) }}{{ strlen($log->description ?? '') > 60 ? '…' : '' }}</td>
                                                <td class="small text-muted">{{ $log->ip_address }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal suppression -->
    @if ($user->id !== Auth::id())
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer le compte de <strong>{{ $user->prenom }} {{ $user->nom }}</strong> ?</p>
                        <p class="text-danger small mb-0"><i class="fas fa-exclamation-circle me-1"></i>Cette action est irréversible.</p>
                    </div>
                    <div class="m-3">
                        <form action="{{ route('gestion_utilisateurs.destroy', $user->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger"><i class="fas fa-trash me-1"></i>
                                &nbsp; Confirmer la suppression
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
