@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-gavel"></i></div>
                        Rapport discipline
                    </h1>
                    <div class="page-header-subtitle text-white-50">Incidents disciplinaires et sanctions par classe</div>
                </div>
                <div class="col-auto mt-4">
                    <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                        <select name="annee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($annees as $a)
                                <option value="{{ $a->id }}" {{ $annee?->id == $a->id ? 'selected' : '' }}>{{ $a->libelle }}</option>
                            @endforeach
                        </select>
                        @if($classes->count())
                            <select name="classe_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">— Toutes les classes —</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ $classeId == $c->id ? 'selected' : '' }}>{{ $c->libelle }}</option>
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
                    <div class="card shadow border-start-5 border-start-danger h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-danger rounded p-3"><i class="fas fa-exclamation-triangle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total incidents</div><div class="fs-4 fw-bold">{{ $totaux['incidents'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-warning h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning rounded p-3"><i class="fas fa-gavel text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Sanctions</div><div class="fs-4 fw-bold">{{ $totaux['sanctions'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-dark h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-dark rounded p-3"><i class="fas fa-skull-crossbones text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Incidents graves</div><div class="fs-4 fw-bold text-danger">{{ $totaux['graves'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-info h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info rounded p-3"><i class="fas fa-phone text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Parents notifiés</div><div class="fs-4 fw-bold">{{ $totaux['parents_notifies'] }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Évolution mensuelle -->
                <div class="col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-line text-danger me-2"></i>Évolution mensuelle des incidents</div>
                        <div class="card-body"><canvas id="chartMois" height="130"></canvas></div>
                    </div>
                </div>
                <!-- Types d'incidents -->
                <div class="col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-pie text-warning me-2"></i>Types d'incidents</div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="chartTypes" style="max-height:220px"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Incidents par classe -->
                <div class="col-lg-7">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-table text-secondary me-2"></i>Incidents par classe</span>
                        </div>
                        <div class="card-body p-0">
                            @if(empty($parClasse) || count($parClasse) === 0)
                                <div class="text-center py-5 text-muted">Aucun incident enregistré.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Classe</th>
                                                <th class="text-center">Incidents</th>
                                                <th class="text-center">Parents notifiés</th>
                                                <th>Sévérité</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parClasse as $row)
                                                <tr>
                                                    <td class="ps-3 fw-semibold">{{ $row->classe }}</td>
                                                    <td class="text-center fw-bold text-danger">{{ $row->total }}</td>
                                                    <td class="text-center">{{ $row->notifies }}</td>
                                                    <td style="width:120px">
                                                        @if($totaux['incidents'] > 0)
                                                            @php $pct = round($row->total / $totaux['incidents'] * 100) @endphp
                                                            <div class="progress" style="height:8px">
                                                                <div class="progress-bar bg-danger" style="width:{{ $pct }}%"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $pct }}% du total</small>
                                                        @endif
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

                <!-- Top élèves + Sanctions -->
                <div class="col-lg-5">
                    <!-- Top 10 élèves -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-user-times text-danger me-2"></i>Top élèves (incidents)</div>
                        <div class="list-group list-group-flush">
                            @forelse($topEleves as $i => $row)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $i < 3 ? 'bg-danger' : 'bg-light text-dark border' }} rounded-pill">{{ $i+1 }}</span>
                                        <div>
                                            <div class="fw-semibold small">{{ $row->prenom }} {{ $row->nom }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $row->classe }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger">{{ $row->total }}</span>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-3">Aucune donnée</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sanctions par type -->
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-gavel text-warning me-2"></i>Sanctions par type</div>
                        <div class="list-group list-group-flush">
                            @forelse($sanctionsParType as $s)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <span class="small fw-semibold">{{ ucfirst($s->type ?? 'Non précisé') }}</span>
                                    <span class="badge bg-warning text-dark">{{ $s->total }}</span>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-3">Aucune sanction</div>
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
const moisLabels = @json(collect($parMois)->pluck('mois_label'));
const moisData   = @json(collect($parMois)->pluck('total'));
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [{ label: 'Incidents', data: moisData,
            backgroundColor: 'rgba(220,53,69,.7)', borderRadius: 4 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

const typeLabels = @json(collect($parType)->pluck('type')->map(fn($t) => ucfirst($t ?? 'Autre')));
const typeData   = @json(collect($parType)->pluck('total'));
new Chart(document.getElementById('chartTypes'), {
    type: 'doughnut',
    data: {
        labels: typeLabels,
        datasets: [{ data: typeData,
            backgroundColor: ['#dc3545','#ffc107','#fd7e14','#6f42c1','#0dcaf0','#20c997'] }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});
</script>
@endpush
