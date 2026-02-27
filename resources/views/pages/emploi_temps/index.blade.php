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
                        <div class="page-header-icon"><i data-feather="clock"></i></div>
                        Emploi du temps
                    </h1>
                    <div class="page-header-subtitle">
                        {{ $annee?->libelle ?? 'Aucune année scolaire active' }}
                        @if($classe) — {{ $classe->nom }} @endif
                    </div>
                </div>
                <div class="col-auto mt-4">
                    @if($annee && $classeId)
                        <a href="{{ route('gestion_emploi_temps.create', ['annee_id' => $annee->id, 'classe_id' => $classeId]) }}"
                           class="btn btn-dark btn-sm fw-500">
                            <i class="fas fa-plus me-1"></i> Ajouter un créneau
                        </a>
                    @endif
                    <button onclick="window.print()" class="btn btn-light btn-sm">
                        <i class="fas fa-print me-1"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="container-xl px-4 mt-n10">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4 shadow no-print">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('gestion_emploi_temps.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-500 text-muted mb-1">Année scolaire</label>
                    <select name="annee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($annees as $a)
                            <option value="{{ $a->id }}" @selected($annee && $a->id == $annee->id)>{{ $a->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-500 text-muted mb-1">Classe</label>
                    <select name="classe_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($classes as $cl)
                            <option value="{{ $cl->id }}" @selected($cl->id == $classeId)>{{ $cl->nom }}</option>
                        @endforeach
                        @if($classes->isEmpty())
                            <option disabled>Aucune classe</option>
                        @endif
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grille hebdomadaire -->
    <div class="card shadow mb-4">
        <div class="card-header fw-500">
            <i class="fas fa-calendar-week me-2 text-primary"></i>Grille hebdomadaire
        </div>
        <div class="card-body p-0 p-md-2" style="overflow-x:auto;">

            @php
                $heureDebut = 7;    // 07:00
                $heureFin   = 19;   // 19:00
                $scale      = 1.5;  // px par minute
                $totalMinutes = ($heureFin - $heureDebut) * 60;
                $gridHeight   = $totalMinutes * $scale;

                $palette = [
                    '#4e73df','#1cc88a','#36b9cc','#f6c23e',
                    '#e74a3b','#6f42c1','#fd7e14','#20c9a6',
                ];
                // Assign a color per matière id
                $colorMap = [];
            @endphp

            <div class="timetable-wrapper" style="display:flex; min-width:800px;">
                <!-- Colonne des heures -->
                <div class="timetable-hours" style="width:56px; flex-shrink:0; position:relative; height:{{ $gridHeight + 30 }}px; margin-top:30px;">
                    @for($h = $heureDebut; $h <= $heureFin; $h++)
                        @php $top = ($h - $heureDebut) * 60 * $scale; @endphp
                        <div style="position:absolute; top:{{ $top }}px; width:100%; font-size:0.72rem; color:#6c757d; text-align:right; padding-right:6px; transform:translateY(-50%);">
                            {{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00
                        </div>
                    @endfor
                </div>

                <!-- Colonnes par jour -->
                @foreach(\App\Models\EmploiTemp::JOURS as $jour)
                    <div class="timetable-day" style="flex:1; min-width:110px; margin:0 2px;">
                        <!-- En-tête du jour -->
                        <div style="height:30px; font-size:0.8rem; font-weight:600; text-align:center; background:#f8f9fa; border:1px solid #e3e6f0; border-radius:4px 4px 0 0; line-height:30px;">
                            {{ $jour }}
                        </div>
                        <!-- Zone créneaux -->
                        <div style="position:relative; height:{{ $gridHeight }}px; border:1px solid #e3e6f0; border-top:none; background:#fff;">
                            <!-- Lignes de fond (chaque heure) -->
                            @for($h = $heureDebut; $h < $heureFin; $h++)
                                @php $top = ($h - $heureDebut) * 60 * $scale; @endphp
                                <div style="position:absolute; top:{{ $top }}px; left:0; right:0; border-top:1px dashed #e9ecef;"></div>
                            @endfor

                            <!-- Créneaux du jour -->
                            @if(isset($grille[$jour]))
                                @foreach($grille[$jour] as $c)
                                    @php
                                        [$dh, $dm] = explode(':', substr($c->heure_debut, 0, 5));
                                        [$fh, $fm] = explode(':', substr($c->heure_fin, 0, 5));
                                        $minutesDebut = (int)$dh * 60 + (int)$dm - $heureDebut * 60;
                                        $minutesFin   = (int)$fh * 60 + (int)$fm - $heureDebut * 60;
                                        $top    = max(0, $minutesDebut * $scale);
                                        $height = max(20, ($minutesFin - $minutesDebut) * $scale - 2);
                                        $emc    = $c->enseignementMatiereClasse;
                                        $mid    = $emc?->matiere?->id ?? 0;
                                        if (!isset($colorMap[$mid])) {
                                            $colorMap[$mid] = $palette[count($colorMap) % count($palette)];
                                        }
                                        $color = $colorMap[$mid];
                                    @endphp
                                    <div style="position:absolute; top:{{ $top }}px; left:3px; right:3px; height:{{ $height }}px;
                                                background:{{ $color }}1a; border-left:3px solid {{ $color }};
                                                border-radius:3px; padding:3px 4px; overflow:hidden; font-size:0.7rem;">
                                        <div style="font-weight:700; color:{{ $color }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ $emc?->matiere?->intitule ?? 'Matière?' }}
                                        </div>
                                        @if($height > 30)
                                            <div style="color:#555; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $emc?->enseignant?->prenom }} {{ $emc?->enseignant?->nom }}
                                            </div>
                                        @endif
                                        @if($height > 45 && $c->salle)
                                            <div style="color:#888;">
                                                <i class="fas fa-map-marker-alt"></i> {{ $c->salle }}
                                            </div>
                                        @endif
                                        <div style="color:#777; font-size:0.65rem;">
                                            {{ substr($c->heure_debut,0,5) }}–{{ substr($c->heure_fin,0,5) }}
                                        </div>
                                        <!-- Actions -->
                                        <div class="creneau-actions no-print" style="position:absolute; top:2px; right:2px; display:flex; gap:2px;">
                                            <a href="{{ route('gestion_emploi_temps.edit', $c->id) }}"
                                               class="btn btn-xs p-0 px-1" style="background:{{ $color }}33; color:{{ $color }}; font-size:0.6rem; border:none; border-radius:2px;" title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form method="POST" action="{{ route('gestion_emploi_temps.destroy', $c->id) }}"
                                                  onsubmit="return confirm('Supprimer ce créneau ?')" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-xs p-0 px-1"
                                                        style="background:#dc354533; color:#dc3545; font-size:0.6rem; border:none; border-radius:2px;" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if(!$annee || !$classeId)
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                    Sélectionnez une année scolaire et une classe pour afficher la grille.
                </div>
            @elseif($creneaux->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-week fa-3x mb-3 d-block"></i>
                    Aucun créneau enregistré pour cette classe.
                    <div class="mt-2">
                        <a href="{{ route('gestion_emploi_temps.create', ['annee_id' => $annee->id, 'classe_id' => $classeId]) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Ajouter le premier créneau
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Légende -->
    @if($creneaux->isNotEmpty())
        <div class="card shadow mb-4 no-print">
            <div class="card-header fw-500 py-2">Légende</div>
            <div class="card-body py-2 d-flex flex-wrap gap-3">
                @php $shownMids = []; @endphp
                @foreach($creneaux as $c)
                    @php
                        $emc = $c->enseignementMatiereClasse;
                        $mid = $emc?->matiere?->id ?? 0;
                        if (in_array($mid, $shownMids)) continue;
                        $shownMids[] = $mid;
                        $col = $colorMap[$mid] ?? '#888';
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:14px; height:14px; background:{{ $col }}; border-radius:3px; display:inline-block;"></span>
                        <span class="small">{{ $emc?->matiere?->intitule }}</span>
                        <span class="text-muted small">— {{ $emc?->enseignant?->prenom }} {{ $emc?->enseignant?->nom }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div><!-- /container -->

<style>
@media print {
    .no-print, .sidenav, .topnav, .page-header { display: none !important; }
    .container-xl { padding: 0 !important; }
    .timetable-wrapper { min-width: unset !important; }
    .card { box-shadow: none !important; border: 1px solid #999 !important; }
}
</style>

@endsection
