@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="eye"></i></div>
                            Détail du bulletin
                        </h1>
                        <p class="text-muted">Consultez les informations détaillées de la matière</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_detail_bulletins.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="list"></i>&nbsp; Liste des détails
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
                        <i data-feather="layers"></i> Informations académiques
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Élève</h6>
                                <p class="mb-0">{{ $detailBulletin->bulletin->eleve->nom ?? 'N/A' }} {{ $detailBulletin->bulletin->eleve->prenom ?? '' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Période</h6>
                                <p class="mb-0">{{ $detailBulletin->bulletin->periodEvaluation->libelle ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Matière</h6>
                                <p class="mb-0">{{ $detailBulletin->matiere->intitule ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Moyenne</h6>
                                <p class="mb-0">{{ $detailBulletin->moyenne ?? '-' }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Coeff.</h6>
                                <p class="mb-0">{{ $detailBulletin->coefficient ?? '-' }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Moy. pondérée</h6>
                                <p class="mb-0">{{ $detailBulletin->moyenne_ponderee ?? '-' }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Moy. classe</h6>
                                <p class="mb-0">{{ $detailBulletin->moyenne_classe ?? '-' }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Point min</h6>
                                <p class="mb-0">{{ $detailBulletin->point_min ?? '-' }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">Point max</h6>
                                <p class="mb-0">{{ $detailBulletin->point_max ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Rang</h6>
                                <p class="mb-0">{{ $detailBulletin->rang ?? '-' }}</p>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted mb-1">Enseignant</h6>
                                <p class="mb-0">{{ $detailBulletin->enseignant->nom ?? 'N/A' }} {{ $detailBulletin->enseignant->prenom ?? '' }}</p>
                            </div>
                        </div>

                        @if($detailBulletin->appreciation)
                            <hr>
                            <h6 class="text-muted mb-1">Appréciation</h6>
                            <p class="mb-0">{{ $detailBulletin->appreciation }}</p>
                        @endif

                        @if($detailBulletin->commentaire_enseignant)
                            <hr>
                            <h6 class="text-muted mb-1">Commentaire enseignant</h6>
                            <p class="mb-0">{{ $detailBulletin->commentaire_enseignant }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h6 class="mb-0"><i data-feather="settings" class="me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestion_detail_bulletins.edit', $detailBulletin->id) }}" class="btn btn-dark btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <form action="{{ route('gestion_detail_bulletins.destroy', $detailBulletin->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i data-feather="trash-2" class="me-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
