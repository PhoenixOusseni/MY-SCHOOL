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
                            <div class="page-header-icon"><i class="fas fa-users"></i></div>
                            Gestion des Utilisateurs
                        </h1>
                        <p class="page-header-subtitle text-white-50">Comptes d'accès au système</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_utilisateurs.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Nouvel utilisateur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-start-5 border-start-primary shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total utilisateurs</p>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary rounded p-3"><i class="fas fa-users text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-5 border-start-success shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Comptes actifs</p>
                            <h3 class="mb-0 text-success">{{ $stats['actifs'] }}</h3>
                        </div>
                        <div class="bg-success rounded p-3"><i class="fas fa-user-check text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-5 border-start-secondary shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Comptes inactifs</p>
                            <h3 class="mb-0 text-secondary">{{ $stats['inactifs'] }}</h3>
                        </div>
                        <div class="bg-secondary rounded p-3"><i class="fas fa-user-slash text-white fa-lg"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Liste des utilisateurs</h6>
                <span class="badge bg-secondary">{{ $users->total() }} utilisateur(s)</span>
            </div>
            <div class="card-body p-0">
                @if ($users->isEmpty())
                    <div class="text-center text-muted p-5">
                        <i class="fas fa-users fa-3x opacity-25 mb-3 d-block"></i>
                        Aucun utilisateur enregistré.
                        <a href="{{ route('gestion_utilisateurs.create') }}" class="d-block mt-2">Créer le premier</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom complet</th>
                                    <th>Login</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Rôle</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr class="{{ !$user->actif ? 'table-secondary opacity-75' : '' }}">
                                        <td class="text-muted small">{{ $users->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-{{ $user->actif ? 'primary' : 'secondary' }} text-white d-flex align-items-center justify-content-center"
                                                    style="width:36px;height:36px;font-size:14px;font-weight:600;flex-shrink:0;">
                                                    {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $user->prenom }} {{ strtoupper($user->nom) }}</div>
                                                    <small class="text-muted">Inscrit le {{ $user->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code>{{ $user->login }}</code></td>
                                        <td class="small">{{ $user->email }}</td>
                                        <td class="small">{{ $user->telephone ?? '—' }}</td>
                                        <td>
                                            @if ($user->role)
                                                <span class="badge bg-info text-dark">{{ $user->role->libelle }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($user->actif)
                                                <span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size:8px"></i>Actif</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-circle me-1" style="font-size:8px"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_utilisateurs.show', $user->id) }}"
                                                class="btn btn-sm btn-1 me-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('gestion_utilisateurs.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning me-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($user->id !== Auth::id())
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->id }}" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h6 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer</h6>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Supprimer <strong>{{ $user->prenom }} {{ $user->nom }}</strong> ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <form action="{{ route('gestion_utilisateurs.destroy', $user->id) }}" method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
