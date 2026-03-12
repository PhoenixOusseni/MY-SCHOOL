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
                        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAjoutDetail">
                            <i data-feather="plus"></i>&nbsp; Ajouter un détail
                        </button>
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
                            <a href="{{ route('gestion_bulletins.edit', $bulletin->id) }}" class="btn btn-1 btn-sm">
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

    {{-- Modal Ajout Détail Bulletin --}}
    <div class="modal fade" id="modalAjoutDetail" tabindex="-1" aria-labelledby="modalAjoutDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjoutDetailLabel">
                        <i data-feather="plus-circle" class="me-2"></i>Ajouter un détail de bulletin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('gestion_detail_bulletins.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bulletin_id" value="{{ $bulletin->id }}">
                    <input type="hidden" name="redirect_url" value="{{ route('gestion_bulletins.show', $bulletin->id) }}">
                    <div class="modal-body">

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Matière <span class="text-danger">*</span></label>
                                <select name="matiere_id" class="form-select @error('matiere_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}" @selected(old('matiere_id') == $matiere->id)>{{ $matiere->intitule }}</option>
                                    @endforeach
                                </select>
                                @error('matiere_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Enseignant</label>
                                <select name="enseignant_id" class="form-select @error('enseignant_id') is-invalid @enderror">
                                    <option value="">Sélectionner</option>
                                    @foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" @selected(old('enseignant_id') == $enseignant->id)>
                                            {{ $enseignant->nom }} {{ $enseignant->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Moyenne</label>
                                <input type="number" step="0.01" min="0" name="moyenne" value="{{ old('moyenne') }}" class="form-control @error('moyenne') is-invalid @enderror">
                                @error('moyenne') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Coefficient</label>
                                <input type="number" step="0.01" min="0" name="coefficient" value="{{ old('coefficient') }}" class="form-control @error('coefficient') is-invalid @enderror">
                                @error('coefficient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Moy. pondérée</label>
                                <input type="number" step="0.01" min="0" name="moyenne_ponderee" value="{{ old('moyenne_ponderee') }}" class="form-control @error('moyenne_ponderee') is-invalid @enderror">
                                @error('moyenne_ponderee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Moy. classe</label>
                                <input type="number" step="0.01" min="0" name="moyenne_classe" value="{{ old('moyenne_classe') }}" class="form-control @error('moyenne_classe') is-invalid @enderror">
                                @error('moyenne_classe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Point min</label>
                                <input type="number" step="0.01" min="0" name="point_min" value="{{ old('point_min') }}" class="form-control @error('point_min') is-invalid @enderror">
                                @error('point_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Point max</label>
                                <input type="number" step="0.01" min="0" name="point_max" value="{{ old('point_max') }}" class="form-control @error('point_max') is-invalid @enderror">
                                @error('point_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Rang</label>
                                <input type="number" min="1" name="rang" value="{{ old('rang') }}" class="form-control @error('rang') is-invalid @enderror">
                                @error('rang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-9 mb-3">
                                <label class="form-label">Appréciation</label>
                                <input type="text" name="appreciation" value="{{ old('appreciation') }}" class="form-control @error('appreciation') is-invalid @enderror">
                                @error('appreciation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Commentaire enseignant</label>
                            <textarea name="commentaire_enseignant" rows="3" class="form-control @error('commentaire_enseignant') is-invalid @enderror">{{ old('commentaire_enseignant') }}</textarea>
                            @error('commentaire_enseignant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i data-feather="x"></i>&nbsp; Annuler
                        </button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i data-feather="save"></i>&nbsp; Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('modalAjoutDetail'));
            modal.show();
        });
    </script>
    @endif
@endsection
