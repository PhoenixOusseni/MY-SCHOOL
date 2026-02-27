@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-graduation-cap"></i></div>
                        Résultats scolaires
                    </h1>
                    <div class="page-header-subtitle text-white-50">Moyennes, mentions et classements par période</div>
                </div>
                <div class="col-auto mt-4">
                    <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                        <select name="annee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($annees as $a)
                                <option value="{{ $a->id }}" {{ $annee?->id == $a->id ? 'selected' : '' }}>{{ $a->libelle }}</option>
                            @endforeach
                        </select>
                        @if($periodes->count())
                            <select name="periode_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>{{ $p->libelle }}</option>
                                @endforeach
                            </select>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-n10">
    <div class="row g-4">
        @include('pages.statistiques._nav')

        <div class="col-lg-10">
            <!-- Stat cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-primary h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-primary rounded p-3"><i class="fas fa-file-alt text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Bulletins générés</div><div class="fs-4 fw-bold">{{ $totaux['bulletins'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-success h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success rounded p-3"><i class="fas fa-chart-line text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Moyenne générale</div><div class="fs-4 fw-bold">{{ $totaux['moyenne_generale'] }}/20</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-info h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info rounded p-3"><i class="fas fa-check-circle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Admis (≥10)</div>
                                <div class="fs-4 fw-bold">{{ collect($parClasse)->sum('admis') }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-danger h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-danger rounded p-3"><i class="fas fa-times-circle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">En échec (&lt;10)</div>
                                <div class="fs-4 fw-bold">{{ collect($parClasse)->sum(fn($r) => $r->nb_eleves - $r->admis) }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Graphique moyennes par classe -->
                <div class="col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Moyennes par classe</div>
                        <div class="card-body"><canvas id="chartMoyennes" height="130"></canvas></div>
                    </div>
                </div>
                <!-- Distribution mentions -->
                <div class="col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-pie text-success me-2"></i>Distribution des mentions</div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="chartMentions" style="max-height:220px"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tableau par classe -->
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-table text-secondary me-2"></i>Résultats par classe</div>
                        <div class="card-body p-0">
                            @if(empty($parClasse) || count($parClasse) === 0)
                                <div class="text-center py-5 text-muted">Aucun bulletin pour cette période.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Classe</th>
                                                <th class="text-center">Élèves</th>
                                                <th class="text-center">Moy.</th>
                                                <th class="text-center">Min</th>
                                                <th class="text-center">Max</th>
                                                <th class="text-center">Admis</th>
                                                <th>Taux</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parClasse as $row)
                                                <tr>
                                                    <td class="ps-3 fw-semibold">{{ $row->classe }}</td>
                                                    <td class="text-center">{{ $row->nb_eleves }}</td>
                                                    <td class="text-center">
                                                        <span class="fw-bold {{ $row->moyenne >= 10 ? 'text-success' : 'text-danger' }}">
                                                            {{ $row->moyenne }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-muted">{{ $row->min_moy }}</td>
                                                    <td class="text-center text-muted">{{ $row->max_moy }}</td>
                                                    <td class="text-center">{{ $row->admis }}/{{ $row->nb_eleves }}</td>
                                                    <td style="width:120px">
                                                        <div class="progress" style="height:8px">
                                                            <div class="progress-bar {{ $row->taux_reussite >= 50 ? 'bg-success' : 'bg-danger' }}"
                                                                 style="width:{{ $row->taux_reussite }}%"></div>
                                                        </div>
                                                        <small class="{{ $row->taux_reussite >= 50 ? 'text-success' : 'text-danger' }} fw-semibold">{{ $row->taux_reussite }}%</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top 10 élèves -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-trophy text-warning me-2"></i>Top 10 élèves</div>
                        <div class="list-group list-group-flush">
                            @forelse($topEleves as $i => $b)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $i < 3 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} rounded-pill">{{ $i+1 }}</span>
                                        <div>
                                            <div class="fw-semibold small">{{ $b->eleve->prenom ?? '' }} {{ $b->eleve->nom ?? '' }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $b->classe->libelle ?? '' }}</div>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-success">{{ $b->moyenne_globale }}/20</span>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-4">Aucune donnée</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
const classeLabels = @json(collect($parClasse)->pluck('classe'));
const moyData      = @json(collect($parClasse)->pluck('moyenne'));
new Chart(document.getElementById('chartMoyennes'), {
    type: 'bar',
    data: {
        labels: classeLabels,
        datasets: [{ label: 'Moyenne', data: moyData,
            backgroundColor: moyData.map(m => m >= 10 ? 'rgba(25,135,84,.7)' : 'rgba(220,53,69,.7)'),
            borderRadius: 4 }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { min: 0, max: 20, grid: { color: '#eee' },
                 annotations: { line10: { type: 'line', yMin: 10, yMax: 10, borderColor: 'red', borderDash: [6,3] } } }
        }
    }
});

const mentionLabels = @json(array_keys($distributionMentions));
const mentionData   = @json(array_values($distributionMentions));
new Chart(document.getElementById('chartMentions'), {
    type: 'pie',
    data: {
        labels: mentionLabels,
        datasets: [{ data: mentionData,
            backgroundColor: ['#198754','#20c997','#0dcaf0','#ffc107','#dc3545'] }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});
</script>
@endpush
