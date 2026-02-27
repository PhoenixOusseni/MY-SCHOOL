<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">

                    <a class="nav-link collapsed mt-3" href="{{ route('dashboard') }}">
                        <div class="nav-link-icon"><i data-feather="home"></i></div>
                        Tableau de bord
                    </a>

                    <div class="sidenav-menu-heading">GESTION ACADÉMIQUE</div>

                    <!-- Établissement -->
                    <a class="nav-link collapsed" href="{{ route('gestion_etablissements.index') }}">
                        <div class="nav-link-icon"><i data-feather="briefcase"></i></div>
                        Établissement
                    </a>

                    <!-- Années Scolaires & Niveaux -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseAnnees" aria-expanded="false" aria-controls="collapseAnnees">
                        <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                        Années & Niveaux
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAnnees" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_annees_scolaires.index') }}">Années scolaires</a>
                            <a class="nav-link" href="{{ route('gestion_niveaux.index') }}">Niveaux</a>
                            <a class="nav-link" href="{{ route('gestion_periodes_evaluation.index') }}">Périodes d'évaluation</a>
                        </nav>
                    </div>

                    <!-- Classes -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseClasses" aria-expanded="false" aria-controls="collapseClasses">
                        <div class="nav-link-icon"><i data-feather="grid"></i></div>
                        Classes
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseClasses" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_classes.index') }}">Gestion des classes</a>
                            <a class="nav-link" href="#">Professeurs principaux</a>
                        </nav>
                    </div>

                    <!-- Élèves -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseEleves" aria-expanded="false" aria-controls="collapseEleves">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Élèves
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEleves" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_eleves.index') }}">Liste des élèves</a>
                            <a class="nav-link" href="{{ route('gestion_inscriptions.index') }}">Inscriptions</a>
                            <a class="nav-link" href="#">Dossiers élèves</a>
                        </nav>
                    </div>

                    <!-- Enseignants -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseEnseignants" aria-expanded="false" aria-controls="collapseEnseignants">
                        <div class="nav-link-icon"><i data-feather="user-check"></i></div>
                        Enseignants
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEnseignants" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_enseignants.index') }}">Liste des enseignants</a>
                            <a class="nav-link" href="{{ route('form.professeur_principal') }}">Affectations</a>
                            <a class="nav-link" href="{{ route('gestion_emploi_temps.index') }}">Emploi du temps</a>
                        </nav>
                    </div>

                    <!-- Parents/Tuteurs -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseParents" aria-expanded="false" aria-controls="collapseParents">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Parents/Tuteurs
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseParents" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_tuteurs.index') }}">Liste des parents</a>
                            <a class="nav-link" href="{{ route('gestion_associations.index') }}">Associations élèves</a>
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">PÉDAGOGIE</div>

                    <!-- Matières -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseMatieres" aria-expanded="false" aria-controls="collapseMatieres">
                        <div class="nav-link-icon"><i data-feather="book"></i></div>
                        Matières
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseMatieres" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_matieres.index') }}">Liste des matières</a>
                            <a class="nav-link" href="{{ route('gestion_enseignement_matieres.index') }}">Matières enseignées</a>
                        </nav>
                    </div>

                    <!-- Emploi du temps -->
                    <a class="nav-link collapsed" href="{{ route('gestion_emploi_temps.index') }}">
                        <div class="nav-link-icon"><i data-feather="clock"></i></div>
                        Emploi du temps
                    </a>

                    <!-- Devoirs -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseDevoirs" aria-expanded="false" aria-controls="collapseDevoirs">
                        <div class="nav-link-icon"><i data-feather="edit"></i></div>
                        Devoirs
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDevoirs" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_devoirs.create') }}">Créer un devoir</a>
                            <a class="nav-link" href="{{ route('gestion_devoirs.index') }}">Liste des devoirs</a>
                            {{-- <a class="nav-link" href="{{ route('gestion_soumissions.index') }}">Soumissions</a> --}}
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">ÉVALUATIONS</div>

                    <!-- Évaluations/Examens -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseEvaluations" aria-expanded="false" aria-controls="collapseEvaluations">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Évaluations
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEvaluations" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_evaluations.create') }}">Créer évaluation</a>
                            <a class="nav-link" href="{{ route('gestion_evaluations.index') }}">Liste des évaluations</a>
                            <a class="nav-link" href="#">Calendrier examens</a>
                        </nav>
                    </div>

                    <!-- Notes -->
                    {{-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseNotes" aria-expanded="false" aria-controls="collapseNotes">
                        <div class="nav-link-icon"><i data-feather="award"></i></div>
                        Notes
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseNotes" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_notes.create') }}">Saisie des notes</a>
                            <a class="nav-link" href="#">Moyennes matières</a>
                            <a class="nav-link" href="#">Consultation notes</a>
                        </nav>
                    </div> --}}

                    <!-- Bulletins -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseBulletins" aria-expanded="false" aria-controls="collapseBulletins">
                        <div class="nav-link-icon"><i data-feather="printer"></i></div>
                        Bulletins
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseBulletins" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_bulletins.create') }}">Générer bulletins</a>
                            <a class="nav-link" href="{{ route('gestion_bulletins.index') }}">Consultation</a>
                            <a class="nav-link" href="{{ route('gestion_detail_bulletins.index') }}">Détails du bulletin</a>
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">VIE SCOLAIRE</div>

                    <!-- Absences & Retards -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseAssiduite" aria-expanded="false" aria-controls="collapseAssiduite">
                        <div class="nav-link-icon"><i data-feather="user-x"></i></div>
                        Assiduité
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAssiduite" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_absences.index') }}">Absences</a>
                            <a class="nav-link" href="{{ route('gestion_retards.index') }}">Retards</a>
                            <a class="nav-link" href="#">Justifications</a>
                            <a class="nav-link" href="#">Rapports assiduité</a>
                        </nav>
                    </div>

                    <!-- Discipline -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseDiscipline" aria-expanded="false" aria-controls="collapseDiscipline">
                        <div class="nav-link-icon"><i data-feather="alert-triangle"></i></div>
                        Discipline
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDiscipline" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_incidents.index') }}">Incidents</a>
                            <a class="nav-link" href="{{ route('gestion_sanctions.index') }}">Sanctions</a>
                            <a class="nav-link" href="#">Rapports discipline</a>
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">FINANCES</div>

                    <!-- Frais & Paiements -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseFinances" aria-expanded="false" aria-controls="collapseFinances">
                        <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                        Scolarité
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseFinances" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_frais_scolarite.index') }}">Types de frais</a>
                            <a class="nav-link" href="{{ route('gestion_paiements.create') }}">Enregistrer paiement</a>
                            <a class="nav-link" href="{{ route('gestion_paiements.index') }}">Historique paiements</a>
                            <a class="nav-link" href="{{ route('paiements.situation_financiere') }}">Situation financière</a>
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">RAPPORTS</div>

                    <!-- Statistiques -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseStatistiques" aria-expanded="false" aria-controls="collapseStatistiques">
                        <div class="nav-link-icon"><i data-feather="bar-chart-2"></i></div>
                        Statistiques & Rapports
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseStatistiques" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('statistiques.effectifs') }}">Effectifs</a>
                            <a class="nav-link" href="{{ route('statistiques.resultats') }}">Résultats scolaires</a>
                            <a class="nav-link" href="{{ route('statistiques.taux_reussite') }}">Taux de réussite</a>
                            <a class="nav-link" href="{{ route('statistiques.finances') }}">États financiers</a>
                            <a class="nav-link" href="{{ route('statistiques.assiduite') }}">Rapport assiduité</a>
                            <a class="nav-link" href="{{ route('statistiques.discipline') }}">Rapport discipline</a>
                        </nav>
                    </div>

                    <div class="sidenav-menu-heading">SYSTÈME</div>

                    <!-- Administration -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                        <div class="nav-link-icon"><i data-feather="shield"></i></div>
                        Administration
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAdmin" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('gestion_utilisateurs.index') }}">Utilisateurs</a>
                            <a class="nav-link" href="{{ route('gestion_roles.index') }}">Rôles & Permissions</a>
                            <a class="nav-link" href="{{ route('gestion_logs.index') }}">Logs système</a>
                        </nav>
                    </div>

                    <!-- Paramètres -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseParametres" aria-expanded="false" aria-controls="collapseParametres">
                        <div class="nav-link-icon"><i data-feather="settings"></i></div>
                        Paramètres
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseParametres" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('parametres.configuration') }}">Configuration générale</a>
                            <a class="nav-link" href="{{ route('parametres.notifications') }}">Notifications</a>
                            <a class="nav-link" href="{{ route('parametres.sauvegardes') }}">Sauvegarde &amp; Restauration</a>
                        </nav>
                    </div>

                </div>
            </div>
            <div class="sidenav-footer" style="background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);">
                <div class="sidenav-footer-content text-center">
                    <div class="sidenav-footer-subtitle text-white">Utilisateur connecté(e):</div>
                    <div class="sidenav-footer-title text-white">{{ Auth::user()->login }}</div>
                </div>
            </div>
        </nav>
    </div>
