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
                            <div class="page-header-icon"><i class="fas fa-edit"></i></div>
                            Modifier le Rôle
                        </h1>
                        <p class="page-header-subtitle text-white-50">{{ $role->libelle }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_roles.show', $role->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i><strong>Erreurs :</strong>
                <ul class="mb-0 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $assignedIds = old('permissions', $role->permissions->pluck('id')->toArray());
        @endphp

        <form action="{{ route('gestion_roles.update', $role->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <!-- Infos du rôle -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Informations du rôle</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom du rôle <span class="text-danger">*</span></label>
                                <input type="text" name="libelle"
                                    class="form-control @error('libelle') is-invalid @enderror"
                                    value="{{ old('libelle', $role->libelle) }}" required>
                                @error('libelle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
                            </div>

                            <div class="bg-light rounded p-3 mb-4 small text-muted">
                                <i class="fas fa-users me-1"></i>
                                <strong>{{ $role->users->count() }}</strong> utilisateur(s) ont ce rôle.
                            </div>

                            <button type="submit" class="btn btn-1 btn-sm w-100">
                                <i class="fas fa-save me-1"></i>&nbsp; Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-key me-2"></i>Permissions
                                <span class="badge bg-primary ms-2" id="countBadge">
                                    {{ count($assignedIds) }}
                                </span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-dark" id="toggleAll">
                                <i class="fas fa-check-double me-1"></i>Tout sélectionner
                            </button>
                        </div>
                        <div class="card-body">
                            @forelse ($permissions as $module => $perms)
                                @php $moduleSlug = str_replace([' ', "'"], ['-', ''], strtolower($module)); @endphp
                                <div class="mb-4">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="text-uppercase text-muted small fw-bold mb-0">
                                            <i class="fas fa-folder me-1"></i>{{ $module }}
                                        </h6>
                                        <button type="button" class="btn btn-link text-muted p-0 small toggle-module"
                                            data-module="{{ $moduleSlug }}">
                                            Tout sélectionner
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        @foreach ($perms as $perm)
                                            <div class="col-md-6">
                                                <div class="form-check border rounded p-2 ps-4 h-100 {{ in_array($perm->id, $assignedIds) ? 'border-success' : '' }}" style="{{ in_array($perm->id, $assignedIds) ? 'background:#f0fff4' : '' }}">
                                                    <input class="form-check-input perm-check"
                                                        data-module="{{ $moduleSlug }}"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $perm->id }}"
                                                        id="perm_{{ $perm->id }}"
                                                        {{ in_array($perm->id, $assignedIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100" for="perm_{{ $perm->id }}">
                                                        <span class="fw-semibold">{{ $perm->label }}</span>
                                                        @if ($perm->description)
                                                            <small class="d-block text-muted">{{ $perm->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-key fa-2x opacity-25 d-block mb-2"></i>
                                    Aucune permission disponible.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateCount() {
            const count = document.querySelectorAll('.perm-check:checked').length;
            document.getElementById('countBadge').textContent = count;
        }

        document.querySelectorAll('.perm-check').forEach(c => c.addEventListener('change', updateCount));

        document.getElementById('toggleAll').addEventListener('click', function () {
            const all = document.querySelectorAll('.perm-check');
            const allChecked = [...all].every(c => c.checked);
            all.forEach(c => c.checked = !allChecked);
            this.innerHTML = allChecked
                ? '<i class="fas fa-check-double me-1"></i>Tout sélectionner'
                : '<i class="fas fa-times-circle me-1"></i>Tout désélectionner';
            updateCount();
        });

        document.querySelectorAll('.toggle-module').forEach(btn => {
            btn.addEventListener('click', function () {
                const module = this.dataset.module;
                const checks = document.querySelectorAll(`.perm-check[data-module="${module}"]`);
                const allChecked = [...checks].every(c => c.checked);
                checks.forEach(c => c.checked = !allChecked);
                this.textContent = allChecked ? 'Tout sélectionner' : 'Tout désélectionner';
                updateCount();
            });
        });
    </script>
@endsection
