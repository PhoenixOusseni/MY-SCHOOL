<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - School Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @include('pages.auth.style.login_css')
</head>

<body>
    <div class="login-container">

        <!-- Section Formulaire de connexion -->
        <div class="login-form-section">
            <div class="login-form-wrapper">

                <div class="logo-section">
                    <div class="logo-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="logo-text">SCHOOL MANAGER</div>
                    <div class="logo-subtext">Connectez-vous à votre espace</div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('connexion') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="login" class="form-label">Identifiant</label>
                        <div class="input-icon-wrapper">
                            <input type="text" class="form-control @error('login') is-invalid @enderror"
                                id="login" name="login" placeholder="Votre identifiant"
                                value="super.admin" required autocomplete="username">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-icon-wrapper position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" value="password"
                                required autocomplete="current-password">
                            <i class="bi bi-lock input-icon"></i>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </button>
                </form>

                <div class="divider">
                    <span>ou</span>
                </div>

                <div class="register-link">
                    Pas encore de compte ? <a href="">Créer un compte</a>
                </div>

            </div>
        </div>

        <!-- Section Informations -->
        <div class="register-info-section">
            <div class="info-content">

                <div class="info-badge">
                    <span class="dot"></span>
                    Plateforme active et sécurisée
                </div>

                <h1 class="info-title">
                    La gestion scolaire <span class="info-highlight">réinventée</span> pour les établissements modernes
                </h1>
                <p class="info-subtitle">
                    Simplifiez l'administration, suivez les performances et communiquez efficacement avec toute la communauté éducative.
                </p>

                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-number">1K+</div>
                        <div class="stat-label">Établissements</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Élèves gérés</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">99%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                </div>

                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div class="feature-title">Rapide à déployer</div>
                        <div class="feature-desc">Opérationnel en moins de 5 minutes</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <div class="feature-title">100% Sécurisé</div>
                        <div class="feature-desc">Données chiffrées et protégées</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="feature-title">Tableaux de bord</div>
                        <div class="feature-desc">Statistiques en temps réel</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div class="feature-title">Support dédié</div>
                        <div class="feature-desc">Assistance disponible 24/7</div>
                    </div>
                </div>

                <div class="benefits-section">
                    <h3 class="benefits-title">Tout ce dont vous avez besoin</h3>
                    <div class="benefit-list">
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Gestion des élèves & inscriptions</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Notes et bulletins automatisés</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Suivi des absences & discipline</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Emplois du temps & planning</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Communication parents-école</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Gestion financière & frais</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</body>

</html>
