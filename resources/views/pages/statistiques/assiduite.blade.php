@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-calendar-check"></i></div>
                        Rapport d'assiduité
                    </h1>
                    <div class="page-header-subtitle text-white-75">
                        <small>Absences et retards par classe et par élève</small>
                    </div>
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
                                    <option value="{{ $c->id }}" {{ $classeId == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
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
                            <div class="bg-danger rounded p-3"><i class="fas fa-user-times text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total absences</div><div class="fs-4 fw-bold">{{ $totaux['absences'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-warning h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning rounded p-3"><i class="fas fa-clock text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total retards</div><div class="fs-4 fw-bold">{{ $totaux['retards'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-success h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success rounded p-3"><i class="fas fa-check-circle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Abs. justifiées</div><div class="fs-4 fw-bold text-success">{{ $totaux['justifiees'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-dark h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-dark rounded p-3"><i class="fas fa-times-circle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Abs. non justifiées</div><div class="fs-4 fw-bold text-danger">{{ $totaux['non_justifiees'] }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Évolution mensuelle -->
                <div class="col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-line text-danger me-2"></i>Évolution mensuelle des absences</div>
                        <div class="card-body"><canvas id="chartMois" height="130"></canvas></div>
                    </div>
                </div>
                <!-- Répartition justifiées / non -->
                <div class="col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-pie text-warning me-2"></i>Absences justifiées vs non</div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="chartJustif" style="max-height:220px"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Absences par classe -->
                <div class="col-lg-7">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-table text-secondary me-2"></i>Absences par classe</div>
                        <div class="card-body p-0">
                            @if(empty($parClasse) || count($parClasse) === 0)
                                <div class="text-center py-5 text-muted">Aucune absence enregistrée.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Classe</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center text-success">Justifiées</th>
                                                <th class="text-center text-danger">Non just.</th>
                                                <th>Répartition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parClasse as $row)
                                                <tr>
                                                    <td class="ps-3 fw-semibold">{{ $row->classe }}</td>
                                                    <td class="text-center fw-bold">{{ $row->total }}</td>
                                                    <td class="text-center text-success">{{ $row->justifiees }}</td>
                                                    <td class="text-center text-danger">{{ $row->non_justifiees }}</td>
                                                    <td style="width:120px">
                                                        @if($row->total > 0)
                                                            <div class="progress" style="height:8px">
                                                                <div class="progress-bar bg-success" style="width:{{ round($row->justifiees/$row->total*100) }}%"></div>
                                                                <div class="progress-bar bg-danger" style="width:{{ round($row->non_justifiees/$row->total*100) }}%"></div>
                                                            </div>
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

                <!-- Top 10 élèves absents -->
                <div class="col-lg-5">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Top 10 absentéistes</div>
                        <div class="list-group list-group-flush">
                            @forelse($topAbsents as $i => $row)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $i < 3 ? 'bg-danger' : 'bg-light text-dark border' }} rounded-pill">{{ $i+1 }}</span>
                                        <div>
                                            <div class="fw-semibold small">{{ $row->prenom }} {{ $row->nom }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $row->classe }}</div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger small">{{ $row->total }} abs.</div>
                                        <div class="text-muted" style="font-size:11px">{{ $row->non_justifiees }} non just.</div>
                                    </div>
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
const moisLabels = @json(collect($parMois)->pluck('mois_label'));
const moisData   = @json(collect($parMois)->pluck('total'));
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [{ label: 'Absences', data: moisData,
            backgroundColor: 'rgba(220,53,69,.7)', borderRadius: 4 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('chartJustif'), {
    type: 'doughnut',
    data: {
        labels: ['Justifiées', 'Non justifiées'],
        datasets: [{ data: [{{ $totaux['justifiees'] }}, {{ $totaux['non_justifiees'] }}],
            backgroundColor: ['#198754','#dc3545'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
