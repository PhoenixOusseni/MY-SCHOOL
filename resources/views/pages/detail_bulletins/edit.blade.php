@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier le détail du bulletin
                        </h1>
                        <p class="text-muted">Mettre à jour les résultats d'une matière</p>
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
        <div class="card">
            <div class="card-body">
                <form action="{{ route('gestion_detail_bulletins.update', $detailBulletin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bulletin <span class="text-danger">*</span></label>
                            <select name="bulletin_id" class="form-select @error('bulletin_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($bulletins as $bulletin)
                                    <option value="{{ $bulletin->id }}" @selected(old('bulletin_id', $detailBulletin->bulletin_id) == $bulletin->id)>
                                        {{ $bulletin->eleve->nom ?? 'N/A' }} {{ $bulletin->eleve->prenom ?? '' }} - {{ $bulletin->periodEvaluation->libelle ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulletin_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Matière <span class="text-danger">*</span></label>
                            <select name="matiere_id" class="form-select @error('matiere_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" @selected(old('matiere_id', $detailBulletin->matiere_id) == $matiere->id)>{{ $matiere->intitule }}</option>
                                @endforeach
                            </select>
                            @error('matiere_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Enseignant</label>
                            <select name="enseignant_id" class="form-select @error('enseignant_id') is-invalid @enderror">
                                <option value="">Sélectionner</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" @selected(old('enseignant_id', $detailBulletin->enseignant_id) == $enseignant->id)>
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
                            <input type="number" step="0.01" min="0" name="moyenne" value="{{ old('moyenne', $detailBulletin->moyenne) }}" class="form-control @error('moyenne') is-invalid @enderror">
                            @error('moyenne') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Coefficient</label>
                            <input type="number" step="0.01" min="0" name="coefficient" value="{{ old('coefficient', $detailBulletin->coefficient) }}" class="form-control @error('coefficient') is-invalid @enderror">
                            @error('coefficient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Moy. pondérée</label>
                            <input type="number" step="0.01" min="0" name="moyenne_ponderee" value="{{ old('moyenne_ponderee', $detailBulletin->moyenne_ponderee) }}" class="form-control @error('moyenne_ponderee') is-invalid @enderror">
                            @error('moyenne_ponderee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Moy. classe</label>
                            <input type="number" step="0.01" min="0" name="moyenne_classe" value="{{ old('moyenne_classe', $detailBulletin->moyenne_classe) }}" class="form-control @error('moyenne_classe') is-invalid @enderror">
                            @error('moyenne_classe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Point min</label>
                            <input type="number" step="0.01" min="0" name="point_min" value="{{ old('point_min', $detailBulletin->point_min) }}" class="form-control @error('point_min') is-invalid @enderror">
                            @error('point_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Point max</label>
                            <input type="number" step="0.01" min="0" name="point_max" value="{{ old('point_max', $detailBulletin->point_max) }}" class="form-control @error('point_max') is-invalid @enderror">
                            @error('point_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Rang</label>
                            <input type="number" min="1" name="rang" value="{{ old('rang', $detailBulletin->rang) }}" class="form-control @error('rang') is-invalid @enderror">
                            @error('rang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Appréciation</label>
                            <input type="text" name="appreciation" value="{{ old('appreciation', $detailBulletin->appreciation) }}" class="form-control @error('appreciation') is-invalid @enderror">
                            @error('appreciation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Commentaire enseignant</label>
                        <textarea name="commentaire_enseignant" rows="3" class="form-control @error('commentaire_enseignant') is-invalid @enderror">{{ old('commentaire_enseignant', $detailBulletin->commentaire_enseignant) }}</textarea>
                        @error('commentaire_enseignant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="m-3">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="save"></i>&nbsp; Mettre à jour
                        </button>
                        <a href="{{ route('gestion_detail_bulletins.index') }}" class="btn btn-dark">
                            <i data-feather="x"></i>&nbsp; Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
