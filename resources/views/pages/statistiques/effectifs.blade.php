@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-users"></i></div>
                        Effectifs
                    </h1>
                    <div class="page-header-subtitle text-white-50">Répartition des élèves par classe, niveau et genre</div>
                </div>
                <div class="col-auto mt-4">
                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <select name="annee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($annees as $a)
                                <option value="{{ $a->id }}" {{ $annee?->id == $a->id ? 'selected' : '' }}>{{ $a->libelle }}</option>
                            @endforeach
                        </select>
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
                            <div class="bg-primary rounded p-3"><i class="fas fa-users text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total élèves</div><div class="fs-4 fw-bold">{{ $totaux['total'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-info h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info rounded p-3"><i class="fas fa-chalkboard text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Classes actives</div><div class="fs-4 fw-bold">{{ $totaux['classes'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-success h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success rounded p-3"><i class="fas fa-venus text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Filles</div><div class="fs-4 fw-bold">{{ $totaux['filles'] }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow border-start-5 border-start-warning h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning rounded p-3"><i class="fas fa-mars text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Garçons</div><div class="fs-4 fw-bold">{{ $totaux['garcons'] }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Graphique évolution + genre -->
                <div class="col-lg-8">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-line text-primary me-2"></i>Évolution des effectifs</div>
                        <div class="card-body"><canvas id="chartEvolution" height="120"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-pie text-info me-2"></i>Répartition genre</div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="chartGenre" style="max-height:200px"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tableau par classe -->
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-table text-secondary me-2"></i>Effectifs par classe</div>
                        <div class="card-body p-0">
                            @if($parClasse->isEmpty())
                                <div class="text-center py-5 text-muted">Aucune donnée pour cette année scolaire.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Classe</th>
                                                <th>Niveau</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Garçons</th>
                                                <th class="text-center">Filles</th>
                                                <th>Répartition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parClasse as $row)
                                                <tr>
                                                    <td class="ps-4 fw-semibold">{{ $row['classe'] }}</td>
                                                    <td><span class="badge bg-light text-dark border">{{ $row['niveau'] }}</span></td>
                                                    <td class="text-center fw-bold">{{ $row['total'] }}</td>
                                                    <td class="text-center text-warning">{{ $row['garcons'] }}</td>
                                                    <td class="text-center text-success">{{ $row['filles'] }}</td>
                                                    <td style="width:160px">
                                                        @if($row['total'] > 0)
                                                            @php $pctG = round($row['garcons']/$row['total']*100) @endphp
                                                            <div class="progress" style="height:8px">
                                                                <div class="progress-bar bg-warning" style="width:{{ $pctG }}%"></div>
                                                                <div class="progress-bar bg-success" style="width:{{ 100-$pctG }}%"></div>
                                                            </div>
                                                            <div class="d-flex justify-content-between" style="font-size:11px">
                                                                <span class="text-warning">{{ $pctG }}% G</span>
                                                                <span class="text-success">{{ 100-$pctG }}% F</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td class="ps-4" colspan="2">TOTAL</td>
                                                <td class="text-center">{{ $totaux['total'] }}</td>
                                                <td class="text-center text-warning">{{ $totaux['garcons'] }}</td>
                                                <td class="text-center text-success">{{ $totaux['filles'] }}</td>
                                                <td></td>
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
// Évolution
const evLabels  = @json($evolution->pluck('annee'));
const evData    = @json($evolution->pluck('total'));
new Chart(document.getElementById('chartEvolution'), {
    type: 'line',
    data: {
        labels: evLabels,
        datasets: [{ label: 'Effectifs', data: evData,
            borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.1)',
            tension: 0.3, fill: true, pointRadius: 5 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Genre
const genreLabels = @json($parGenre->pluck('genre')->map(fn($g) => ucfirst($g ?? 'Non spécifié')));
const genreData   = @json($parGenre->pluck('total'));
new Chart(document.getElementById('chartGenre'), {
    type: 'doughnut',
    data: {
        labels: genreLabels,
        datasets: [{ data: genreData, backgroundColor: ['#ffc107','#198754','#0dcaf0','#dc3545'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
