<?php
// View/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération sécurisée du rôle de l'utilisateur 
$user_role = $_SESSION['user_role'] ?? '';
?>

<header class="bg-white border-bottom py-3 px-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Logo / Nom de l'application -->
            <div class="col-md-4 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                    <i class="bi bi-activity text-primary fs-2"></i>
                    <h1 class="fw-bold text-primary m-0" style="font-size: 1.85rem; letter-spacing: -0.03em; font-weight: 850;">TrackS</h1>
                </div>
            </div>
            <!-- Logo de l'établissement -->
            <div class="col-md-4 text-center my-3 my-md-0">
                <img src="../assets/images/logo.png" alt="Logo de TrackS" class="img-fluid" style="max-height: 45px;" onerror="this.src='https://unsplash.com'"/>
            </div>
            <!-- Slogan Institutionnel -->
            <div class="col-md-4 text-center text-md-end d-none d-md-block">
                <span class="fs-6 text-muted fw-normal fst-italic">
                    <i class="bi bi-people-fill text-secondary me-1"></i> Pour un suivi de proximité
                </span>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom sticky-top px-3 py-2">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-dark fw-bold fs-6" href="dashboard.php">
            <i class="bi bi-grid-1x2-fill text-primary"></i> Menu Principal
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#trackSNavbarContent" aria-controls="trackSNavbarContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="trackSNavbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-1">

                <li class="nav-item">
                    <a class="nav-link px-3 rounded hover-nav-link" href="dashboard.php">
                        <i class="bi bi-house-door me-1"></i>Accueil
                    </a>
                </li>

                <!-- ACCÈS EXCLUSIF : SCOLARITÉ -->
                <?php if ($user_role === 'scolarite'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded hover-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-diagram-3 me-1"></i>Structures
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="filieres.php"><i class="bi bi-folder2-open me-2 text-muted"></i>Gestion des Filières</a></li>
                            <li><a class="dropdown-item py-2" href="ue.php"><i class="bi bi-journal-text me-2 text-muted"></i>Gestion des UE</a></li>
                            <li><a class="dropdown-item py-2" href="syllabus.php"><i class="bi bi-book me-2 text-muted"></i>Syllabus &amp; Volumes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded hover-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-people me-1"></i>Utilisateurs
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="etudiant.php"><i class="bi bi-mortarboard me-2 text-muted"></i>Inscriptions Étudiants</a></li>
                            <li><hr class="dropdown-divider my-1" /></li>
                            <li><a class="dropdown-item py-2" href="enseignants_gestion.php"><i class="bi bi-person-badge me-2 text-muted"></i>Enregistrer &amp; Gérer Enseignants</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded hover-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar-check me-1"></i>Absences
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="validations.php"><i class="bi bi-file-earmark-check me-2 text-muted"></i>Validation Justificatifs</a></li>
                            <li><a class="dropdown-item py-2" href="statistiques_globales.php"><i class="bi bi-graph-up me-2 text-muted"></i>Suivi de l'Absentéisme</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ACCÈS EXCLUSIF : CHEF DE DÉPARTEMENT -->
                <?php if ($user_role === 'chef_departement'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded hover-nav-link fw-semibold text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-shield-lock text-warning me-1"></i>Supervision
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="audit.php"><i class="bi bi-fingerprint me-2 text-muted"></i>Journal d'Audit</a></li>
                            <li><hr class="dropdown-divider my-1" /></li>
                            <li><a class="dropdown-item py-2" href="convocations.php"><i class="bi bi-envelope-exclamation me-2 text-muted"></i>Émettre une Convocation</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded hover-nav-link" href="progression_departement.php">
                            <i class="bi bi-bar-chart-line me-1"></i>Progression des Cours
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ACCÈS EXCLUSIF : ENSEIGNANT -->
                <?php if ($user_role === 'enseignant'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded fw-bold text-success hover-nav-link" href="appel.php">
                            <i class="bi bi-postcard-fill me-1"></i>Faire l'Appel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded hover-nav-link" href="progression_syllabus.php">
                            <i class="bi bi-journal-check me-1"></i>Avancement du Syllabus
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ACCÈS EXCLUSIF : ÉTUDIANT -->
                <?php if ($user_role === 'etudiant'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded hover-nav-link" href="mes_absences.php">
                            <i class="bi bi-calendar-x me-1"></i>Mes Absences
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded hover-nav-link" href="justifier.php">
                            <i class="bi bi-cloud-upload me-1"></i>Déposer un Justificatif
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ACCÈS COMMUN : RAPPORTS ET EXPORTS -->
                <?php if (in_array($user_role, ['scolarite', 'chef_departement', 'enseignant'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded hover-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-printer me-1"></i>Éditions
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="export_pdf.php"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export Relevé PDF</a></li>
                            <?php if ($user_role === 'chef_departement'): ?>
                                <li><a class="dropdown-item py-2" href="export_word.php"><i class="bi bi-file-earmark-word text-primary me-2"></i>Rapport d'Avancement Word</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ACCÈS UNIVERSEL : PARAMÈTRES -->
                <li class="nav-item">
                    <a class="nav-link px-3 rounded hover-nav-link" href="parametres.php">
                        <i class="bi bi-sliders me-1"></i>Paramètres
                    </a>
                </li>
            </ul>

                <!-- Actions de Session Droite -->
                <div class="d-flex align-items-center gap-3 ms-lg-3 mt-3 mt-lg-0 border-top pt-3 pt-lg-0 border-lg-0">
                    <a class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 px-3 py-2 rounded-pill shadow-sm" 
                    href="../Controller/logoutController.php" 
                    onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter de votre espace TrackS ?');">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </div>
        </div>
    </div>
</nav>