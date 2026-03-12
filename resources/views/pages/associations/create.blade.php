@extends('layouts.master')

@section('style')
    @include('partials.style')
    <style>
        .eleve-list {
            max-height: 340px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: .375rem;
            background: #fff;
        }
        .eleve-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            cursor: pointer;
            border-bottom: 1px solid #f1f3f5;
            transition: background .1s;
        }
        .eleve-item:last-child { border-bottom: none; }
        .eleve-item:hover { background: #f8f9fa; }
        .eleve-item.selected { background: #e8f4fd; }
        .eleve-item input[type="checkbox"] { flex-shrink: 0; width: 1rem; height: 1rem; cursor: pointer; }
        .eleve-item label { cursor: pointer; margin: 0; flex: 1; font-size: .875rem; }
        .eleve-item .badge-classe { font-size: .7rem; white-space: nowrap; }
        .selected-tags { display: flex; flex-wrap: wrap; gap: .35rem; min-height: 32px; }
        .selected-tag {
            display: inline-flex; align-items: center; gap: .3rem;
            background: #0d6efd; color: #fff;
            border-radius: 20px; padding: .2rem .65rem;
            font-size: .78rem;
        }
        .selected-tag .remove-tag { cursor: pointer; opacity: .8; font-size: .85rem; }
        .selected-tag .remove-tag:hover { opacity: 1; }
        #eleveSearch { border-radius: .375rem .375rem 0 0; border-bottom: none; }
        .eleve-list { border-radius: 0 0 .375rem .375rem; }
        #selectAllRow {
            display: flex; align-items: center; gap: .6rem;
            padding: .4rem .75rem; background: #f8f9fa;
            border: 1px solid #dee2e6; border-bottom: 2px solid #dee2e6;
            font-size: .8rem; font-weight: 600; color: #495057;
        }
        #selectAllRow input { width: 1rem; height: 1rem; cursor: pointer; }
        .empty-message { text-align: center; color: #adb5bd; padding: 1.5rem; font-size: .85rem; }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="link-2"></i></div>
                            Ajouter une Association
                        </h1>
                        <p class="page-header-subtitle">
                            Associez un ou plusieurs élèves à un tuteur en définissant les rôles et permissions.
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_associations.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i data-feather="alert-circle" class="me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="associationForm" action="{{ route('gestion_associations.store') }}" method="POST">
            @csrf
            @if ($tuteurId)
                <input type="hidden" name="tuteur_id" value="{{ $tuteurId }}">
            @endif

            <div class="row">

                {{-- ── COLONNE GAUCHE : Sélection des élèves ── --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i data-feather="users" class="me-2"></i>Sélection des élèves</h6>
                        </div>
                        <div class="card-body p-3">

                            {{-- Tags des élèves sélectionnés --}}
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Élèves sélectionnés</small>
                                    <span class="badge bg-primary" id="selectedCount">0</span>
                                </div>
                                <div id="selectedTags" class="selected-tags p-2 border rounded bg-light" style="min-height: 40px;">
                                    <span class="text-muted" id="noSelectionText" style="font-size:.8rem;">Aucun élève sélectionné</span>
                                </div>
                            </div>

                            {{-- Barre de recherche --}}
                            <input type="text" id="eleveSearch" class="form-control"
                                placeholder="Rechercher un élève par nom, prénom ou classe…">

                            {{-- Ligne Tout sélectionner --}}
                            <div id="selectAllRow">
                                <input type="checkbox" id="selectAll">
                                <label for="selectAll" style="cursor:pointer; margin:0;">Tout sélectionner / désélectionner</label>
                                <span id="visibleCount" class="ms-auto text-muted fw-normal"></span>
                            </div>

                            {{-- Liste des élèves --}}
                            <div class="eleve-list" id="eleveList">
                                @foreach ($eleves as $eleve)
                                    @php
                                        $classe = $eleve->inscriptions()->first()?->classe;
                                        $classeNom = $classe?->libelle ?? ($classe?->nom ?? 'Sans classe');
                                    @endphp
                                    <div class="eleve-item"
                                         data-search="{{ strtolower($eleve->prenom . ' ' . $eleve->nom . ' ' . $classeNom) }}"
                                         data-id="{{ $eleve->id }}"
                                         onclick="toggleItem(this)">
                                        <input type="checkbox" name="eleve_ids[]"
                                               value="{{ $eleve->id }}"
                                               id="eleve_{{ $eleve->id }}"
                                               {{ in_array($eleve->id, (array) old('eleve_ids', [])) ? 'checked' : '' }}
                                               onclick="event.stopPropagation();"
                                               onchange="onCheckChange(this)">
                                        <label for="eleve_{{ $eleve->id }}">
                                            <strong>{{ $eleve->prenom . ' ' . strtoupper($eleve->nom) }}</strong>
                                        </label>
                                        <span class="badge bg-secondary badge-classe">{{ $classeNom }}</span>
                                    </div>
                                @endforeach
                                <div class="empty-message d-none" id="emptyMessage">Aucun élève trouvé</div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── COLONNE DROITE : Tuteur & Paramètres ── --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i data-feather="user" class="me-2"></i>Tuteur</h6>
                        </div>
                        <div class="card-body p-3">
                            @if ($tuteur)
                                <input type="hidden" name="tuteur_id" value="{{ $tuteur->id }}">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="{{ $tuteur->prenom . ' ' . strtoupper($tuteur->nom) }}" disabled>
                                    <span class="input-group-text">
                                        @php
                                            $labels = ['pere'=>'Père','mere'=>'Mère','tuteur'=>'Tuteur','autre'=>'Autre'];
                                        @endphp
                                        <span class="badge bg-primary">{{ $labels[$tuteur->relationship] ?? 'N/A' }}</span>
                                    </span>
                                </div>
                            @else
                                <select class="form-select" id="tuteur_id" name="tuteur_id" required
                                    onchange="updateTuteurInfo()">
                                    <option value="" selected disabled>-- Sélectionner un tuteur --</option>
                                    @foreach ($tuteurs as $t)
                                        <option value="{{ $t->id }}"
                                            data-relationship="{{ $t->relationship }}"
                                            data-telephone="{{ $t->telephone }}"
                                            data-email="{{ $t->email }}"
                                            {{ old('tuteur_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->prenom . ' ' . strtoupper($t->nom) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    Lien&nbsp;: <span id="tuteurRelationship">—</span> &nbsp;|&nbsp;
                                    Tél&nbsp;: <span id="tuteurTelephone">—</span>
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- Paramètres --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i data-feather="settings" class="me-2"></i>Paramètres</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary"
                                    {{ old('is_primary') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_primary">
                                    <strong>Tuteur Principal</strong>
                                    <small class="d-block text-muted">Contact principal pour la scolarité</small>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="can_pickup" name="can_pickup"
                                    {{ old('can_pickup') ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_pickup">
                                    <strong>Peut venir chercher l'élève</strong>
                                    <small class="d-block text-muted">Autoriser ce tuteur à récupérer l'élève</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="emergency_contact" name="emergency_contact"
                                    {{ old('emergency_contact') ? 'checked' : '' }}>
                                <label class="form-check-label" for="emergency_contact">
                                    <strong>Contact d'urgence</strong>
                                    <small class="d-block text-muted">Contacter en cas d'urgence</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Bouton --}}
                    <button type="submit" class="btn btn-1 w-100" id="submitBtn" disabled>
                        <i data-feather="check" class="me-2"></i>Créer l'Association
                    </button>
                    <div id="submitHint" class="text-center mt-2 text-muted" style="font-size:.8rem;">
                        Sélectionnez au moins un élève pour continuer
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        @php
            $relationshipLabel = ['pere'=>'Père','mere'=>'Mère','tuteur'=>'Tuteur','autre'=>'Autre'];
        @endphp

        // ── Tuteur info ──────────────────────────────────────────────
        function updateTuteurInfo() {
            const select = document.getElementById('tuteur_id');
            const option = select.options[select.selectedIndex];
            const labels = @json($relationshipLabel);
            document.getElementById('tuteurRelationship').textContent = labels[option.dataset.relationship] || '—';
            document.getElementById('tuteurTelephone').textContent = option.dataset.telephone || '—';
        }

        // ── Sélection des élèves ─────────────────────────────────────
        function getCheckedItems() {
            return document.querySelectorAll('#eleveList input[type="checkbox"]:checked');
        }

        function updateUI() {
            const checked = getCheckedItems();
            const count = checked.length;

            // Compteur badge
            document.getElementById('selectedCount').textContent = count;

            // Tags
            const tagsContainer = document.getElementById('selectedTags');
            const noText = document.getElementById('noSelectionText');
            tagsContainer.innerHTML = '';

            if (count === 0) {
                noText.style.display = '';
                tagsContainer.appendChild(noText);
            } else {
                noText.style.display = 'none';
                checked.forEach(cb => {
                    const item = cb.closest('.eleve-item');
                    const label = item.querySelector('label').textContent.trim();
                    const classe = item.querySelector('.badge-classe').textContent.trim();

                    const tag = document.createElement('span');
                    tag.className = 'selected-tag';
                    tag.innerHTML = `${label} <small>(${classe})</small> <span class="remove-tag" onclick="removeEleve(${cb.value})">&times;</span>`;
                    tagsContainer.appendChild(tag);
                });
            }

            // Bouton submit
            const submitBtn = document.getElementById('submitBtn');
            const hint = document.getElementById('submitHint');
            submitBtn.disabled = count === 0;
            hint.style.display = count === 0 ? '' : 'none';

            // Highlight items
            document.querySelectorAll('.eleve-item').forEach(item => {
                const cb = item.querySelector('input[type="checkbox"]');
                item.classList.toggle('selected', cb.checked);
            });

            updateSelectAll();
        }

        function toggleItem(item) {
            const cb = item.querySelector('input[type="checkbox"]');
            cb.checked = !cb.checked;
            updateUI();
        }

        function onCheckChange(cb) {
            updateUI();
        }

        function removeEleve(id) {
            const cb = document.getElementById('eleve_' + id);
            if (cb) { cb.checked = false; updateUI(); }
        }

        // ── Recherche ─────────────────────────────────────────────────
        document.getElementById('eleveSearch').addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.eleve-item').forEach(item => {
                const match = !q || item.dataset.search.includes(q);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('emptyMessage').classList.toggle('d-none', visible > 0);
            document.getElementById('visibleCount').textContent = visible ? `${visible} élève(s)` : '';
            updateSelectAll();
        });

        // ── Tout sélectionner ─────────────────────────────────────────
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.eleve-item:not([style*="display: none"]) input[type="checkbox"]')
                .forEach(cb => cb.checked = this.checked);
            updateUI();
        });

        function updateSelectAll() {
            const visible = document.querySelectorAll('.eleve-item:not([style*="display: none"]) input[type="checkbox"]');
            const checkedVisible = Array.from(visible).filter(cb => cb.checked);
            const sa = document.getElementById('selectAll');
            sa.indeterminate = checkedVisible.length > 0 && checkedVisible.length < visible.length;
            sa.checked = visible.length > 0 && checkedVisible.length === visible.length;
            document.getElementById('visibleCount').textContent = visible.length ? `${visible.length} élève(s)` : '';
        }

        // ── Validation soumission ─────────────────────────────────────
        document.getElementById('associationForm').addEventListener('submit', function(e) {
            if (getCheckedItems().length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un élève.');
            }
        });

        // ── Init ──────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            updateUI();
            updateSelectAll();
            if (document.getElementById('tuteur_id')?.value) updateTuteurInfo();
        });
    </script>
@endsection
