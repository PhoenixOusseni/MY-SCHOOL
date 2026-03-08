@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="zap" class="text-white"></i></div>
                            Génération automatique des bulletins
                        </h1>
                        <p class="text-white">Générez les bulletins de toute une classe à partir de leurs notes d'évaluations</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_bulletins.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="list"></i>&nbsp; Liste des bulletins
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            {{-- Formulaire principal --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i data-feather="settings" class="me-2"></i> Paramètres de génération
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gestion_bulletins.generate') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Classe <span class="text-danger">*</span></label>
                                    <select name="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                        <option value="">— Sélectionner une classe —</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" @selected(old('classe_id') == $classe->id)>
                                                {{ $classe->nom }}
                                                @if($classe->niveau)
                                                    ({{ $classe->niveau->nom }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('classe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Période d'évaluation <span class="text-danger">*</span></label>
                                    <select name="period_evaluation_id" class="form-select @error('period_evaluation_id') is-invalid @enderror" required>
                                        <option value="">— Sélectionner une période —</option>
                                        @foreach($periodEvaluations as $period)
                                            <option value="{{ $period->id }}" @selected(old('period_evaluation_id') == $period->id)>
                                                {{ $period->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('period_evaluation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Statut des bulletins générés <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="brouillon" @selected(old('status', 'brouillon') === 'brouillon')>Brouillon</option>
                                        <option value="publie" @selected(old('status') === 'publie')>Publié</option>
                                        <option value="envoye" @selected(old('status') === 'envoye')>Envoyé</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="overwrite" id="overwrite" value="1"
                                            {{ old('overwrite') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="overwrite">
                                            Écraser les bulletins existants
                                            <small class="text-muted d-block">Si décoché, les bulletins déjà créés pour cette période seront ignorés.</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1 btn-sm">
                                    <i data-feather="zap"></i>&nbsp; Générer les bulletins
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Panneau d'information --}}
            <div class="col-lg-4">
                <div class="card mb-4 border-start border-primary border-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i data-feather="info" class="me-1"></i> Comment ça fonctionne ?</h6>
                        <ol class="ps-3 small text-muted mb-0">
                            <li class="mb-2">Sélectionnez la <strong>classe</strong> et la <strong>période</strong> d'évaluation.</li>
                            <li class="mb-2">Le système récupère toutes les <strong>notes</strong> saisies pour chaque matière.</li>
                            <li class="mb-2">Il calcule la <strong>moyenne par matière</strong> (notes ramenées sur 20, pondérées par les coefficients d'évaluation).</li>
                            <li class="mb-2">Puis la <strong>moyenne globale</strong> en appliquant les coefficients de matière définis par niveau.</li>
                            <li class="mb-2">Les <strong>rangs</strong> sont attribués automatiquement (classe et par matière).</li>
                            <li>Un bulletin est créé (ou mis à jour) pour chaque élève ayant au moins une note.</li>
                        </ol>
                    </div>
                </div>

                <div class="card mb-4 border-start border-warning border-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i data-feather="alert-triangle" class="me-1 text-warning"></i> Pré-requis</h6>
                        <ul class="ps-3 small text-muted mb-0">
                            <li class="mb-1">Les élèves doivent être <strong>inscrits</strong> dans la classe.</li>
                            <li class="mb-1">Les matières doivent avoir un <strong>coefficient défini</strong> pour le niveau de la classe (<em>Matières &gt; Niveaux</em>).</li>
                            <li class="mb-1">Des <strong>évaluations</strong> doivent exister pour cette période.</li>
                            <li>Des <strong>notes</strong> doivent avoir été saisies pour les élèves.</li>
                        </ul>
                    </div>
                </div>

                <div class="card border-start border-success border-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i data-feather="check-circle" class="me-1 text-success"></i> Appréciations automatiques</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tbody>
                                <tr><td><span class="badge bg-success">Excellent</span></td><td class="text-muted">≥ 18 / 20</td></tr>
                                <tr><td><span class="badge bg-primary">Très Bien</span></td><td class="text-muted">≥ 16 / 20</td></tr>
                                <tr><td><span class="badge bg-info text-dark">Bien</span></td><td class="text-muted">≥ 14 / 20</td></tr>
                                <tr><td><span class="badge bg-secondary">Assez Bien</span></td><td class="text-muted">≥ 12 / 20</td></tr>
                                <tr><td><span class="badge bg-warning text-dark">Passable</span></td><td class="text-muted">≥ 10 / 20</td></tr>
                                <tr><td><span class="badge bg-danger">Insuffisant</span></td><td class="text-muted">&lt; 10 / 20</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
