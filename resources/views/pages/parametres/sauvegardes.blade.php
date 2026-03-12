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
                        <div class="page-header-icon"><i class="fas fa-database"></i></div>
                        Sauvegarde &amp; Restauration
                    </h1>
                    <div class="page-header-subtitle text-white-50">Gérer les sauvegardes de la base de données</div>
                </div>
                <div class="col-auto mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-white-50" href="#">Accueil</a></li>
                            <li class="breadcrumb-item"><a class="text-white-50" href="#">Paramètres</a></li>
                            <li class="breadcrumb-item active text-white">Sauvegardes</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-n10">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Navigation onglets verticaux -->
        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <div class="card-header bg-white fw-bold text-muted small text-uppercase py-3">
                    <i class="fas fa-sliders-h me-2 text-primary"></i>Paramètres
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('parametres.configuration') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="fas fa-building me-2 text-secondary"></i> Configuration générale
                    </a>
                    <a href="{{ route('parametres.notifications') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="fas fa-bell me-2 text-warning"></i> Notifications
                    </a>
                    <a href="{{ route('parametres.sauvegardes') }}"
                       class="list-group-item list-group-item-action active d-flex align-items-center py-3">
                        <i class="fas fa-database me-2"></i> Sauvegarde &amp; Restauration
                    </a>
                </div>
            </div>

            <!-- Info BD -->
            <div class="card shadow">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="fas fa-info-circle text-info me-2"></i>Informations BD
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Base de données</small>
                        <div class="fw-semibold">{{ $dbName }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Taille</small>
                        <div class="fw-semibold">{{ $dbSize }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Sauvegardes</small>
                        <div class="fw-semibold">{{ count($fichiers) }} fichier(s)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-lg-9">
            <!-- Actions -->
            <div class="row g-3 mb-4">
                <!-- Créer sauvegarde -->
                <div class="col-md-6">
                    <div class="card shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded p-3 me-3">
                                    <i class="fas fa-plus-circle text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Nouvelle sauvegarde</h6>
                                    <small class="text-muted">Exporter la base de données actuelle</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3 flex-grow-1">
                                Génère un fichier <code>.sql</code> complet de toutes les tables et données.
                                Le fichier est stocké dans <code>storage/app/backups/</code>.
                            </p>
                            <form method="POST" action="{{ route('parametres.creer_sauvegarde') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100"
                                        onclick="return confirm('Créer une nouvelle sauvegarde ?')">
                                    <i class="fas fa-download me-2"></i>Créer une sauvegarde
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Restaurer -->
                <div class="col-md-6">
                    <div class="card shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning rounded p-3 me-3">
                                    <i class="fas fa-upload text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Restaurer depuis un fichier</h6>
                                    <small class="text-muted">Importer un fichier SQL existant</small>
                                </div>
                            </div>
                            <div class="alert alert-warning py-2 small mb-3">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Attention :</strong> Cette opération remplace toutes les données actuelles.
                            </div>
                            <form method="POST" action="{{ route('parametres.restaurer') }}"
                                  enctype="multipart/form-data"
                                  onsubmit="return confirm('Cette opération va écraser les données actuelles. Confirmer ?')">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="fichier_sql" class="form-control form-control-sm @error('fichier_sql') is-invalid @enderror"
                                           accept=".sql,.txt">
                                    @error('fichier_sql')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm w-100">
                                    <i class="fas fa-redo me-2"></i>Restaurer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des sauvegardes -->
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-history text-info me-2"></i>Historique des sauvegardes</span>
                    <span class="badge bg-secondary">{{ count($fichiers) }}</span>
                </div>
                <div class="card-body p-0">
                    @if(count($fichiers) === 0)
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                            <p>Aucune sauvegarde disponible.</p>
                            <p class="small">Créez votre première sauvegarde ci-dessus.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Fichier</th>
                                        <th>Taille</th>
                                        <th>Date</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fichiers as $fichier)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-file-code text-info fa-lg"></i>
                                                    <span class="fw-semibold small font-monospace">{{ $fichier['nom'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $fichier['taille'] }}</span>
                                            </td>
                                            <td class="text-muted small">{{ $fichier['date'] }}</td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{ route('parametres.telecharger', $fichier['nom']) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            title="Supprimer"
                                                            onclick="confirmerSuppression('{{ $fichier['nom'] }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <!-- Formulaire suppression caché -->
                                                <form id="delete-{{ md5($fichier['nom']) }}"
                                                      method="POST"
                                                      action="{{ route('parametres.supprimer', $fichier['nom']) }}"
                                                      class="d-none">
                                                    @csrf @method('DELETE')
                                                </form>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmerSuppression(filename) {
    if (confirm('Supprimer la sauvegarde « ' + filename + ' » ?')) {
        const formId = 'delete-' + md5(filename);
        // Utiliser un formulaire dynamique puisque md5 n'est pas dispo en JS natif
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("parametres/sauvegardes") }}/' + encodeURIComponent(filename);
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
