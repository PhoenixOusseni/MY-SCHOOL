@extends('layouts.master')
@include('partials.style')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-dark header-gradient pb-10 mt-n3">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit-2"></i></div>
                            Modifier le créneau
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $creneau->jour_semaine }}
                            {{ substr($creneau->heure_debut, 0, 5) }}–{{ substr($creneau->heure_fin, 0, 5) }}
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_emploi_temps.index', ['annee_id' => $anneeId, 'classe_id' => $classeId]) }}"
                            class="btn btn-dark">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la grille
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="container-xl px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card shadow mb-4">
                    <div class="card-header fw-500">
                        <i class="fas fa-clock me-2 text-primary"></i>Informations du créneau
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('gestion_emploi_temps.update', $creneau->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Année scolaire -->
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Année scolaire <span
                                            class="text-danger">*</span></label>
                                    <select name="annee_scolaire_id" id="annee_scolaire_id"
                                        class="form-select @error('annee_scolaire_id') is-invalid @enderror"
                                        onchange="reloadPage(this.value, null)">
                                        @foreach ($annees as $a)
                                            <option value="{{ $a->id }}" @selected($a->id == $anneeId)>
                                                {{ $a->libelle }}</option>
                                        @endforeach
                                    </select>
                                    @error('annee_scolaire_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Classe -->
                                <div class="col-md-6">
                                    <label class="form-label fw-500">Classe <span class="text-danger">*</span></label>
                                    <select name="_classe_id" id="classe_id" class="form-select"
                                        onchange="filterEmc(this.value)">
                                        @foreach ($classes as $cl)
                                            <option value="{{ $cl->id }}" @selected($cl->id == $classeId)>
                                                {{ $cl->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Matière / Enseignant (EMC) -->
                                <div class="col-12">
                                    <label class="form-label fw-500">Matière — Enseignant <span
                                            class="text-danger">*</span></label>
                                    <select name="enseignement_matiere_classe_id" id="emc_select"
                                        class="form-select @error('enseignement_matiere_classe_id') is-invalid @enderror">
                                        <option value="">— Sélectionner —</option>
                                    </select>
                                    @error('enseignement_matiere_classe_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jour -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Jour <span class="text-danger">*</span></label>
                                    <select name="jour_semaine"
                                        class="form-select @error('jour_semaine') is-invalid @enderror">
                                        <option value="">— Choisir —</option>
                                        @foreach (\App\Models\EmploiTemp::JOURS as $j)
                                            <option value="{{ $j }}" @selected(old('jour_semaine', $creneau->jour_semaine) == $j)>
                                                {{ $j }}</option>
                                        @endforeach
                                    </select>
                                    @error('jour_semaine')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Heure début -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Heure début <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_debut"
                                        value="{{ old('heure_debut', substr($creneau->heure_debut, 0, 5)) }}"
                                        class="form-control @error('heure_debut') is-invalid @enderror">
                                    @error('heure_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Heure fin -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Heure fin <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_fin"
                                        value="{{ old('heure_fin', substr($creneau->heure_fin, 0, 5)) }}"
                                        class="form-control @error('heure_fin') is-invalid @enderror">
                                    @error('heure_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Salle -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Salle</label>
                                    <input type="text" name="salle" value="{{ old('salle', $creneau->salle) }}"
                                        class="form-control @error('salle') is-invalid @enderror"
                                        placeholder="Ex: Salle 12">
                                    @error('salle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Effective from -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Valable du</label>
                                    <input type="date" name="effective_from"
                                        value="{{ old('effective_from', $creneau->effective_from?->format('Y-m-d')) }}"
                                        class="form-control @error('effective_from') is-invalid @enderror">
                                    @error('effective_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Effective to -->
                                <div class="col-md-4">
                                    <label class="form-label fw-500">Valable au</label>
                                    <input type="date" name="effective_to"
                                        value="{{ old('effective_to', $creneau->effective_to?->format('Y-m-d')) }}"
                                        class="form-control @error('effective_to') is-invalid @enderror">
                                    @error('effective_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" form="editForm" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>&nbsp; Enregistrer
                                    </button>
                                </div>
                                <!-- Supprimer -->
                                <form method="POST" action="{{ route('gestion_emploi_temps.destroy', $creneau->id) }}"
                                    onsubmit="return confirm('Supprimer ce créneau ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>&nbsp; Supprimer
                                    </button>
                                </form>
                            </div>
                        </form>

                        {{-- Note: the main form needs an id for the submit button above --}}
                        <script>
                            // Give the main form an id so the save button works
                            document.addEventListener('DOMContentLoaded', () => {
                                const forms = document.querySelectorAll('form[method="post"]');
                                // The first POST form with PUT method is the edit form
                                forms.forEach(f => {
                                    if (f.querySelector('input[name="_method"][value="PUT"]')) {
                                        f.id = 'editForm';
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EMC data for JS -->
    <script>
        const emcParClasse = @json($emcParClasse);
        const selectedEmc = {{ old('enseignement_matiere_classe_id', $creneau->enseignement_matiere_classe_id) }};

        function filterEmc(classeId) {
            const sel = document.getElementById('emc_select');
            sel.innerHTML = '<option value="">— Sélectionner —</option>';
            const items = emcParClasse[classeId] || [];
            items.forEach(emc => {
                const opt = document.createElement('option');
                opt.value = emc.id;
                opt.text = (emc.matiere?.intitule || 'Matière?') + ' — ' + (emc.enseignant?.prenom || '') + ' ' + (
                    emc.enseignant?.nom || '');
                if (emc.id == selectedEmc) opt.selected = true;
                sel.appendChild(opt);
            });
        }

        function reloadPage(anneeId, classeId) {
            let url = "{{ route('gestion_emploi_temps.edit', $creneau->id) }}?annee_id=" + anneeId;
            if (classeId) url += '&classe_id=' + classeId;
            window.location.href = url;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const classeId = document.getElementById('classe_id').value;
            if (classeId) filterEmc(classeId);

            document.getElementById('classe_id').addEventListener('change', function() {
                filterEmc(this.value);
            });
        });
    </script>
@endsection
