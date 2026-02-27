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
                            Rôles & Permissions
                        </h1>
                        <p class="page-header-subtitle text-white-50">Gérer les profils d'accès au système</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_roles.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Nouveau rôle
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
            @forelse ($roles as $role)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">
                                        <i class="fas fa-shield-alt me-2 text-primary"></i>{{ $role->libelle }}
                                    </h5>
                                    @if($role->description)
                                        <p class="text-muted small mb-0">{{ $role->description }}</p>
                                    @endif
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $role->users_count }} user(s)</span>
                            </div>

                            <!-- Aperçu permissions -->
                            <div class="mb-3">
                                @if ($role->permissions->isEmpty())
                                    <span class="text-muted small"><i class="fas fa-ban me-1"></i>Aucune permission</span>
                                @else
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($role->permissions->take(5) as $perm)
                                            <span class="badge bg-light text-dark border small">{{ $perm->label }}</span>
                                        @endforeach
                                        @if ($role->permissions->count() > 5)
                                            <span class="badge bg-secondary small">+{{ $role->permissions->count() - 5 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex gap-2">
                            <a href="{{ route('gestion_roles.show', $role->id) }}" class="btn btn-sm btn-1 flex-fill">
                                <i class="fas fa-eye me-1"></i>&nbsp; Voir
                            </a>
                            <a href="{{ route('gestion_roles.edit', $role->id) }}" class="btn btn-sm btn-dark flex-fill">
                                <i class="fas fa-edit me-1"></i>&nbsp; Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}">
                                <i class="fas fa-trash"></i>&nbsp; Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Modal suppression -->
                    <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h6 class="modal-title"><i class="fas fa-trash me-2"></i>Supprimer</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Supprimer le rôle <strong>{{ $role->libelle }}</strong> ?
                                    @if ($role->users_count > 0)
                                        <div class="alert alert-warning mt-2 mb-0 small p-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $role->users_count }} utilisateur(s) ont ce rôle.
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="{{ route('gestion_roles.destroy', $role->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center text-muted p-5">
                            <i class="fas fa-shield-alt fa-3x opacity-25 d-block mb-3"></i>
                            Aucun rôle créé.
                            <a href="{{ route('gestion_roles.create') }}" class="d-block mt-2">Créer le premier rôle</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
