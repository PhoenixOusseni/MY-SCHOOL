<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\EleveParentController;
use App\Http\Controllers\TuteurController;
use App\Http\Controllers\FraiScolariteController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\EnseignementMatiereClasseController;
use App\Http\Controllers\PeriodeEvaluationController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\SoumissionDevoirController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\DetailBulletinController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\RetardController;
use App\Http\Controllers\IncidentDisciplinaireController;
use App\Http\Controllers\SanctionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\EmploiTempController;
use App\Http\Controllers\DossierEleveController;


// Routes publiques (non authentifiées)
Route::get('/', [PageController::class, 'home'])->name('login');
Route::get('/register_users', [PageController::class, 'add_users'])->name('add_users');
Route::post('connexion', [AuthController::class, 'login'])->name('connexion');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Routes protégées (authentification requise)
Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('profile/{id}', [PageController::class, 'profile'])->name('profile');

    // Gestion des établissements
    Route::resource('gestion_etablissements', EtablissementController::class);

    // Gestion des années scolaires
    Route::resource('gestion_annees_scolaires', AnneeScolaireController::class);

    // Gestion des niveaux
    Route::resource('gestion_niveaux', NiveauController::class);

    // Gestion des matieres
    Route::resource('gestion_matieres', MatiereController::class);

    // Gestion des classes
    Route::resource('gestion_classes', ClasseController::class);

    // Gestion des eleves
    Route::resource('gestion_eleves', EleveController::class);

    // Dossiers des élèves
    Route::get('dossiers_eleves', [DossierEleveController::class, 'index'])->name('dossiers_eleves.index');
    Route::get('dossiers_eleves/{id}', [DossierEleveController::class, 'show'])->name('dossiers_eleves.show');

    // Gestion des inscriptions
    Route::resource('gestion_inscriptions', InscriptionController::class);
    Route::get('gestion_inscriptions/{id}/print', [InscriptionController::class, 'print'])->name('gestion_inscriptions.print');

    // Gestion des tuteurs
    Route::resource('gestion_tuteurs', TuteurController::class);

    // Gestion des associations élèves-tuteurs
    Route::resource('gestion_associations', EleveParentController::class);

    // Gestion des enseignants
    Route::resource('gestion_enseignants', EnseignantController::class);
    Route::get('form_professeur_principal', [EnseignantController::class, 'form_professeurPrincipal'])->name('form.professeur_principal');
    Route::post('gestion_enseignants_principal', [EnseignantController::class, 'professeurPrincipal'])->name('gestion_enseignants.professeur_principal');
    Route::delete('professeur_principal/{id}', [EnseignantController::class, 'professeurPrincipalDelete'])->name('gestion_enseignants.professeur_principal_delete');

    // Gestion des frais de scolarité
    Route::resource('gestion_frais_scolarite', FraiScolariteController::class);

    // Gestion des paiements
    Route::resource('gestion_paiements', PaiementController::class);
    Route::get('gestion_paiements/{id}/print', [PaiementController::class, 'printReceipt'])->name('gestion_paiements.print');
    Route::post('gestion_paiements/{id}/solder', [PaiementController::class, 'solder'])->name('gestion_paiements.solder');
    Route::get('situation_financiere', [PaiementController::class, 'situationFinanciere'])->name('paiements.situation_financiere');

    // Gestion des matières enseignées par classe
    Route::resource('gestion_enseignement_matieres', EnseignementMatiereClasseController::class);

    // Gestion de périodes d'évaluation
    Route::resource('gestion_periodes_evaluation', PeriodeEvaluationController::class);

    // Gestion des devoirs
    Route::resource('gestion_devoirs', DevoirController::class);

    // Gestion des soumissions de devoirs
    Route::resource('gestion_soumissions', SoumissionDevoirController::class);

    // Gestion des évaluations
    Route::resource('gestion_evaluations', EvaluationController::class);

    // Gestion des notes
    Route::resource('gestion_notes', NoteController::class);

    // Gestion des bulletins
    Route::get('gestion_bulletins/generate', [BulletinController::class, 'generateForm'])->name('gestion_bulletins.generate_form');
    Route::post('gestion_bulletins/generate', [BulletinController::class, 'generate'])->name('gestion_bulletins.generate');
    Route::resource('gestion_bulletins', BulletinController::class);
    Route::get('gestion_bulletins/{id}/print', [BulletinController::class, 'print'])->name('gestion_bulletins.print');

    // Gestion des détails de bulletins
    Route::resource('gestion_detail_bulletins', DetailBulletinController::class);

    // Gestion des absences
    Route::resource('gestion_absences', AbsenceController::class);

    // Gestion des retards
    Route::resource('gestion_retards', RetardController::class);

    // Gestion des incidents disciplinaires
    Route::resource('gestion_incidents', IncidentDisciplinaireController::class);

    // Gestion des sanctions
    Route::resource('gestion_sanctions', SanctionController::class);

    // Gestion des utilisateurs
    Route::resource('gestion_utilisateurs', UserController::class);
    Route::post('gestion_utilisateurs/{id}/toggle', [UserController::class, 'toggleActif'])->name('gestion_utilisateurs.toggle');

    // Gestion des rôles
    Route::resource('gestion_roles', RoleController::class);

    // Logs système
    Route::get('gestion_logs', [SystemLogController::class, 'index'])->name('gestion_logs.index');
    Route::delete('gestion_logs', [SystemLogController::class, 'clear'])->name('gestion_logs.clear');

    // Paramètres
    Route::get('parametres/configuration', [ParametreController::class, 'configuration'])->name('parametres.configuration');
    Route::post('parametres/configuration', [ParametreController::class, 'saveConfiguration'])->name('parametres.save_configuration');
    Route::get('parametres/notifications', [ParametreController::class, 'notifications'])->name('parametres.notifications');
    Route::post('parametres/notifications', [ParametreController::class, 'saveNotifications'])->name('parametres.save_notifications');
    Route::get('parametres/sauvegardes', [ParametreController::class, 'sauvegardes'])->name('parametres.sauvegardes');
    Route::post('parametres/sauvegardes', [ParametreController::class, 'creerSauvegarde'])->name('parametres.creer_sauvegarde');
    Route::get('parametres/sauvegardes/{filename}', [ParametreController::class, 'telecharger'])->name('parametres.telecharger')->where('filename', '.+');
    Route::delete('parametres/sauvegardes/{filename}', [ParametreController::class, 'supprimerSauvegarde'])->name('parametres.supprimer')->where('filename', '.+');
    Route::post('parametres/restaurer', [ParametreController::class, 'restaurer'])->name('parametres.restaurer');

    // Emploi du temps
    Route::resource('gestion_emploi_temps', EmploiTempController::class);

    // Statistiques & Rapports
    Route::get('statistiques/effectifs',     [StatistiqueController::class, 'effectifs'])->name('statistiques.effectifs');
    Route::get('statistiques/resultats',     [StatistiqueController::class, 'resultats'])->name('statistiques.resultats');
    Route::get('statistiques/taux-reussite', [StatistiqueController::class, 'tauxReussite'])->name('statistiques.taux_reussite');
    Route::get('statistiques/finances',      [StatistiqueController::class, 'finances'])->name('statistiques.finances');
    Route::get('statistiques/assiduite',     [StatistiqueController::class, 'assiduite'])->name('statistiques.assiduite');
    Route::get('statistiques/discipline',    [StatistiqueController::class, 'discipline'])->name('statistiques.discipline');

});
