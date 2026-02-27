@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="eye"></i></div>
                            Détails du bulletin
                        </h1>
                        <p class="text-muted">Informations générales et détails par matière</p>
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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i data-feather="check-circle" class="me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="file-text"></i> Informations du bulletin
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Élève</h6>
                                <p class="mb-0">{{ $bulletin->eleve->nom ?? 'N/A' }} {{ $bulletin->eleve->prenom ?? '' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Classe</h6>
                                <p class="mb-0">{{ $bulletin->classe->nom ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Période</h6>
                                <p class="mb-0">{{ $bulletin->periodEvaluation->libelle ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Moyenne</h6>
                                <p class="mb-0"><strong>{{ $bulletin->moyenne_globale ?? '-' }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Rang</h6>
                                <p class="mb-0">
                                    @if($bulletin->rang && $bulletin->total_eleves)
                                        {{ $bulletin->rang }}/{{ $bulletin->total_eleves }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Total points</h6>
                                <p class="mb-0">{{ $bulletin->total_points ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Coef. total</h6>
                                <p class="mb-0">{{ $bulletin->total_coefficient ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Absences</h6>
                                <p class="mb-0">{{ $bulletin->absences }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Absences justifiées</h6>
                                <p class="mb-0">{{ $bulletin->justification_absences }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Retards</h6>
                                <p class="mb-0">{{ $bulletin->retards }}</p>
                            </div>
                        </div>

                        @if($bulletin->commentaire_principal)
                            <hr>
                            <h6 class="text-muted mb-1">Commentaire principal</h6>
                            <p class="mb-0">{{ $bulletin->commentaire_principal }}</p>
                        @endif

                        @if($bulletin->commentaire_directeur)
                            <hr>
                            <h6 class="text-muted mb-1">Commentaire directeur</h6>
                            <p class="mb-0">{{ $bulletin->commentaire_directeur }}</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
                        <span><i data-feather="layers"></i> Détails par matière</span>
                        <a href="{{ route('gestion_detail_bulletins.create') }}" class="btn btn-sm btn-dark">
                            <i data-feather="plus"></i>&nbsp; Ajouter un détail
                        </a>
                    </div>
                    <div class="table-responsive p-3">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Matière</th>
                                    <th>Moyenne</th>
                                    <th>Coeff.</th>
                                    <th>Moy. pondérée</th>
                                    <th>Rang</th>
                                    <th>Enseignant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bulletin->detailBulletins as $detail)
                                    <tr>
                                        <td>{{ $detail->matiere->intitule ?? 'N/A' }}</td>
                                        <td>{{ $detail->moyenne ?? '-' }}</td>
                                        <td>{{ $detail->coefficient ?? '-' }}</td>
                                        <td>{{ $detail->moyenne_ponderee ?? '-' }}</td>
                                        <td>{{ $detail->rang ?? '-' }}</td>
                                        <td>{{ $detail->enseignant->nom ?? 'N/A' }} {{ $detail->enseignant->prenom ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aucun détail de bulletin</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <i data-feather="activity"></i> Statut
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Statut :</strong>
                            @if($bulletin->status === 'publie')
                                <span class="badge bg-success">Publié</span>
                            @elseif($bulletin->status === 'envoye')
                                <span class="badge bg-info">Envoyé</span>
                            @else
                                <span class="badge bg-warning">Brouillon</span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>Mention conduite :</strong> {{ $bulletin->mention_conduite ?? '-' }}</p>
                        <p class="mb-2"><strong>Publié le :</strong> {{ optional($bulletin->published_at)->format('d/m/Y H:i') ?? '-' }}</p>
                        <p class="mb-0"><strong>Généré le :</strong> {{ optional($bulletin->generated_at)->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0"><i data-feather="settings" class="me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_bulletins.edit', $bulletin->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <form action="{{ route('gestion_bulletins.destroy', $bulletin->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i data-feather="trash-2" class="me-2"></i>Supprimer
                                </button>
                            </form>
                            <a href="{{ route('gestion_bulletins.print', $bulletin->id) }}" class="btn btn-success btn-sm mt-2" target="_blank">
                                <i data-feather="printer" class="me-2"></i>Imprimer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
