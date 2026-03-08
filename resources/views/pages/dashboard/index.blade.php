@extends('layouts.master')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* ── KPI Cards ─────────────────────────────── */
        .kpi-card {
            border-radius: 14px !important;
            border: 1px solid var(--border-color) !important;
            background: #fff !important;
            box-shadow: var(--card-shadow) !important;
            transition: box-shadow 0.2s ease, transform 0.2s ease !important;
            overflow: hidden;
        }
        .kpi-card:hover {
            box-shadow: var(--card-shadow-hover) !important;
            transform: translateY(-2px);
        }
        .kpi-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .kpi-icon.red    { background: rgba(196,30,58,0.1); color: #c41e3a; }
        .kpi-icon.blue   { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .kpi-icon.green  { background: rgba(16,185,129,0.1); color: #10b981; }
        .kpi-icon.orange { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .kpi-icon.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }
        .kpi-icon.teal   { background: rgba(20,184,166,0.1); color: #14b8a6; }

        .kpi-value {
            font-size: 1.85rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--text-primary);
        }
        .kpi-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }
        .kpi-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 20px;
        }
        .kpi-badge.up   { background: rgba(16,185,129,0.12); color: #059669; }
        .kpi-badge.down { background: rgba(239,68,68,0.10); color: #dc2626; }
        .kpi-badge.neutral { background: rgba(100,116,139,0.1); color: #64748b; }

        /* ── Section title ─────────────────────────── */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-secondary);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        /* ── Chart wrapper ─────────────────────────── */
        .chart-wrapper {
            position: relative;
            height: 240px;
        }
        .chart-wrapper-lg {
            position: relative;
            height: 280px;
        }

        /* ── Quick stat badges ─────────────────────── */
        .stat-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #fafafa;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: background 0.15s;
        }
        .stat-pill:hover { background: #f1f5f9; }
        .stat-pill-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .stat-pill-val {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }
        .stat-pill-lbl {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        /* ── Quick links ───────────────────────────── */
        .quick-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 18px 10px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #fff;
            text-decoration: none !important;
            color: var(--text-primary) !important;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }
        .quick-link:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(196,30,58,0.15);
        }
        .quick-link-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            background: var(--body-bg);
            transition: background 0.2s;
        }
        .quick-link:hover .quick-link-icon {
            background: rgba(196,30,58,0.12);
        }

        /* ── Progress bar ──────────────────────────── */
        .progress-sm { height: 6px; border-radius: 4px; background: #f1f5f9; }
        .progress-sm .progress-bar { border-radius: 4px; }

        /* ── Annee badge ───────────────────────────── */
        .annee-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.18);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(4px);
        }

        /* ── Taux gauge ────────────────────────────── */
        .taux-circle {
            width: 100px; height: 100px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .taux-circle-inner {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
        }
    </style>
@endsection

@section('content')
<main>
    {{-- ══════════════════ PAGE HEADER ══════════════════ --}}
    <header class="page-header page-header-dark pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Tableau de bord
                        </h1>
                        <p class="mb-0 mt-1" style="color:rgba(255,255,255,0.7);font-size:13px;">
                            Vue d'ensemble de votre établissement scolaire
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mt-4 d-flex align-items-center gap-2 flex-wrap">
                        @if($annee)
                        <span class="annee-badge">
                            <i data-feather="calendar" style="width:13px;height:13px;"></i>
                            {{ $annee->libelle }}
                        </span>
                        @endif
                        <span class="annee-badge">
                            <i data-feather="clock" style="width:13px;height:13px;"></i>
                            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════════════ MAIN CONTENT ══════════════════ --}}
    <div class="container-xl px-4 mt-n10" style="position:relative;z-index:2;">

        {{-- ── KPI ROW 1 : Effectifs ─────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-sm-6 col-lg-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($totalEleves) }}</div>
                            <div class="kpi-label">Élèves inscrits</div>
                        </div>
                        <div class="kpi-icon red"><i data-feather="users"></i></div>
                    </div>
                    <div class="mt-2">
                        <span class="kpi-badge neutral">
                            <i data-feather="user-check" style="width:11px;height:11px;"></i>
                            {{ $totalInscrits }} cette année
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-lg-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($totalEnseignants) }}</div>
                            <div class="kpi-label">Enseignants</div>
                        </div>
                        <div class="kpi-icon blue"><i data-feather="briefcase"></i></div>
                    </div>
                    <div class="mt-2">
                        <span class="kpi-badge neutral">Corps enseignant</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-lg-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($totalClasses) }}</div>
                            <div class="kpi-label">Classes actives</div>
                        </div>
                        <div class="kpi-icon green"><i data-feather="grid"></i></div>
                    </div>
                    <div class="mt-2">
                        <span class="kpi-badge neutral">Année en cours</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-lg-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value" style="font-size:1.4rem;">{{ number_format($totalCollecte, 0, ',', ' ') }} F</div>
                            <div class="kpi-label">Revenus collectés</div>
                        </div>
                        <div class="kpi-icon orange"><i data-feather="dollar-sign"></i></div>
                    </div>
                    <div class="mt-2">
                        @if($totalReste > 0)
                        <span class="kpi-badge down">
                            {{ number_format($totalReste, 0, ',', ' ') }} F restant
                        </span>
                        @else
                        <span class="kpi-badge up">Tout soldé</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ROW 2 : Taux réussite + Assiduité/Discipline ──────── --}}
        <div class="row g-3 mb-4">

            {{-- Taux de réussite --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i data-feather="award" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>Taux de réussite</span>
                        @if($dernierePeriode)
                        <span class="badge" style="background:var(--primary-light);color:var(--primary);font-size:11px;">
                            {{ $dernierePeriode->libelle ?? 'Dernière période' }}
                        </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                        <div style="position:relative;width:160px;height:160px;">
                            <canvas id="tauxDonut"></canvas>
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;">
                                <div style="font-size:2rem;font-weight:800;color:var(--text-primary);">{{ $tauxReussite }}%</div>
                                <div style="font-size:11px;color:var(--text-secondary);font-weight:500;">réussite</div>
                            </div>
                        </div>
                        <div class="row w-100 mt-3 text-center">
                            <div class="col-6">
                                <div class="fw-700" style="font-size:1.1rem;color:#10b981;">{{ $tauxReussite }}%</div>
                                <div style="font-size:11px;color:var(--text-secondary);">Admis</div>
                            </div>
                            <div class="col-6">
                                <div class="fw-700" style="font-size:1.1rem;color:#ef4444;">{{ 100 - $tauxReussite }}%</div>
                                <div style="font-size:11px;color:var(--text-secondary);">En échec</div>
                            </div>
                        </div>
                        <a href="{{ route('statistiques.taux_reussite') }}" class="btn btn-sm mt-3 w-100" style="background:var(--primary-light);color:var(--primary);border:none;font-size:12px;border-radius:8px;">
                            Voir le rapport complet →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Assiduité & Discipline ─ mois courant --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i data-feather="clock" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>
                        Assiduité &amp; Discipline — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </div>
                    <div class="card-body d-flex flex-column gap-3">
                        <div class="stat-pill">
                            <div class="stat-pill-icon" style="background:rgba(239,68,68,0.1);color:#ef4444;">
                                <i data-feather="user-x" style="width:16px;height:16px;"></i>
                            </div>
                            <div>
                                <div class="stat-pill-val">{{ $absencesMois }}</div>
                                <div class="stat-pill-lbl">Absences ce mois</div>
                            </div>
                            <a href="{{ route('gestion_absences.index') }}" class="ms-auto text-muted" style="font-size:12px;">Voir →</a>
                        </div>
                        <div class="stat-pill">
                            <div class="stat-pill-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
                                <i data-feather="alert-triangle" style="width:16px;height:16px;"></i>
                            </div>
                            <div>
                                <div class="stat-pill-val">{{ $retardsMois }}</div>
                                <div class="stat-pill-lbl">Retards ce mois</div>
                            </div>
                            <a href="{{ route('gestion_retards.index') }}" class="ms-auto text-muted" style="font-size:12px;">Voir →</a>
                        </div>
                        <div class="stat-pill">
                            <div class="stat-pill-icon" style="background:rgba(139,92,246,0.1);color:#8b5cf6;">
                                <i data-feather="shield" style="width:16px;height:16px;"></i>
                            </div>
                            <div>
                                <div class="stat-pill-val">{{ $incidentsMois }}</div>
                                <div class="stat-pill-lbl">Incidents disciplinaires</div>
                            </div>
                            <a href="{{ route('gestion_incidents.index') }}" class="ms-auto text-muted" style="font-size:12px;">Voir →</a>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('statistiques.assiduite') }}" class="btn btn-sm flex-fill" style="background:var(--primary-light);color:var(--primary);border:none;font-size:12px;border-radius:8px;">
                                Rapport assiduité
                            </a>
                            <a href="{{ route('statistiques.discipline') }}" class="btn btn-sm flex-fill" style="background:rgba(139,92,246,0.1);color:#8b5cf6;border:none;font-size:12px;border-radius:8px;">
                                Rapport discipline
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Répartition par genre --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i data-feather="pie-chart" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>
                        Répartition par genre
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="chart-wrapper w-100 d-flex align-items-center justify-content-center" style="height:180px;">
                            <canvas id="genreChart" style="max-width:180px;max-height:180px;"></canvas>
                        </div>
                        <div class="row w-100 mt-2">
                            @php
                                $garcons = $parGenre->firstWhere('genre', 'masculin')?->total ?? $parGenre->firstWhere('genre', 'Masculin')?->total ?? 0;
                                $filles  = $parGenre->firstWhere('genre', 'féminin')?->total ?? $parGenre->firstWhere('genre', 'Féminin')?->total ?? 0;
                                $genreTotal = $garcons + $filles;
                            @endphp
                            <div class="col-6 text-center">
                                <div style="width:10px;height:10px;background:#3b82f6;border-radius:2px;display:inline-block;margin-right:4px;"></div>
                                <span style="font-size:12px;color:var(--text-secondary);">Garçons</span>
                                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">{{ $garcons }}</div>
                                <div style="font-size:11px;color:var(--text-secondary);">
                                    {{ $genreTotal > 0 ? round(($garcons/$genreTotal)*100, 1) : 0 }}%
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div style="width:10px;height:10px;background:#ec4899;border-radius:2px;display:inline-block;margin-right:4px;"></div>
                                <span style="font-size:12px;color:var(--text-secondary);">Filles</span>
                                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">{{ $filles }}</div>
                                <div style="font-size:11px;color:var(--text-secondary);">
                                    {{ $genreTotal > 0 ? round(($filles/$genreTotal)*100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('statistiques.effectifs') }}" class="btn btn-sm mt-3 w-100" style="background:var(--primary-light);color:var(--primary);border:none;font-size:12px;border-radius:8px;">
                            Voir les effectifs complets →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ROW 3 : Évolution effectifs + Paiements mensuels ───── --}}
        <div class="row g-3 mb-4">

            {{-- Évolution des effectifs --}}
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i data-feather="trending-up" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>Évolution des effectifs</span>
                        <a href="{{ route('statistiques.effectifs') }}" style="font-size:12px;color:var(--primary);text-decoration:none;">Voir détails →</a>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper-lg">
                            <canvas id="effectifsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paiements mensuels --}}
            <div class="col-12 col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i data-feather="bar-chart-2" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>Paiements mensuels</span>
                        <a href="{{ route('statistiques.finances') }}" style="font-size:12px;color:var(--primary);text-decoration:none;">Voir finances →</a>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper-lg">
                            <canvas id="paiementsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ROW 4 : Top classes + Accès rapides ────────────────── --}}
        <div class="row g-3 mb-4">

            {{-- Top classes --}}
            <div class="col-12 col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i data-feather="layers" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>Top classes par effectif</span>
                        <a href="{{ route('gestion_inscriptions.index') }}" style="font-size:12px;color:var(--primary);text-decoration:none;">Toutes les inscriptions →</a>
                    </div>
                    <div class="card-body">
                        @php $maxEffectif = $topClasses->max('total') ?: 1; @endphp
                        @forelse($topClasses as $i => $cls)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width:20px;height:20px;border-radius:50%;background:var(--primary-light);color:var(--primary);font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $i + 1 }}</span>
                                    <span style="font-size:13px;font-weight:500;color:var(--text-primary);">{{ $cls->classe }}</span>
                                </div>
                                <span style="font-size:13px;font-weight:700;color:var(--text-primary);">{{ $cls->total }}</span>
                            </div>
                            <div class="progress-sm">
                                <div class="progress-bar" style="width:{{ round(($cls->total/$maxEffectif)*100) }}%;background:{{ ['#c41e3a','#3b82f6','#10b981','#f59e0b','#8b5cf6'][$i] }};"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4" style="font-size:13px;">
                            <i data-feather="inbox" style="width:28px;height:28px;opacity:0.4;display:block;margin:0 auto 8px;"></i>
                            Aucune donnée disponible
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Accès rapides --}}
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <i data-feather="zap" style="width:15px;height:15px;margin-right:6px;color:var(--primary);"></i>
                        Accès rapides
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_eleves.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="users" style="width:18px;height:18px;"></i></div>
                                    Élèves
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_inscriptions.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="user-plus" style="width:18px;height:18px;"></i></div>
                                    Inscriptions
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_enseignants.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="briefcase" style="width:18px;height:18px;"></i></div>
                                    Enseignants
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_classes.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="grid" style="width:18px;height:18px;"></i></div>
                                    Classes
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_bulletins.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="file-text" style="width:18px;height:18px;"></i></div>
                                    Bulletins
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_notes.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="edit-3" style="width:18px;height:18px;"></i></div>
                                    Notes
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_paiements.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="credit-card" style="width:18px;height:18px;"></i></div>
                                    Paiements
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_absences.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="user-x" style="width:18px;height:18px;"></i></div>
                                    Absences
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_evaluations.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="check-square" style="width:18px;height:18px;"></i></div>
                                    Évaluations
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_emploi_temps.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="calendar" style="width:18px;height:18px;"></i></div>
                                    Emploi du temps
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('gestion_incidents.index') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="shield" style="width:18px;height:18px;"></i></div>
                                    Discipline
                                </a>
                            </div>
                            <div class="col-4 col-sm-3 col-md-3">
                                <a href="{{ route('statistiques.effectifs') }}" class="quick-link">
                                    <div class="quick-link-icon"><i data-feather="bar-chart" style="width:18px;height:18px;"></i></div>
                                    Statistiques
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-xl -->
</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Palette primaire ───────────────────────────────────────
    const RED    = '#c41e3a';
    const BLUE   = '#3b82f6';
    const GREEN  = '#10b981';
    const AMBER  = '#f59e0b';
    const PURPLE = '#8b5cf6';
    const PINK   = '#ec4899';
    const TEAL   = '#14b8a6';

    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#64748b';

    // ── 1. Donut — Taux de réussite ────────────────────────────
    const tauxVal = {{ $tauxReussite }};
    new Chart(document.getElementById('tauxDonut'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [tauxVal, 100 - tauxVal],
                backgroundColor: [GREEN, '#f1f5f9'],
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            cutout: '75%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            animation: { animateRotate: true, duration: 800 }
        }
    });

    // ── 2. Donut — Genre ───────────────────────────────────────
    const garcons = {{ $garcons ?? 0 }};
    const filles  = {{ $filles  ?? 0 }};
    new Chart(document.getElementById('genreChart'), {
        type: 'doughnut',
        data: {
            labels: ['Garçons', 'Filles'],
            datasets: [{
                data: [garcons, filles],
                backgroundColor: [BLUE, PINK],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.raw}`
                    }
                }
            },
            animation: { duration: 800 }
        }
    });

    // ── 3. Bar — Évolution effectifs ───────────────────────────
    const evolutionData = @json($evolutionEffectifs);
    new Chart(document.getElementById('effectifsChart'), {
        type: 'bar',
        data: {
            labels: evolutionData.map(r => r.annee),
            datasets: [{
                label: 'Effectif total',
                data: evolutionData.map(r => r.total),
                backgroundColor: 'rgba(196,30,58,0.15)',
                borderColor: RED,
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { stepSize: 10 }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.raw} élèves`
                    }
                }
            },
            animation: { duration: 700 }
        }
    });

    // ── 4. Line — Paiements mensuels ──────────────────────────
    const paiementsData = @json($paiementsMensuels);
    new Chart(document.getElementById('paiementsChart'), {
        type: 'line',
        data: {
            labels: paiementsData.map(r => r.mois_label),
            datasets: [{
                label: 'Montant collecté',
                data: paiementsData.map(r => r.total),
                borderColor: AMBER,
                backgroundColor: 'rgba(245,158,11,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: AMBER,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        callback: v => v.toLocaleString() + ' F'
                    }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.raw.toLocaleString()} F`
                    }
                }
            },
            animation: { duration: 700 }
        }
    });

});
</script>
@endsection
