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
                            <div class="page-header-icon"><i class="fas fa-bell"></i></div>
                            Notifications
                        </h1>
                        <div class="page-header-subtitle text-white-50">Gérer les alertes et notifications automatiques</div>
                    </div>
                    <div class="col-auto mt-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a class="text-white-50" href="#">Accueil</a></li>
                                <li class="breadcrumb-item"><a class="text-white-50" href="#">Paramètres</a></li>
                                <li class="breadcrumb-item active text-white">Notifications</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 mt-n10">
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
                            class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <i class="fas fa-building me-2 text-secondary"></i> Configuration générale
                        </a>
                        <a href="{{ route('parametres.notifications') }}"
                            class="list-group-item list-group-item-action active d-flex align-items-center py-3">
                            <i class="fas fa-bell me-2"></i> Notifications
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
                <form method="POST" action="{{ route('parametres.save_notifications') }}">
                    @csrf
                    <!-- Email -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <span class="fw-bold"><i class="fas fa-envelope text-primary me-2"></i>Notifications par
                                email</span>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="notif_email_actif" value="0">
                                <input class="form-check-input" type="checkbox" name="notif_email_actif" id="switchEmail"
                                    value="1"
                                    {{ ($params['notif_email_actif']->valeur ?? '0') === '1' ? 'checked' : '' }}
                                    onchange="toggleSection('emailSection', this.checked)">
                                <label class="form-check-label" for="switchEmail">Activé</label>
                            </div>
                        </div>
                        <div class="card-body" id="emailSection"
                            style="{{ ($params['notif_email_actif']->valeur ?? '0') === '0' ? 'opacity:.5;pointer-events:none' : '' }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email expéditeur</label>
                                    <input type="email" name="notif_email_expediteur"
                                        class="form-control @error('notif_email_expediteur') is-invalid @enderror"
                                        value="{{ old('notif_email_expediteur', $params['notif_email_expediteur']->valeur ?? '') }}">
                                    @error('notif_email_expediteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nom expéditeur</label>
                                    <input type="text" name="notif_email_nom" class="form-control"
                                        value="{{ old('notif_email_nom', $params['notif_email_nom']->valeur ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Événements -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <span class="fw-bold"><i class="fas fa-calendar-check text-success me-2"></i>Événements
                                notifiés</span>
                        </div>
                        <div class="list-group list-group-flush">
                            <!-- Paiement en retard -->
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div>
                                    <div class="fw-semibold">Paiement en retard</div>
                                    <div class="text-muted small">Envoyer une alerte en cas de retard de paiement de frais
                                        scolaires</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="notif_paiement_retard" value="0">
                                    <input class="form-check-input" type="checkbox" name="notif_paiement_retard"
                                        id="switchPaiement" value="1"
                                        {{ ($params['notif_paiement_retard']->valeur ?? '0') === '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <!-- Absences -->
                            <div class="list-group-item py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold">Seuil d'absences atteint</div>
                                        <div class="text-muted small">Alerte lorsqu'un élève dépasse le seuil d'absences
                                            configuré</div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="notif_absence_actif" value="0">
                                        <input class="form-check-input" type="checkbox" name="notif_absence_actif"
                                            id="switchAbsence" value="1"
                                            {{ ($params['notif_absence_actif']->valeur ?? '0') === '1' ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 text-muted small">Seuil (nb absences) :</label>
                                    <input type="number" name="notif_absence_seuil"
                                        class="form-control form-control-sm @error('notif_absence_seuil') is-invalid @enderror"
                                        style="width:80px" min="1" max="100"
                                        value="{{ old('notif_absence_seuil', $params['notif_absence_seuil']->valeur ?? 5) }}">
                                    @error('notif_absence_seuil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Nouvel utilisateur -->
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div>
                                    <div class="fw-semibold">Création d'un compte utilisateur</div>
                                    <div class="text-muted small">Notifier l'administrateur lors d'un nouvel utilisateur
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="notif_nouveau_utilisateur" value="0">
                                    <input class="form-check-input" type="checkbox" name="notif_nouveau_utilisateur"
                                        id="switchUser" value="1"
                                        {{ ($params['notif_nouveau_utilisateur']->valeur ?? '0') === '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <!-- Bulletin généré -->
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div>
                                    <div class="fw-semibold">Bulletin généré</div>
                                    <div class="text-muted small">Notifier les parents/tuteurs lors de la génération du
                                        bulletin</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="notif_bulletin_genere" value="0">
                                    <input class="form-check-input" type="checkbox" name="notif_bulletin_genere"
                                        id="switchBulletin" value="1"
                                        {{ ($params['notif_bulletin_genere']->valeur ?? '0') === '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-1">
                            <i class="fas fa-save me-1"></i>&nbsp;Enregistrer
                        </button>
                        <a href="{{ route('parametres.notifications') }}" class="btn btn-dark">
                            <i class="fas fa-times me-1"></i>&nbsp;Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleSection(sectionId, enabled) {
            const el = document.getElementById(sectionId);
            if (el) {
                el.style.opacity = enabled ? '1' : '0.5';
                el.style.pointerEvents = enabled ? '' : 'none';
            }
        }
    </script>
@endpush
