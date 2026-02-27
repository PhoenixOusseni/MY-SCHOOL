@extends('layouts.master')
@include('partials.style')

@section('content')
    <!-- En-tête -->
    <div class="page-header page-header-dark header-gradient pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title mb-0 text-white">
                            <div class="page-header-icon"><i class="fas fa-cog"></i></div>
                            Configuration générale
                        </h1>
                        <div class="page-header-subtitle text-white-50">Paramètres principaux de l'application</div>
                    </div>
                    <div class="col-auto mt-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a class="text-white-50" href="#">Accueil</a></li>
                                <li class="breadcrumb-item"><a class="text-white-50" href="#">Paramètres</a></li>
                                <li class="breadcrumb-item active text-white">Configuration</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 mt-n10">
        <!-- Alertes -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Navigation onglets verticaux -->
            <div class="col-lg-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-white fw-bold text-muted small text-uppercase py-3">
                        <i class="fas fa-sliders-h me-2 text-primary"></i>Paramètres
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('parametres.configuration') }}"
                            class="list-group-item list-group-item-action active d-flex align-items-center py-3">
                            <i class="fas fa-building me-2"></i> Configuration générale
                        </a>
                        <a href="{{ route('parametres.notifications') }}"
                            class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <i class="fas fa-bell me-2 text-warning"></i> Notifications
                        </a>
                        <a href="{{ route('parametres.sauvegardes') }}"
                            class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <i class="fas fa-database me-2 text-info"></i> Sauvegarde &amp; Restauration
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="col-lg-9">
                <form method="POST" action="{{ route('parametres.save_configuration') }}" id="formConfig">
                    @csrf
                    <!-- Onglets -->
                    <ul class="nav nav-tabs nav-tabs-bordered mb-0" id="configTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general"
                                type="button">
                                <i class="fas fa-info-circle me-1"></i>Informations
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-securite" type="button">
                                <i class="fas fa-shield-alt me-1"></i>Sécurité & Affichage
                            </button>
                        </li>
                    </ul>

                    <div class="card shadow border-top-0 rounded-top-0">
                        <div class="card-body tab-content p-4">
                            <!-- Onglet Informations -->
                            <div class="tab-pane fade show active" id="tab-general">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nom de l'application <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="app_nom"
                                            class="form-control @error('app_nom') is-invalid @enderror"
                                            value="{{ old('app_nom', $params['app_nom']->valeur ?? 'School Manager') }}"
                                            required>
                                        @error('app_nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Sous-titre</label>
                                        <input type="text" name="app_soustitre" class="form-control"
                                            value="{{ old('app_soustitre', $params['app_soustitre']->valeur ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email de contact</label>
                                        <input type="email" name="app_email"
                                            class="form-control @error('app_email') is-invalid @enderror"
                                            value="{{ old('app_email', $params['app_email']->valeur ?? '') }}">
                                        @error('app_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Téléphone</label>
                                        <input type="text" name="app_telephone" class="form-control"
                                            value="{{ old('app_telephone', $params['app_telephone']->valeur ?? '') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Adresse</label>
                                        <textarea name="app_adresse" class="form-control" rows="2">{{ old('app_adresse', $params['app_adresse']->valeur ?? '') }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Devise</label>
                                        <input type="text" name="app_devise" class="form-control" maxlength="10"
                                            value="{{ old('app_devise', $params['app_devise']->valeur ?? 'XOF') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Langue</label>
                                        <select name="app_langue" class="form-select">
                                            @foreach ($langues as $code => $label)
                                                <option value="{{ $code }}"
                                                    {{ old('app_langue', $params['app_langue']->valeur ?? 'fr') === $code ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Fuseau horaire</label>
                                        <select name="app_timezone" class="form-select">
                                            @foreach ($timezones as $tz => $label)
                                                <option value="{{ $tz }}"
                                                    {{ old('app_timezone', $params['app_timezone']->valeur ?? 'Africa/Abidjan') === $tz ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Sécurité & Affichage -->
                            <div class="tab-pane fade" id="tab-securite">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Couleur principale</label>
                                        <div class="input-group">
                                            <input type="color" name="app_couleur" id="colorPicker"
                                                class="form-control form-control-color"
                                                value="{{ old('app_couleur', $params['app_couleur']->valeur ?? '#2f663f') }}">
                                            <input type="text" id="colorText" class="form-control"
                                                value="{{ old('app_couleur', $params['app_couleur']->valeur ?? '#2f663f') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Éléments par page <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="app_pagination"
                                            class="form-control @error('app_pagination') is-invalid @enderror"
                                            min="5" max="200"
                                            value="{{ old('app_pagination', $params['app_pagination']->valeur ?? 20) }}">
                                        @error('app_pagination')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-check form-switch mb-2">
                                            <input type="hidden" name="app_maintenance" value="0">
                                            <input class="form-check-input" type="checkbox" name="app_maintenance"
                                                id="switchMaintenance" value="1"
                                                {{ old('app_maintenance', $params['app_maintenance']->valeur ?? '0') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="switchMaintenance">
                                                Mode maintenance
                                            </label>
                                            <div class="form-text">Affiche une page de maintenance pour les visiteurs.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="m-3">
                            <button type="submit" class="btn btn-1">
                                <i class="fas fa-save me-1"></i>&nbsp;Enregistrer
                            </button>
                            <a href="{{ route('parametres.configuration') }}" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i>&nbsp;Annuler
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const colorPicker = document.getElementById('colorPicker');
        const colorText = document.getElementById('colorText');
        if (colorPicker) {
            colorPicker.addEventListener('input', () => {
                colorText.value = colorPicker.value;
            });
        }
    </script>
@endpush
