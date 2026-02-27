@extends('layouts.master')

@section('content')

    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="info"></i></div>
                            Modifier une période d'évaluation
                        </h1>
                        <p class="text-muted">{{ $periode->libelle }}</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_periodes_evaluation.index') }}" class="btn btn-dark">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('gestion_periodes_evaluation.update', $periode->id) }}" method="POST"
                            id="periodeForm">
                            @csrf
                            @method('PUT')

                            <!-- Libellé -->
                            <div class="mb-3">
                                <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle" value="{{ old('libelle', $periode->libelle) }}"
                                    placeholder="ex: Trimestre 1, Semestre 1" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    @foreach ($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('type', $periode->type) == $value)>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Année scolaire -->
                            <div class="mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                    id="annee_scolaire_id" name="annee_scolaire_id" required>
                                    @foreach ($anneesScolaires as $annee)
                                        <option value="{{ $annee->id }}" @selected(old('annee_scolaire_id', $periode->annee_scolaire_id) == $annee->id)>
                                            {{ $annee->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_scolaire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date début -->
                            <div class="mb-3">
                                <label for="date_debut" class="form-label">Date de début <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                                    id="date_debut" name="date_debut" value="{{ old('date_debut', $periode->date_debut) }}"
                                    required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date fin -->
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date de fin <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                    id="date_fin" name="date_fin" value="{{ old('date_fin', $periode->date_fin) }}"
                                    required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ordre d'affichage -->
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                    id="order_index" name="order_index"
                                    value="{{ old('order_index', $periode->order_index) }}" placeholder="ex: 1, 2, 3..."
                                    min="1">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Optionnel - utilisé pour trier les périodes</small>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&nbsp; Enregistrer les modifications
                                </button>
                                <a href="{{ route('gestion_periodes_evaluation.index') }}" class="btn btn-dark">
                                    <i data-feather="x"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <strong>Erreurs de validation:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="col-lg-4">
                <!-- Current Status -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Statut actuel</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Libellé</p>
                            <p class="mb-2">
                                <strong>{{ $periode->libelle }}</strong>
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Type</p>
                            <p class="mb-2">
                                @if ($periode->type === 'trimester')
                                    <span class="badge bg-primary">Trimestre</span>
                                @elseif($periode->type === 'semester')
                                    <span class="badge bg-info">Semestre</span>
                                @elseif($periode->type === 'quarter')
                                    <span class="badge bg-secondary">Quart</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Année</p>
                            <p class="mb-2"><strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong></p>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Durée</p>
                            <p class="mb-0"><strong>{{ \Carbon\Carbon::parse($periode->date_debut)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($periode->date_fin)->format('d/m/Y') }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Modification History -->
                <div class="card mb-4">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">Historique</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i data-feather="calendar"></i>
                            <strong>Créée:</strong> {{ $periode->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-muted small mb-0">
                            <i data-feather="refresh-cw"></i>
                            <strong>Modifiée:</strong> {{ $periode->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0 text-light">Zone de danger</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Supprimer cette période d'évaluation supprimera également tous les enregistrements associés.
                        </p>
                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i data-feather="trash-2"></i>&nbsp; Supprimer la période
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette période d'évaluation?</p>
                    <div class="alert alert-warning">
                        <strong>{{ $periode->libelle }}</strong> -
                        <strong>{{ $periode->anneeScolaire->libelle ?? 'N/A' }}</strong>
                    </div>
                    <p class="text-muted small"><i data-feather="alert-triangle"></i> Cette action est
                        <strong>irréversible</strong>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('gestion_periodes_evaluation.destroy', $periode->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Form validation
        document.getElementById('periodeForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
@endsection
