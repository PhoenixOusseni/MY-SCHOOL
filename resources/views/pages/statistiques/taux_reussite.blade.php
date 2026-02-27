@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-trophy"></i></div>
                        Taux de réussite
                    </h1>
                    <div class="page-header-subtitle text-white-50">Suivi du taux d'admission par classe et par période</div>
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
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-primary h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-primary rounded p-3"><i class="fas fa-users text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total évalués</div><div class="fs-4 fw-bold">{{ $totaux['total'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-success h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success rounded p-3"><i class="fas fa-check text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Admis (≥10)</div><div class="fs-4 fw-bold">{{ $totaux['admis'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-{{ $totaux['taux_global'] >= 50 ? 'success' : 'danger' }} h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-{{ $totaux['taux_global'] >= 50 ? 'success' : 'danger' }} rounded p-3">
                                <i class="fas fa-percent text-white fa-lg"></i>
                            </div>
                            <div><div class="text-muted small">Taux global</div>
                                <div class="fs-4 fw-bold text-{{ $totaux['taux_global'] >= 50 ? 'success' : 'danger' }}">{{ $totaux['taux_global'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Graphique comparatif classes -->
                <div class="col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Taux de réussite par classe</div>
                        <div class="card-body"><canvas id="chartTaux" height="130"></canvas></div>
                    </div>
                </div>
                <!-- Évolution par période -->
                <div class="col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-line text-success me-2"></i>Évolution par période</div>
                        <div class="card-body"><canvas id="chartEvol" height="180"></canvas></div>
                    </div>
                </div>

                <!-- Tableau détaillé -->
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-table text-secondary me-2"></i>Détail par classe</div>
                        <div class="card-body p-0">
                            @if(count($parClasse) === 0)
                                <div class="text-center py-5 text-muted">Aucune donnée pour cette sélection.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Classe</th>
                                                <th>Niveau</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center text-success">Admis</th>
                                                <th class="text-center text-danger">Échec</th>
                                                <th>Taux de réussite</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parClasse as $row)
                                                <tr>
                                                    <td class="ps-4 fw-semibold">{{ $row->classe }}</td>
                                                    <td><span class="badge bg-light text-dark border">{{ $row->niveau }}</span></td>
                                                    <td class="text-center">{{ $row->total }}</td>
                                                    <td class="text-center fw-bold text-success">{{ $row->admis }}</td>
                                                    <td class="text-center fw-bold text-danger">{{ $row->echec }}</td>
                                                    <td style="width:200px">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="flex-grow-1">
                                                                <div class="progress" style="height:10px">
                                                                    <div class="progress-bar {{ $row->taux >= 50 ? 'bg-success' : 'bg-danger' }}"
                                                                         style="width:{{ $row->taux }}%"></div>
                                                                </div>
                                                            </div>
                                                            <span class="fw-bold {{ $row->taux >= 50 ? 'text-success' : 'text-danger' }}" style="min-width:42px">{{ $row->taux }}%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td class="ps-4" colspan="2">TOTAL</td>
                                                <td class="text-center">{{ $totaux['total'] }}</td>
                                                <td class="text-center text-success">{{ $totaux['admis'] }}</td>
                                                <td class="text-center text-danger">{{ $totaux['total'] - $totaux['admis'] }}</td>
                                                <td class="fw-bold {{ $totaux['taux_global'] >= 50 ? 'text-success' : 'text-danger' }}">{{ $totaux['taux_global'] }}%</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
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
const tauxLabels = @json(collect($parClasse)->pluck('classe'));
const tauxData   = @json(collect($parClasse)->pluck('taux'));
new Chart(document.getElementById('chartTaux'), {
    type: 'bar',
    data: {
        labels: tauxLabels,
        datasets: [
            { label: 'Admis %', data: tauxData,
              backgroundColor: tauxData.map(t => t >= 50 ? 'rgba(25,135,84,.7)' : 'rgba(220,53,69,.7)'),
              borderRadius: 4 }
        ]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { min: 0, max: 100, ticks: { callback: v => v + '%' } } }
    }
});

const evolLabels = @json(collect($evolution)->pluck('periode'));
const evolData   = @json(collect($evolution)->pluck('taux'));
new Chart(document.getElementById('chartEvol'), {
    type: 'line',
    data: {
        labels: evolLabels,
        datasets: [{ label: 'Taux (%)', data: evolData,
            borderColor: '#198754', backgroundColor: 'rgba(25,135,84,.1)',
            tension: 0.3, fill: true, pointRadius: 5 }]
    },
    options: {
        scales: { y: { min: 0, max: 100, ticks: { callback: v => v + '%' } } }
    }
});
</script>
@endpush
