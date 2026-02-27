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
                            <div class="page-header-icon"><i class="fas fa-shield-alt"></i></div>
                            {{ $role->libelle }}
                        </h1>
                        <p class="page-header-subtitle text-white-50">
                            {{ $role->permissions->count() }} permission(s) · {{ $role->users->count() }} utilisateur(s)
                        </p>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('gestion_roles.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_roles.edit', $role->id) }}" class="btn btn-dark btn-sm">
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

        <div class="row">
            <!-- Permissions -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <h6 class="mb-0"><i class="fas fa-key me-2"></i>Permissions assignées</h6>
                        <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if ($role->permissions->isEmpty())
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-key fa-2x opacity-25 d-block mb-2"></i>
                                Aucune permission assignée à ce rôle.
                                <a href="{{ route('gestion_roles.edit', $role->id) }}" class="d-block mt-1">Configurer maintenant</a>
                            </div>
                        @else
                            @foreach ($role->permissions->groupBy('module') as $module => $perms)
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted small fw-bold mb-2 border-bottom pb-1">
                                        <i class="fas fa-folder me-1"></i>{{ $module }}
                                        <span class="badge bg-secondary ms-1">{{ $perms->count() }}</span>
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($perms as $perm)
                                            <div class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check me-1"></i>{{ $perm->label }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Utilisateurs & Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Utilisateurs ({{ $role->users->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($role->users->isEmpty())
                            <p class="text-muted small text-center p-3 mb-0">Aucun utilisateur avec ce rôle.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($role->users->take(8) as $user)
                                    <li class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                            style="width:30px;height:30px;font-size:12px;font-weight:600;flex-shrink:0;">
                                            {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('gestion_utilisateurs.show', $user->id) }}" class="text-decoration-none small fw-semibold">
                                            {{ $user->prenom }} {{ strtoupper($user->nom) }}
                                        </a>
                                    </li>
                                @endforeach
                                @if ($role->users->count() > 8)
                                    <li class="list-group-item text-center text-muted small py-2">
                                        + {{ $role->users->count() - 8 }} autres
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light"><h6 class="mb-0">Actions</h6></div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('gestion_roles.edit', $role->id) }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-edit me-2"></i>&nbsp; Modifier les permissions
                        </a>
                        @if ($role->users->isEmpty())
                            <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>&nbsp; Supprimer ce rôle
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Supprimer le rôle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Supprimer le rôle <strong>{{ $role->libelle }}</strong> ? Cette action est irréversible.
                </div>
                <div class="m-3">
                    <form action="{{ route('gestion_roles.destroy', $role->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">
                            <i class="fas fa-check me-1"></i>&nbsp; Confirmer la suppression
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

