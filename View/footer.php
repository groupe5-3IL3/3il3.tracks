<?php
// View/footer.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_role = $_SESSION['user_role'] ?? 'etudiant';
?>
<footer class="mt-auto pt-5 pb-4 bg-white border-top text-dark shadow-sm rounded-top px-4">
    <div class="container-fluid">
        <div class="row g-4 mb-4">
            
            <!-- Colonne 1 : Identité et Description de l'Application -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-primary mb-3">TrackS</h5>
                <p class="text-muted small lh-lg">
                    Plateforme centralisée de suivi de l'assiduité des étudiants et de progression des syllabus. Conçue pour maintenir un suivi pédagogique de proximité entre l'administration, les enseignants et les apprenants.
                </p>
                <div class="d-flex gap-3 text-muted align-items-center">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-reset hover-icon" title="Suivez-nous sur Facebook">
                        <i class="bi bi-facebook fs-5 hover-icon"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="text-reset hover-icon" title="Suivez-nous sur Twitter">
                        <i class="bi bi-twitter-x fs-5 hover-icon"></i>
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-reset hover-icon" title="Suivez-nous sur LinkedIn">
                        <i class="bi bi-linkedin fs-5 hover-icon"></i>
                    </a>
                    <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="text-reset hover-icon" title="Voir le code source sur GitHub">
                        <i class="bi bi-github fs-5 hover-icon"></i>
                    </a>
                </div>
            </div>


            <!-- Colonne 2 : Liens Institutionnels Dynamiques selon le Profil -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-dark text-uppercase mb-3 small tracking-wider">Liens Rapides</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><a href="dashboard.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Tableau de bord</a></li>
                    
                    <?php if ($user_role === 'scolarite'): ?>
                        <li class="mb-2"><a href="validations.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Validation Justificatifs</a></li>
                        <li class="mb-2"><a href="etudiants.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Inscriptions Étudiants</a></li>
                    <?php elseif ($user_role === 'chef_departement'): ?>
                        <li class="mb-2"><a href="audit.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Journal d'Audit</a></li>
                        <li class="mb-2"><a href="convocations.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Convocations</a></li>
                    <?php elseif ($user_role === 'enseignant'): ?>
                        <li class="mb-2"><a href="appel.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Faire l'Appel</a></li>
                    <?php elseif ($user_role === 'etudiant'): ?>
                        <li class="mb-2"><a href="mes_absences.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Mes Absences</a></li>
                    <?php endif; ?>
                    
                    <li><a href="parametres.php" class="text-reset text-decoration-none hover-link"><i class="bi bi-chevron-right me-1"></i>Paramètres du Compte</a></li>
                </ul>
            </div>

            <!-- Colonne 3 : Support & Assistance Technique de l'Établissement -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-dark text-uppercase mb-3 small tracking-wider">Support Technique</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-primary"></i> support.tracks@3il3.fr
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-primary"></i> +33 (0)5 55 55 55 55
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-question-circle text-primary"></i> Centre d'Aide & FAQ
                    </li>
                </ul>
            </div>

            <!-- Colonne 4 : État de la Sécurité et État de la Session -->
            <div class="col-lg-2 col-md-6 text-md-start">
                <h6 class="fw-bold text-dark text-uppercase mb-3 small tracking-wider">Sécurité</h6>
                <div class="mb-2">
                    <span class="badge bg-light text-success border border-success-subtle px-2 py-1 small rounded-pill d-inline-flex align-items-center gap-1">
                        <span class="spinner-grow spinner-grow-sm text-success" style="width: 8px; height: 8px;"></span>
                        Session Protégée
                    </span>
                </div>
                <div>
                    <span class="badge bg-light text-muted border px-2 py-1 small rounded-pill text-capitalize">
                        Rôle : <?= htmlspecialchars($user_role); ?>
                    </span>
                </div>
            </div>

        </div>

        <hr class="border-secondary-subtle my-4">

        <!-- Ligne Finale : Mentions Légales et Droits -->
        <div class="row align-items-center justify-content-between text-muted small">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                © <?= date('Y'); ?> TrackS — Plateforme d'Assiduité Pédagogique. Tous droits réservés.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-reset text-decoration-none me-3 hover-link">Mentions Légales</a>
                <a href="#" class="text-reset text-decoration-none hover-link">Politique de Confidentialité</a>
            </div>
        </div>
    </div>
</footer>
