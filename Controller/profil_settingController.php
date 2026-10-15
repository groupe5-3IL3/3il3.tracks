<?php
session_start();
require_once '../config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $email_input = trim($_POST['Email_user'] ?? '');

    if (empty($email_input) || !filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['settings_error'] = "Format d'adresse électronique non valide.";
        header('Location: ../View/parametres.php');
        exit();
    }

    try {
        
        //On récupère l'instance PDO en appelant votre fonction
        $pdo = tracksDatabase();

        // Contrôle d'unicité : Vérifier si cet email est déjà attribué à un autre identifiant ID
        $check_stmt = $pdo->prepare("SELECT ID FROM utilisateur WHERE Email_user = :email AND ID != :id LIMIT 1");
        $check_stmt->execute(['email' => $email_input, 'id' => $user_id]);
        
        if ($check_stmt->fetch()) {
            $_SESSION['settings_error'] = "Cette adresse e-mail est déjà affectée à un autre compte TrackS.";
        } else {
            // Requête de mise à jour sur la table utilisateur
            $update_stmt = $pdo->prepare("UPDATE utilisateur SET Email_user = :email WHERE ID = :id");
            $update_stmt->execute(['email' => $email_input, 'id' => $user_id]);

            // Synchronisation de la session globale pour répercuter le changement sur le header/footer
            $_SESSION['user_email'] = $email_input;
            $_SESSION['settings_success'] = "Vos informations de profil ont été mises à jour.";
        }
    } catch (PDOException $e) {
        $_SESSION['settings_error'] = "Erreur de traitement SQL lors de la mise à jour du profil.";
    }

    header('Location: ../View/parametres.php');
    exit();
}
