<?php
session_start();

// On vérifie si l'utilisateur est connecté en vérifiant la présence de l'ID utilisateur dans la session
if (!isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}   

// On récupère les informations de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$user_nom = $_SESSION['user_nom'];
$user_prenom = $_SESSION['user_prenom'];
$user_email = $_SESSION['user_email'];

require_once '../config/db.php';

$notificationDefaults = [
    'notify_absence' => 1,
    'notify_syllabus' => 0,
    'notify_justif' => 1,
];
foreach ($notificationDefaults as $key => $value) {
    $_SESSION[$key] = $value;
}

try {
    $pdo = tracksDatabase();
    $stmt = $pdo->prepare(
        'SELECT NotifyAbsence_set, NotifySyllabus_set, NotifyJustif_set
         FROM parametre
         WHERE UtilisateurID = :user_id
         LIMIT 1'
    );
    $stmt->execute(['user_id' => $user_id]);
    $notificationSettings = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($notificationSettings) {
        $notificationFields = [
            'notify_absence' => 'NotifyAbsence_set',
            'notify_syllabus' => 'NotifySyllabus_set',
            'notify_justif' => 'NotifyJustif_set',
        ];
        foreach ($notificationFields as $sessionKey => $field) {
            $value = $notificationSettings[$field];
            $_SESSION[$sessionKey] = $value === "\x01" || $value === 1 || $value === '1' ? 1 : 0;
        }
    }
} catch (PDOException $e) {
    $_SESSION['settings_error'] = 'Impossible de charger vos préférences de notification.';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#10183b" />
    <meta name="description" content="Connectez-vous à TrackS, votre espace de suivi pédagogique." />
    <link rel="icon" href="../assets/images/favicon.ico" />    
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <title>Parametre | TrackS</title>
</head>
<body class="container d-flex flex-column min-vh-100 bg-light">
    
    <?php include 'header.php'; ?>

    <main class="flex-grow-1">
           <main class="flex-grow-1 my-4">
        <!-- Titre de la page en harmonie avec le tableau de bord -->
        <div class="mb-4">
            <h2 class="text-primary fw-bold m-0"><i class="bi bi-sliders2-vertical me-2"></i>Espace Configuration</h2>
            <p class="text-muted small">Personnalisez votre expérience sur l'application TrackS selon vos besoins métier.</p>
        </div>

        <!-- ZONE D'AFFICHAGE DES RETOURS DE SOUMISSION (Messages Flash de Session) -->
        <?php if (isset($_SESSION['settings_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['settings_error']; unset($_SESSION['settings_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['settings_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['settings_success']; unset($_SESSION['settings_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- ONGLET LATÉRAL DE NAVIGATION (Menu Bootstrap 5 Pilotes) -->
            <aside class="col-md-4 col-lg-3 mb-4">
                <div class="card border border-1 shadow-sm p-3 rounded-3 bg-white">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active text-start mb-2 py-2.5" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="true">
                            <i class="bi bi-person-bounding-box me-2"></i>Mon Profil
                        </button>
                        <button class="nav-link text-start mb-2 py-2.5" id="v-pills-preferences-tab" data-bs-toggle="pill" data-bs-target="#v-pills-preferences" type="button" role="tab" aria-controls="v-pills-preferences" aria-selected="false">
                            <i class="bi bi-gear-wide-toggle me-2"></i>Préférences
                        </button>
                        <button class="nav-link text-start mb-2 py-2.5" id="v-pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#v-pills-notifications" type="button" role="tab" aria-controls="v-pills-notifications" aria-selected="false">
                            <i class="bi bi-bell-badge me-2"></i>Alertes & Notifications
                        </button>
                        <button class="nav-link text-start py-2.5" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="false">
                            <i class="bi bi-lock-fill me-2"></i>Sécurité du Compte
                        </button>
                    </div>
                </div>
            </aside>

            <!-- ZONE DE CONTENU DYNAMIQUE DES PANNEAUX -->
            <div class="col-md-8 col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <!-- PANNEAU 1 : Informations Profil (Aligné avec la structure SQL utilisateur) -->
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="card border border-1 shadow-sm p-4 mb-4 rounded-3 bg-white">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2 text-primary"><i class="bi bi-person-check me-2"></i>Informations d'identité</h5>
                            <form action="../Controller/profil_settingController.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Nom de famille</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($_SESSION['user_nom'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Prénom</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($_SESSION['user_prenom'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Adresse Électronique</label>
                                        <input type="email" name="Email_user" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Rôle Applicatif</label>
                                        <span class="form-control bg-light text-capitalize fw-semibold text-dark">
                                            <i class="bi bi-shield-fill-check text-primary me-2"></i><?php echo str_replace('_', ' ', $user_role); ?>
                                        </span>
                                    </div>

                                    <!-- Affichage conditionnel du Matricule (uniquement si étudiant ou scolarité ayant un matricule) -->
                                    <?php if (!empty($_SESSION['matricule_etu'])): ?>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Matricule Étudiant</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($_SESSION['matricule_etu']); ?>" readonly>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Affichage conditionnel de la Spécialité (uniquement pour les enseignants) -->
                                    <?php if (!empty($_SESSION['specialite_ens'])): ?>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Spécialité Enseignement</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($_SESSION['specialite_ens']); ?>" readonly>
                                    </div>
                                    <?php endif; ?>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Mettre à jour mon adresse e-mail</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- PANNEAU 2 : Préférences Spécifiques au Métier/Role -->
                    <div class="tab-pane fade" id="v-pills-preferences" role="tabpanel" aria-labelledby="v-pills-preferences-tab">
                        <div class="card border border-1 shadow-sm p-4 mb-4 rounded-3 bg-white">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2 text-primary"><i class="bi bi-eye me-2"></i>Affichage et Année Académique</h5>
                            <form action="../Controller/preference_settingController.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Période académique par défaut</label>
                                        <select name="annee_academique" class="form-select">
                                            <option value="2026-2027" <?php echo (isset($_SESSION['pref_annee_academique']) && $_SESSION['pref_annee_academique'] === '2026-2027') ? 'selected' : ''; ?>>Année Universitaire 2026-2027</option>
                                            <option value="2025-2026" <?php echo (isset($_SESSION['pref_annee_academique']) && $_SESSION['pref_annee_academique'] === '2025-2026') ? 'selected' : ''; ?>>Année Universitaire 2025-2026</option>
                                        </select>
                                    </div>
                                    
                                    <?php if ($user_role === 'enseignant' || $user_role === 'scolarite'): ?>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Rendu de l'appel de cours</label>
                                        <select name="rendu_appel" class="form-select">
                                            <option value="liste" <?php echo (isset($_SESSION['pref_rendu_appel']) && $_SESSION['pref_rendu_appel'] === 'liste') ? 'selected' : ''; ?>>Liste textuelle classique</option>
                                            <option value="trombi" <?php echo (isset($_SESSION['pref_rendu_appel']) && $_SESSION['pref_rendu_appel'] === 'trombi') ? 'selected' : ''; ?>>Trombinoscope en mosaïque</option>
                                        </select>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Format favori pour l'export des présences</label>
                                        <select name="format_export" class="form-select">
                                            <option value="pdf" <?php echo (isset($_SESSION['pref_format_export']) && $_SESSION['pref_format_export'] === 'pdf') ? 'selected' : ''; ?>>Document PDF sécurisé (.pdf)</option>
                                            <option value="excel" <?php echo (isset($_SESSION['pref_format_export']) && $_SESSION['pref_format_export'] === 'excel') ? 'selected' : ''; ?>>Tableau Microsoft Excel (.xlsx)</option>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Valider les préférences</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- PANNEAU 3 : Alertes et notifications système -->
                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                        <div class="card border border-1 shadow-sm p-4 mb-4 rounded-3 bg-white">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2 text-primary"><i class="bi bi-sliders me-2"></i>Rapports et alertes système</h5>
                            <form action="../Controller/notification_settingController.php" method="POST">
                                
                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">Rappels d'absences instantanés</h6>
                                        <small class="text-muted">Alerte automatique dès qu'une modification d'appel ou de présence survient.</small>
                                    </div>
                                    <div class="form-check form-switch fs-5">
                                        <input class="form-check-input" type="checkbox" name="notify_absence" value="1" role="switch" id="notifyAbsence" 
                                            <?php echo (($_SESSION['notify_absence'] ?? 0) === 1) ? 'checked' : ''; ?>>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">Suivi d'avancement des volumes horaires</h6>
                                        <small class="text-muted">Recevoir chaque fin de semaine l'état de complétion du syllabus du département.</small>
                                    </div>
                                    <div class="form-check form-switch fs-5">
                                        <input class="form-check-input" type="checkbox" name="notify_syllabus" value="1" role="switch" id="notifySyllabus"
                                            <?php echo (($_SESSION['notify_syllabus'] ?? 0) === 1) ? 'checked' : ''; ?>>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-dark">Suivi des validations administratives</h6>
                                        <small class="text-muted">Être notifié dès qu'un justificatif téléversé change de statut (Validé / Rejeté).</small>
                                    </div>
                                    <div class="form-check form-switch fs-5">
                                        <input class="form-check-input" type="checkbox" name="notify_justif" value="1" role="switch" id="notifyJustif" 
                                            <?php echo (($_SESSION['notify_justif'] ?? 0) === 1) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Enregistrer les alertes</button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- PANNEAU 4 : Sécurité du Compte (Changement de mot de passe) -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                        <div class="card border border-1 shadow-sm p-4 mb-4 rounded-3 bg-white">
                            <h5 class="fw-bold text-danger mb-4 border-bottom pb-2"><i class="bi bi-shield-lock me-2"></i>Sécuriser l'accès</h5>
                            <form action="../Controller/security_settingController.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold">Mot de passe en cours d'utilisation</label>
                                        <input type="password" name="old_password" class="form-control" placeholder="••••••••" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Nouveau mot de passe secret</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="8 caractères minimum" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Confirmer le nouveau mot de passe</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Ressaisir à l'identique" required>
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-danger px-4 shadow-sm">Mettre à jour le mot de passe</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- Fin de .tab-content -->
            </div> <!-- Fin de la colonne de contenu -->
        </div> <!-- Fin de la ligne .row -->
    </main>


    </main>

    <?php include 'footer.php'; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
