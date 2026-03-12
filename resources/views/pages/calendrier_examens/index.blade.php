@extends('layouts.master')

@section('style')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
<style>
    .fc {
        font-family: inherit;
        font-size: 0.875rem;
    }
    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .fc .fc-button-primary {
        background-color: var(--primary, #c41e3a);
        border-color: var(--primary, #c41e3a);
    }
    .fc .fc-button-primary:hover,
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: var(--primary-dark, #8b1a2e);
        border-color: var(--primary-dark, #8b1a2e);
    }
    .fc .fc-daygrid-event {
        border-radius: 4px;
        font-size: 0.75rem;
        padding: 1px 4px;
        cursor: pointer;
    }
    .fc .fc-day-today {
        background-color: rgba(196, 30, 58, 0.05) !important;
    }
    .exam-day-header {
        background: linear-gradient(135deg, #c41e3a, #8b1a2e);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .exam-item {
        border-left: 4px solid;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 0 6px 6px 0;
        background-color: #f8fafc;
        transition: background 0.15s;
    }
    .exam-item:hover {
        background-color: #f1f5f9;
    }
    .type-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.9;
    }
    #calendar-container {
        min-height: 550px;
    }
    .view-toggle .btn {
        font-size: 0.8rem;
    }
    .modal-exam-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label {
        min-width: 110px;
        color: #64748b;
        font-weight: 500;
    }
    .empty-calendar {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')

<header class="page-header page-header-dark header-gradient pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="calendar"></i></div>
                        Calendrier des Examens
                    </h1>
                    <p class="text-muted">Visualisez et planifiez les évaluations par date</p>
                </div>
                <div class="col-auto mt-4 d-flex gap-2">
                    <a href="{{ route('gestion_evaluations.create') }}" class="btn btn-dark btn-sm">
                        <i data-feather="plus"></i>&nbsp; Nouvelle évaluation
                    </a>
                    <a href="{{ route('gestion_evaluations.index') }}" class="btn btn-outline-light btn-sm">
                        <i data-feather="list"></i>&nbsp; Liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-xl px-4 mt-n10">

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon-box bg-danger bg-opacity-10">
                        <i data-feather="calendar"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total</div>
                        <div class="fw-bold fs-5">{{ $evaluations->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon-box bg-success bg-opacity-10">
                        <i data-feather="check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Publiées</div>
                        <div class="fw-bold fs-5">{{ $evaluations->where('est_publie', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon-box bg-warning bg-opacity-10">
                        <i data-feather="book-open"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Examens</div>
                        <div class="fw-bold fs-5">{{ $evaluations->where('type', 'examen')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon-box bg-info bg-opacity-10">
                        <i data-feather="clock"></i>
                    </div>
                    <div>
                        <div class="text-muted small">À venir</div>
                        <div class="fw-bold fs-5">{{ $evaluations->where('date_examen', '>=', now()->toDateString())->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
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

    {{-- Filtres --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2 py-3">
            <i data-feather="filter" style="width:16px;height:16px;color:#c41e3a;"></i>
            <span class="fw-semibold">Filtres</span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('calendrier_examens.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Classe</label>
                    <select name="classe_id" class="form-select form-select-sm">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                                @if($classe->niveau) — {{ $classe->niveau->nom }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Période</label>
                    <select name="period_evaluation_id" class="form-select form-select-sm">
                        <option value="">Toutes les périodes</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}" {{ request('period_evaluation_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Mois</label>
                    <input type="month" name="mois" class="form-control form-control-sm"
                        value="{{ request('mois') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-danger flex-fill">
                        <i data-feather="search" style="width:14px;height:14px;"></i>&nbsp; Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Vue toggle --}}
    <div class="d-flex justify-content-end mb-3 view-toggle">
        <div class="btn-group btn-group-sm gap-2" role="group">
            <button type="button" class="btn btn-danger active" id="btn-calendar-view">
                <i data-feather="grid" style="width:14px;height:14px;"></i>&nbsp; Calendrier
            </button>
            <button type="button" class="btn btn-1" id="btn-list-view">
                <i data-feather="list" style="width:14px;height:14px;"></i>&nbsp; Liste
            </button>
        </div>
    </div>

    {{-- Calendar View --}}
    <div id="view-calendar">
        <div class="card mb-4">
            <div class="card-body p-3" id="calendar-container">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    {{-- List View --}}
    <div id="view-list" style="display:none;">
        @if($evaluationsParJour->isEmpty())
            <div class="card">
                <div class="card-body empty-calendar">
                    <i data-feather="calendar" style="width:3rem;height:3rem;margin-bottom:1rem;opacity:0.3;"></i>
                    <p class="mb-0">Aucun examen trouvé pour les filtres sélectionnés</p>
                </div>
            </div>
        @else
            @foreach($evaluationsParJour as $date => $exams)
                <div class="card mb-3">
                    <div class="exam-day-header d-flex align-items-center gap-2">
                        <i data-feather="calendar" style="width:15px;height:15px;"></i>
                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                        <span class="badge bg-white text-danger ms-auto">{{ $exams->count() }} examen(s)</span>
                    </div>
                    <div class="card-body p-3">
                        @foreach($exams as $exam)
                            @php
                                $color = $exam->enseignementMatiereClasse->matiere->color ?? '#c41e3a';
                            @endphp
                            <div class="exam-item" style="border-left-color: {{ $color }};">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                    <div>
                                        <div class="fw-semibold mb-1">
                                            <a href="{{ route('gestion_evaluations.show', $exam->id) }}" class="text-dark text-decoration-none">
                                                {{ $exam->titre }}
                                            </a>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <span class="badge" style="background-color: {{ $color }}; color: white; font-size:0.7rem;">
                                                {{ $exam->enseignementMatiereClasse->matiere->intitule ?? 'N/A' }}
                                            </span>
                                            <span class="text-muted small">
                                                <i data-feather="users" style="width:12px;height:12px;"></i>
                                                {{ $exam->enseignementMatiereClasse->classe->nom ?? 'N/A' }}
                                            </span>
                                            @if($exam->heure_debut)
                                                <span class="text-muted small">
                                                    <i data-feather="clock" style="width:12px;height:12px;"></i>
                                                    {{ $exam->heure_debut }}
                                                    @if($exam->duree) ({{ $exam->duree }} min) @endif
                                                </span>
                                            @endif
                                            @if($exam->salle)
                                                <span class="text-muted small">
                                                    <i data-feather="map-pin" style="width:12px;height:12px;"></i>
                                                    {{ $exam->salle }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <span class="type-badge" style="background-color: {{ $color }}20; color: {{ $color }};">
                                            {{ ucfirst(str_replace('_', ' ', $exam->type)) }}
                                        </span>
                                        @if($exam->est_publie)
                                            <span class="badge bg-success-subtle text-success" style="font-size:0.65rem;">Publié</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.65rem;">Brouillon</span>
                                        @endif
                                        <div class="mt-1">
                                            <a href="{{ route('gestion_evaluations.show', $exam->id) }}"
                                               class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:0.7rem;">
                                                <i data-feather="eye" style="width:11px;height:11px;"></i> Détail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @if($exam->periodEvaluation)
                                    <div class="text-muted mt-1" style="font-size:0.75rem;">
                                        <i data-feather="tag" style="width:11px;height:11px;"></i>
                                        {{ $exam->periodEvaluation->libelle }}
                                        &middot;
                                        Note max : {{ $exam->note_max ?? '-' }}
                                        &middot;
                                        Coeff : {{ $exam->coefficient ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

{{-- Modal détail examen (depuis calendrier) --}}
<div class="modal fade" id="examModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-exam-header p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="text-white-50 small mb-1" id="modal-type-label"></div>
                        <h5 class="mb-0 text-white" id="modal-titre"></h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="users" style="width:14px;height:14px;" class="me-1"></i>Classe</span>
                    <span id="modal-classe"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="book" style="width:14px;height:14px;" class="me-1"></i>Matière</span>
                    <span id="modal-matiere"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="user-check" style="width:14px;height:14px;" class="me-1"></i>Enseignant</span>
                    <span id="modal-enseignant"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="clock" style="width:14px;height:14px;" class="me-1"></i>Heure</span>
                    <span id="modal-heure"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="activity" style="width:14px;height:14px;" class="me-1"></i>Durée</span>
                    <span id="modal-duree"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="map-pin" style="width:14px;height:14px;" class="me-1"></i>Salle</span>
                    <span id="modal-salle"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="award" style="width:14px;height:14px;" class="me-1"></i>Note max</span>
                    <span id="modal-note-max"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="tag" style="width:14px;height:14px;" class="me-1"></i>Période</span>
                    <span id="modal-periode"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i data-feather="eye" style="width:14px;height:14px;" class="me-1"></i>Statut</span>
                    <span id="modal-statut"></span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a id="modal-link" href="#" class="btn btn-sm btn-danger">
                    <i data-feather="external-link" style="width:14px;height:14px;"></i> Voir le détail
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/fr.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const events = @json($events);

    // FullCalendar init
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        events: events,
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const p = info.event.extendedProps;
            document.getElementById('modal-titre').textContent      = info.event.title.split(' · ')[0];
            document.getElementById('modal-type-label').textContent = p.type;
            document.getElementById('modal-classe').textContent     = p.classe;
            document.getElementById('modal-matiere').textContent    = p.matiere;
            document.getElementById('modal-enseignant').textContent = p.enseignant;
            document.getElementById('modal-heure').textContent      = p.heure;
            document.getElementById('modal-duree').textContent      = p.duree;
            document.getElementById('modal-salle').textContent      = p.salle;
            document.getElementById('modal-note-max').textContent   = p.note_max ? p.note_max + ' pts' : '-';
            document.getElementById('modal-periode').textContent    = p.periode;
            document.getElementById('modal-statut').innerHTML       = p.est_publie
                ? '<span class="badge bg-success">Publié</span>'
                : '<span class="badge bg-secondary">Brouillon</span>';
            document.getElementById('modal-link').href = info.event.url;
            new bootstrap.Modal(document.getElementById('examModal')).show();
            feather.replace();
        },
        dayMaxEvents: 3,
        moreLinkText: function (n) { return '+ ' + n + ' autre(s)'; },
        height: 'auto',
        eventMouseEnter: function (info) {
            info.el.style.opacity = '0.85';
            info.el.style.cursor = 'pointer';
        },
        eventMouseLeave: function (info) {
            info.el.style.opacity = '1';
        },
    });
    calendar.render();

    // Toggle calendar / list
    document.getElementById('btn-calendar-view').addEventListener('click', function () {
        document.getElementById('view-calendar').style.display = '';
        document.getElementById('view-list').style.display = 'none';
        this.classList.add('active', 'btn-danger');
        this.classList.remove('btn-outline-secondary');
        const btn2 = document.getElementById('btn-list-view');
        btn2.classList.remove('active', 'btn-danger');
        btn2.classList.add('btn-outline-secondary');
        calendar.render();
    });

    document.getElementById('btn-list-view').addEventListener('click', function () {
        document.getElementById('view-calendar').style.display = 'none';
        document.getElementById('view-list').style.display = '';
        this.classList.add('active', 'btn-danger');
        this.classList.remove('btn-outline-secondary');
        const btn1 = document.getElementById('btn-calendar-view');
        btn1.classList.remove('active', 'btn-danger');
        btn1.classList.add('btn-outline-secondary');
    });

    feather.replace();
});
</script>
@endsection
