# SCHOOL MANAGER – Mémoire Claude

## Stack
- Laravel (PHP), Blade, Bootstrap 5, Feather Icons, Chart.js 4, jQuery, DataTables
- DB: MySQL (via Eloquent ORM)
- CSS: variables custom dans `layouts/master.blade.php` (`--primary: #c41e3a`, `--body-bg: #f1f5f9`, etc.)

## Structure clé
- `app/Models/` : Eleve, Enseignant, Classe, Inscription, AnneeScolaire, Bulletin, Paiement, Absence, Retard, IncidentDisciplinaire, Sanction, Note, etc.
- `app/Http/Controllers/PageController.php` : dashboard, home, profile
- `app/Http/Controllers/StatistiqueController.php` : effectifs, resultats, tauxReussite, finances, assiduite, discipline
- `routes/web.php` : toutes les routes (resources + routes nommées)
- `resources/views/layouts/master.blade.php` : layout principal (topbar + sidebar + footer)
- `resources/views/partials/` : header, footer, script, style, sideNav, topNav, meta

## Dashboard
- Vue: `resources/views/pages/dashboard/index.blade.php`
- Contrôleur: `PageController::dashboard()` – passe les stats via compact()
- Stats incluses: totalEleves, totalEnseignants, totalClasses, totalInscrits, totalCollecte, totalReste, absencesMois, retardsMois, incidentsMois, parGenre, evolutionEffectifs, tauxReussite, topClasses, paiementsMensuels
- Charts (Chart.js 4): donut taux réussite, donut genre, bar évolution effectifs, line paiements mensuels

## Conventions
- `AnneeScolaire::where('is_current', true)->first()` pour l'année courante
- Couleurs icônes KPI : red=#c41e3a, blue=#3b82f6, green=#10b981, orange=#f59e0b, purple=#8b5cf6
- Page header : `page-header page-header-dark pb-10` + `mt-n10` pour le contenu qui chevauche
- Les vues utilisent `@section('style')` pour les styles spécifiques et `@section('script')` pour les scripts
