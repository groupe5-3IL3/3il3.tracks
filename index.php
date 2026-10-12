<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si le ticket secret n'existe pas encore pour cette visite, on le crée
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$loginError = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#10183b" />
    <meta name="description" content="Connectez-vous à TrackS, votre espace de suivi pédagogique." />
    <link rel="icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" href="/TrackS_back/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="assets/css/connexion.css" /> 
    <title>Connexion | TrackS</title>
</head>

<body>
    <main class="container-fluid p-0">
        <div class="row g-0 login-shell">
            <section class="col-lg-6 welcome-panel d-flex flex-column justify-content-between"
                aria-labelledby="welcome-title">
                <span class="orb orb-one" aria-hidden="true"></span>
                <span class="orb orb-two" aria-hidden="true"></span>

                <a class="brand" href="#" aria-label="TrackS, accueil">
                    <span class="brand-mark">
                        <img src="assets/images/logo.png" alt="" width="46" height="46" />
                    </span>
                    <span class="brand-name">Track<span>S</span></span>
                </a>

                <div class="welcome-content">
                    <p class="eyebrow"><span class="eyebrow-dot" aria-hidden="true"></span> L'espace de votre
                        établissement</p>
                    <h1 class="welcome-title" id="welcome-title">Le suivi qui fait avancer <span>vos études.</span></h1>
                    <p class="welcome-copy">
                        Retrouvez au même endroit le suivi des absences et la progression des cours.
                        Une vision claire, pour toute la communauté éducative.
                    </p>
                    <ul class="feature-list" aria-label="Fonctionnalités de TrackS">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M8 3v3m8-3v3M4.5 9.5h15M6 5h12a2 2 0 0 1 2 2v12H4V7a2 2 0 0 1 2-2Z"
                                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="m9 15 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Suivi des absences
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 19V5m0 14h16" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" />
                                <path d="m7 15 4-4 3 2 5-6" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16 7h3v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Progression des cours
                        </li>
                    </ul>
                </div>

                <div class="panel-footer">
                    <span>© 2025 TrackS</span>
                    <span>Pour un suivi de proximité</span>
                </div>
            </section>

            <section class="col-lg-6 login-panel" aria-labelledby="login-title">
                <div class="login-content">
                    <p class="login-kicker">Bon retour parmi nous</p>
                    <h2 class="login-title" id="login-title">Connectez-vous</h2>
                    <p class="login-description">Accédez à votre espace avec les identifiants de votre établissement.
                    </p>

                    <?php if (is_string($loginError) && $loginError !== ''): ?>
                    <p class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <?php endif; ?>

                    <form action="Controller/connectController.php" method="post" autocomplete="on">
                        <!-- Cette ligne insère le ticket secret invisible pour l'utilisateur -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="field-group">
                            <label for="profil" class="form-label">Votre profil</label>
                            <select name="profil" id="profil" class="form-select" required>
                                <option value="" selected disabled>Choisissez votre profil</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="enseignant">Enseignant</option>
                                <option value="scolarite">Scolarité</option>
                                <option value="chef_departement">Chef de département</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="identifiant" class="form-label">E-mail ou matricule</label>
                            <input type="text" name="identifiant" id="identifiant" class="form-control"
                                placeholder="nom@etablissement.fr ou votre matricule" autocomplete="username"
                                required />
                        </div>

                        <div class="field-group mb-0">
                            <label for="mot-de-passe" class="form-label">Mot de passe</label>
                            <input type="password" name="mot_de_passe" id="mot-de-passe" class="form-control"
                                placeholder="Saisissez votre mot de passe" autocomplete="current-password" required />
                        </div>

                        <button type="submit" class="btn btn-primary w-100 submit-button">
                            Se connecter
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>

                    <p class="secure-note">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.7" />
                            <path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3" stroke="currentColor" stroke-width="1.7"
                                stroke-linecap="round" />
                        </svg>
                        Vos informations restent confidentielles
                    </p>
                    <p class="login-footer">Besoin d'aide ? Contactez l'administration de votre établissement.</p>
                </div>
            </section>
        </div>
    </main>
    <script src="/TrackS_back/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>