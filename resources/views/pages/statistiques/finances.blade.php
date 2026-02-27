@extends('layouts.master')
@include('partials.style')

@section('content')
<div class="page-header page-header-dark header-gradient pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title mb-0 text-white">
                        <div class="page-header-icon"><i class="fas fa-money-bill-wave"></i></div>
                        États financiers
                    </h1>
                    <div class="page-header-subtitle text-white-50">Suivi des paiements et des recettes scolaires</div>
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
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-success h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success rounded p-3"><i class="fas fa-hand-holding-usd text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Total collecté</div>
                                <div class="fs-4 fw-bold text-success">{{ number_format($totaux['collecte'], 0, ',', ' ') }} F</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-danger h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-danger rounded p-3"><i class="fas fa-exclamation-circle text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Reste à percevoir</div>
                                <div class="fs-4 fw-bold text-danger">{{ number_format($totaux['reste'], 0, ',', ' ') }} F</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow border-start-5 border-start-info h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info rounded p-3"><i class="fas fa-receipt text-white fa-lg"></i></div>
                            <div><div class="text-muted small">Nb de paiements</div><div class="fs-4 fw-bold">{{ $totaux['paiements'] }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            @if($annee)
                @php
                    $totalPercu = $totaux['collecte'] + $totaux['reste'];
                    $pctCollecte = $totalPercu > 0 ? round(($totaux['collecte'] / $totalPercu) * 100, 1) : 0;
                @endphp
                <div class="card shadow mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between mb-1 small fw-semibold">
                            <span>Taux de recouvrement</span>
                            <span class="text-success">{{ $pctCollecte }}%</span>
                        </div>
                        <div class="progress" style="height:14px;border-radius:8px">
                            <div class="progress-bar bg-success" style="width:{{ $pctCollecte }}%">{{ $pctCollecte }}%</div>
                            <div class="progress-bar bg-danger" style="width:{{ 100-$pctCollecte }}%">{{ 100-$pctCollecte }}%</div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size:11px">
                            <span class="text-success">Collecté : {{ number_format($totaux['collecte'], 0, ',', ' ') }} F</span>
                            <span class="text-danger">Reste : {{ number_format($totaux['reste'], 0, ',', ' ') }} F</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <!-- Évolution mensuelle -->
                <div class="col-lg-8">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-line text-success me-2"></i>Évolution mensuelle des recettes</div>
                        <div class="card-body"><canvas id="chartMensuel" height="130"></canvas></div>
                    </div>
                </div>
                <!-- Par méthode -->
                <div class="col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-chart-pie text-info me-2"></i>Par méthode de paiement</div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="chartMethode" style="max-height:220px"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tableau par type de frais -->
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-table text-secondary me-2"></i>Récapitulatif par type de frais</div>
                        <div class="card-body p-0">
                            @if($parFrais->isEmpty())
                                <div class="text-center py-5 text-muted">Aucun paiement enregistré pour cette année.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Type de frais</th>
                                                <th class="text-center">Paiements</th>
                                                <th class="text-end">Total perçu</th>
                                                <th class="text-end">Reste à payer</th>
                                                <th>Recouvrement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($parFrais as $row)
                                                @php
                                                    $base = $row->total_perçu + $row->total_reste;
                                                    $pct  = $base > 0 ? round(($row->total_perçu / $base) * 100) : 0;
                                                @endphp
                                                <tr>
                                                    <td class="ps-4 fw-semibold">{{ $row->frais }}</td>
                                                    <td class="text-center">{{ $row->nb_paiements }}</td>
                                                    <td class="text-end fw-bold text-success">{{ number_format($row->total_perçu, 0, ',', ' ') }} F</td>
                                                    <td class="text-end text-danger">{{ number_format($row->total_reste, 0, ',', ' ') }} F</td>
                                                    <td style="width:160px">
                                                        <div class="progress" style="height:8px">
                                                            <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ $pct }}%</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td class="ps-4">TOTAL</td>
                                                <td class="text-center">{{ $totaux['paiements'] }}</td>
                                                <td class="text-end text-success">{{ number_format($totaux['collecte'], 0, ',', ' ') }} F</td>
                                                <td class="text-end text-danger">{{ number_format($totaux['reste'], 0, ',', ' ') }} F</td>
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
const mensuelLabels = @json($evolutionMensuelle->pluck('mois_label'));
const mensuelData   = @json($evolutionMensuelle->pluck('total'));
new Chart(document.getElementById('chartMensuel'), {
    type: 'bar',
    data: {
        labels: mensuelLabels,
        datasets: [{ label: 'Recettes (F)', data: mensuelData,
            backgroundColor: 'rgba(25,135,84,.7)', borderRadius: 4 }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { ticks: { callback: v => v.toLocaleString('fr-FR') + ' F' } } }
    }
});

const methLabels = @json($parMethode->pluck('methode_paiement')->map(fn($m) => ucfirst($m ?? 'Autre')));
const methData   = @json($parMethode->pluck('total'));
new Chart(document.getElementById('chartMethode'), {
    type: 'doughnut',
    data: {
        labels: methLabels,
        datasets: [{ data: methData,
            backgroundColor: ['#198754','#0d6efd','#ffc107','#20c997','#dc3545','#6f42c1'] }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});
</script>
@endpush
