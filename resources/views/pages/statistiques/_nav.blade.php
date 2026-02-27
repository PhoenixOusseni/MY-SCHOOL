{{-- Partial navigation statiques - inclus dans chaque vue du module --}}
<div class="col-lg-2 d-none d-lg-block">
    <div class="card shadow">
        <div class="card-header bg-white fw-bold text-muted small text-uppercase py-3">
            <i class="fas fa-chart-bar me-2 text-primary"></i>Rapports
        </div>
        <div class="list-group list-group-flush small">
            <a href="{{ route('statistiques.effectifs') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.effectifs') ? 'active' : '' }}">
                <i class="fas fa-users fa-fw"></i> Effectifs
            </a>
            <a href="{{ route('statistiques.resultats') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.resultats') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap fa-fw"></i> Résultats scolaires
            </a>
            <a href="{{ route('statistiques.taux_reussite') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.taux_reussite') ? 'active' : '' }}">
                <i class="fas fa-trophy fa-fw"></i> Taux de réussite
            </a>
            <a href="{{ route('statistiques.finances') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.finances') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave fa-fw"></i> États financiers
            </a>
            <a href="{{ route('statistiques.assiduite') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.assiduite') ? 'active' : '' }}">
                <i class="fas fa-calendar-check fa-fw"></i> Assiduité
            </a>
            <a href="{{ route('statistiques.discipline') }}"
               class="list-group-item list-group-item-action py-2 d-flex align-items-center gap-2 {{ request()->routeIs('statistiques.discipline') ? 'active' : '' }}">
                <i class="fas fa-gavel fa-fw"></i> Discipline
            </a>
        </div>
    </div>
</div>
