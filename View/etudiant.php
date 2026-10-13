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
    
    <!-- Intégration du Header modulaire -->
    <?php include 'header.php'; ?>

    <main class="flex-grow-1">
        
      
    </main>

    <?php include 'footer.php'; ?>

    <script src="/../TrackS_back/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
