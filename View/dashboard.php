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
    <title>Tableau de Bord | TrackS</title>
</head>
<body class="container d-flex flex-column min-vh-100 bg-light">
    
    <?php include 'header.php'; ?>

    <main class="flex-grow-1">
        
        <!-- SECTION 1 : Message de Bienvenue et informations dynamiques de Session -->
        <section class="row mt-4 border border-1 rounded shadow-sm bg-white p-4 align-items-center zoom-hover">
            <!-- Colonne Gauche : Image de présentation (4/12 de la largeur) -->
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <img src="../assets/images/suivi_etudiants.jpeg" alt="Image de Tracks" class="img-fluid rounded-4 shadow-sm" onerror="this.src='https://unsplash.com'"/>
            </div>
            
            <!-- Colonne Droite : Contenu contextuel dynamique (8/12 de la largeur) -->
            <div class="col-md-8">
                <h2 class="text-primary fw-bold mb-3">Bienvenue sur TrackS</h2>
                
                <!-- Zone d'affichage contextuelle complète pour les 4 profils distincts -->
                <?php if ($user_role === 'scolarite'): ?>
                    <div class="alert alert-primary py-3 px-4 border-0 rounded-3 shadow-sm animate-fade-up m-0">
                        <h4 class="alert-heading fw-bold fs-5 mb-2"><i class="bi bi-shield-check me-2"></i>Espace Scolarité Actif</h4>
                        <p class="text-dark mb-0">
                            Bienvenue. Vous êtes connecté avec les droits d'administration globale. Vous pouvez suivre l'assiduité générale de l'établissement, analyser les statistiques de présence et valider les pièces justificatives téléversées par les étudiants.
                        </p>
                    </div>
                <?php elseif ($user_role === 'chef_departement'): ?>
                    <div class="alert alert-warning py-3 px-4 border-0 rounded-3 shadow-sm animate-fade-up m-0">
                        <h4 class="alert-heading fw-bold fs-5 mb-2"><i class="bi bi-shield-lock-fill me-2"></i>Espace Direction & Supervision</h4>
                        <p class="text-dark mb-0">
                            Session superviseur ouverte. Vous disposez des droits d'accès pour <b>consulter le journal d'audit</b> complet de votre département et <b>émettre des convocations officielles</b> à l'attention des étudiants en situation de décrochage.
                        </p>
                    </div>
                <?php elseif ($user_role === 'enseignant'): ?>
                    <div class="alert alert-success py-3 px-4 border-0 rounded-3 shadow-sm animate-fade-up m-0">
                        <h4 class="alert-heading fw-bold fs-5 mb-2"><i class="bi bi-person-workspace me-2"></i>Espace Enseignant</h4>
                        <p class="text-dark mb-0">
                            Session pédagogique active. Accédez immédiatement à vos Unités d'Enseignement (UE) pour <b>remplir la feuille d'appel en ligne</b> de votre séance actuelle ou valider la progression des chapitres de votre syllabus.
                        </p>
                    </div>
                <?php elseif ($user_role === 'etudiant'): ?>
                    <div class="alert alert-info py-3 px-4 border-0 rounded-3 shadow-sm animate-fade-up m-0">
                        <h4 class="alert-heading fw-bold fs-5 mb-2"><i class="bi bi-mortarboard-fill me-2"></i>Espace Étudiant Pouvant Suivre</h4>
                        <p class="text-dark mb-0">
                            Bienvenue dans votre espace d'études personnel. Retrouvez ici la progression en temps réel de vos enseignements, consultez votre taux d'absentéisme par matière et déposez vos justificatifs médicaux ou administratifs en cas d'absence.
                        </p>
                    </div>
                <?php else: ?>
                    <p class="text-muted lh-lg mb-0">
                        TrackS vous permet de suivre vos étudiants, gérer vos cours, analyser la progression des syllabus par rapport aux volumes horaires prévus, et piloter les performances de l'établissement en toute simplicité et à proximité.
                    </p>
                <?php endif; ?>
            </div>
        </section>

        <!-- SECTION 2 : Nos Cours avec Défilement Automatique Infini Continu -->
        <section class="mt-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark mb-1">Nos Cours & Syllabus</h2>
                <p class="text-muted">Discover the classes taught and track their real-time progression.</p>
            </div>
            
            <div class="slider-container">
                <div class="slider-track">
                    
                    <!-- Groupe de matières réelles (Basé sur vos tables MySQL filiere2 / ue2) -->
                    <div class="card p-4 text-center shadow-sm course-card-custom">
                        <i class="bi bi-code-slash display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Développement Web</h4>
                        <p class="text-muted small">HTML, CSS, JavaScript et Architecture PHP MVC pour créer des applications sécurisées.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>
                    
                    <div class="card p-4 text-center shadow-sm course-card-custom">
                        <i class="bi bi-database display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Bases de Données Relational</h4>
                        <p class="text-muted small">Administration Oracle 19c, SQL, requêtes PL/SQL avancées et modélisation Visual Paradigm.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>
                    
                    <div class="card p-4 text-center shadow-sm course-card-custom">
                        <i class="bi bi-cpu display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Intelligence Artificielle</h4>
                        <p class="text-muted small">Initiation aux architectures de Machine Learning, Big Data et scripts de traitement Python.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>

                    <div class="card p-4 text-center shadow-sm course-card-custom">
                        <i class="bi bi-diagram-3 display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Génie Logiciel & UML</h4>
                        <p class="text-muted small">Conception de diagrammes de classes, modélisation de bases de données et gestion Git en équipe.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>

                    <!-- DUPLICATION DU GROUPE (Obligatoire pour créer l'effet d'optique de boucle infinie fluide) -->
                    <div class="card p-4 text-center shadow-sm course-card-custom" aria-hidden="true">
                        <i class="bi bi-code-slash display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Développement Web</h4>
                        <p class="text-muted small">HTML, CSS, JavaScript et Architecture PHP MVC pour créer des applications sécurisées.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>
                    
                    <div class="card p-4 text-center shadow-sm course-card-custom" aria-hidden="true">
                        <i class="bi bi-database display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Bases de Données Relational</h4>
                        <p class="text-muted small">Administration Oracle 19c, SQL, requêtes PL/SQL avancées et modélisation Visual Paradigm.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>
                    
                    <div class="card p-4 text-center shadow-sm course-card-custom" aria-hidden="true">
                        <i class="bi bi-cpu display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Intelligence Artificielle</h4>
                        <p class="text-muted small">Initiation aux architectures de Machine Learning, Big Data et scripts de traitement Python.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>

                    <div class="card p-4 text-center shadow-sm course-card-custom" aria-hidden="true">
                        <i class="bi bi-diagram-3 display-4 text-primary mb-3"></i>
                        <h4 class="fs-5 fw-bold">Génie Logiciel & UML</h4>
                        <p class="text-muted small">Conception de diagrammes de classes, modélisation de bases de données et gestion Git en équipe.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-auto w-100">Voir le cours</a>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 3 : Les Témoignages (Statiques) -->
        <section class="mt-5 mb-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">Nos Témoignages</h2>
                <p class="text-muted">Avis recueillis auprès de notre communauté éducative.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border border-primary p-4 h-100 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-circle display-6 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-0 fw-bold"><?= $user_role === 'etudiant' ? $user_prenom . ' ' . $user_nom : 'Fred NANGUE' ?></h5>
                                <small class="text-muted">Étudiant en 3ème année</small>
                            </div>
                        </div>
                        <p class="fst-italic text-muted mb-0">
                            "TrackS m'a permis de suivre la progression de mes syllabus en temps réel et de téléverser mes justificatifs instantanément en ligne. L'interface responsive est magique sur smartphone."
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border border-primary p-4 h-100 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-workspace display-6 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">Miguel FEULEFACK</h5>
                                <small class="text-muted">Enseignant en Informatique</small>
                            </div>
                        </div>
                        <p class="fst-italic text-muted mb-0">
                            "Faire l'appel en un clic avec la feuille interactive AJAX me fait gagner 10 minutes à chaque début de séance. C'est un outil collaboratif indispensable pour le département."
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
