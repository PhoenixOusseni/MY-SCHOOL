@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier le bulletin
                        </h1>
                        <p class="text-muted">Mettre à jour les informations du bulletin</p>
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
        <div class="card">
            <div class="card-body">
                <form action="{{ route('gestion_bulletins.update', $bulletin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Élève <span class="text-danger">*</span></label>
                            <select name="eleve_id" class="form-select @error('eleve_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($eleves as $eleve)
                                    <option value="{{ $eleve->id }}" @selected(old('eleve_id', $bulletin->eleve_id) == $eleve->id)>
                                        {{ $eleve->nom }} {{ $eleve->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('eleve_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Classe <span class="text-danger">*</span></label>
                            <select name="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" @selected(old('classe_id', $bulletin->classe_id) == $classe->id)>{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                            @error('classe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Période d'évaluation <span class="text-danger">*</span></label>
                            <select name="period_evaluation_id" class="form-select @error('period_evaluation_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($periodEvaluations as $period)
                                    <option value="{{ $period->id }}" @selected(old('period_evaluation_id', $bulletin->period_evaluation_id) == $period->id)>
                                        {{ $period->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('period_evaluation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Moyenne globale</label>
                            <input type="number" step="0.01" min="0" name="moyenne_globale" value="{{ old('moyenne_globale', $bulletin->moyenne_globale) }}" class="form-control @error('moyenne_globale') is-invalid @enderror">
                            @error('moyenne_globale') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Rang</label>
                            <input type="number" min="1" name="rang" value="{{ old('rang', $bulletin->rang) }}" class="form-control @error('rang') is-invalid @enderror">
                            @error('rang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Total élèves</label>
                            <input type="number" min="1" name="total_eleves" value="{{ old('total_eleves', $bulletin->total_eleves) }}" class="form-control @error('total_eleves') is-invalid @enderror">
                            @error('total_eleves') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Mention conduite</label>
                            <input type="text" name="mention_conduite" value="{{ old('mention_conduite', $bulletin->mention_conduite) }}" class="form-control @error('mention_conduite') is-invalid @enderror">
                            @error('mention_conduite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Total points</label>
                            <input type="number" step="0.01" min="0" name="total_points" value="{{ old('total_points', $bulletin->total_points) }}" class="form-control @error('total_points') is-invalid @enderror">
                            @error('total_points') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Total coefficient</label>
                            <input type="number" step="0.01" min="0" name="total_coefficient" value="{{ old('total_coefficient', $bulletin->total_coefficient) }}" class="form-control @error('total_coefficient') is-invalid @enderror">
                            @error('total_coefficient') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Absences</label>
                            <input type="number" min="0" name="absences" value="{{ old('absences', $bulletin->absences) }}" class="form-control @error('absences') is-invalid @enderror">
                            @error('absences') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Justifiées</label>
                            <input type="number" min="0" name="justification_absences" value="{{ old('justification_absences', $bulletin->justification_absences) }}" class="form-control @error('justification_absences') is-invalid @enderror">
                            @error('justification_absences') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Retards</label>
                            <input type="number" min="0" name="retards" value="{{ old('retards', $bulletin->retards) }}" class="form-control @error('retards') is-invalid @enderror">
                            @error('retards') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="brouillon" @selected(old('status', $bulletin->status) === 'brouillon')>Brouillon</option>
                                <option value="publie" @selected(old('status', $bulletin->status) === 'publie')>Publié</option>
                                <option value="envoye" @selected(old('status', $bulletin->status) === 'envoye')>Envoyé</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date publication</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($bulletin->published_at)->format('Y-m-d\TH:i')) }}" class="form-control @error('published_at') is-invalid @enderror">
                            @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date génération</label>
                            <input type="datetime-local" name="generated_at" value="{{ old('generated_at', optional($bulletin->generated_at)->format('Y-m-d\TH:i')) }}" class="form-control @error('generated_at') is-invalid @enderror">
                            @error('generated_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Commentaire principal</label>
                        <textarea name="commentaire_principal" rows="3" class="form-control @error('commentaire_principal') is-invalid @enderror">{{ old('commentaire_principal', $bulletin->commentaire_principal) }}</textarea>
                        @error('commentaire_principal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Commentaire directeur</label>
                        <textarea name="commentaire_directeur" rows="3" class="form-control @error('commentaire_directeur') is-invalid @enderror">{{ old('commentaire_directeur', $bulletin->commentaire_directeur) }}</textarea>
                        @error('commentaire_directeur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="m-3">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="save"></i>&nbsp; Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
