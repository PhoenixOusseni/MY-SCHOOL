@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="folder"></i></div>
                            Dossiers des Élèves
                        </h1>
                        <p class="text-white-75 mb-0">Accédez au dossier complet de chaque élève</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_eleves.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="users"></i>&thinsp; Liste des élèves
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10 mb-5">

        <!-- Filtres -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('dossiers_eleves.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold small text-muted">Recherche</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-feather="search" style="width:16px;height:16px;"></i></span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Nom, prénom, matricule…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold small text-muted">Classe</label>
                            <select name="classe_id" class="form-select">
                                <option value="">Toutes les classes</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold small text-muted">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous</option>
                                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                <option value="diplome" {{ request('statut') == 'diplome' ? 'selected' : '' }}>Diplômé</option>
                                <option value="abandonne" {{ request('statut') == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                            </select>
                        </div>
                        <div class="col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i data-feather="filter" style="width:14px;height:14px;"></i>&thinsp; Filtrer
                            </button>
                            <a href="{{ route('dossiers_eleves.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i data-feather="x" style="width:14px;height:14px;"></i>&thinsp; Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résultats -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i data-feather="list" style="width:16px;height:16px;" class="me-1"></i>
                    {{ $eleves->count() }} dossier(s) trouvé(s)
                </span>
            </div>
            <div class="card-body p-0">
                @if ($eleves->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i data-feather="folder-minus" style="width:48px;height:48px;opacity:.3;"></i>
                        <p class="mt-3">Aucun élève ne correspond aux critères de recherche.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Élève</th>
                                    <th>Classe actuelle</th>
                                    <th>Statut</th>
                                    <th>Absences</th>
                                    <th>Paiements</th>
                                    <th class="text-center">Dossier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eleves as $index => $eleve)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-dark">{{ $eleve->registration_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle-sm bg-primary text-white">
                                                    {{ strtoupper(substr($eleve->prenom, 0, 1)) }}{{ strtoupper(substr($eleve->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
                                                    <small class="text-muted">{{ $eleve->user->email ?? '—' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php $lastInscription = $eleve->inscriptions->sortByDesc('created_at')->first(); @endphp
                                            @if ($lastInscription)
                                                <span class="badge bg-info text-dark">{{ $lastInscription->classe->nom ?? '—' }}</span>
                                                <br><small class="text-muted">{{ $lastInscription->anneeScolaire->libelle ?? '' }}</small>
                                            @else
                                                <span class="text-muted small">Non inscrit</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($eleve->statut) {
                                                    'actif'     => 'success',
                                                    'suspendu'  => 'warning',
                                                    'diplome'   => 'primary',
                                                    'abandonne' => 'danger',
                                                    default     => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($eleve->statut ?? 'N/A') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="{{ $eleve->absences->count() > 5 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                                {{ $eleve->absences->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php $nbPaie = $eleve->paiements->count(); @endphp
                                            <span class="{{ $nbPaie == 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                                {{ $nbPaie }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('dossiers_eleves.show', $eleve->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="Ouvrir le dossier">
                                                <i data-feather="folder-open" style="width:14px;height:14px;"></i>&thinsp; Ouvrir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('script')
<style>
    .avatar-circle-sm {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
        flex-shrink: 0;
    }
</style>
<script>
    $(document).ready(function () {
        if (typeof feather !== 'undefined') feather.replace();
    });
</script>
@endsection
