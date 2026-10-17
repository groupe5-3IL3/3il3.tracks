<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation de complétion des données requises
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['settings_error'] = "Veuillez remplir l'intégralité des champs de vérification d'accès.";
        header('Location: ../View/parametres.php');
        exit();
    }

    // Validation de l'identité des deux saisies du nouveau mot de passe
    if ($new_password !== $confirm_password) {
        $_SESSION['settings_error'] = "Le mot de passe de confirmation est divergent.";
        header('Location: ../View/parametres.php');
        exit();
    }

    // Validation de la complexité (Longueur minimale)
    if (strlen($new_password) < 8) {
        $_SESSION['settings_error'] = "Le nouveau code de sécurité doit comporter au moins 8 caractères.";
        header('Location: ../View/parametres.php');
        exit();
    }

    try {

        //On récupère l'instance PDO en appelant votre fonction
        $pdo = tracksDatabase();

        // Extraction de l'empreinte de sécurité courante via la colonne MotDePasse_user
        $stmt = $pdo->prepare("SELECT MotDePasse_user FROM utilisateur WHERE ID = :id LIMIT 1");
        $stmt->execute(['id' => $user_id]);
        $user_row = $stmt->fetch();

        // Vérification de correspondance de l'ancienne clé d'accès avec l'empreinte bcrypt de la BDD
        if ($user_row && password_verify($old_password, $user_row['MotDePasse_user'])) {
            
            // Génération d'une nouvelle clé chiffrée selon l'algorithme standard natif de PHP
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            
            // Mutation de la ligne dans la table utilisateur
            $update_stmt = $pdo->prepare("UPDATE utilisateur SET MotDePasse_user = :password WHERE ID = :id");
            $update_stmt->execute([
                'password' => $new_hash,
                'id' => $user_id
            ]);

            $_SESSION['settings_success'] = "Votre clé secrète d'accès a été réinitialisée avec succès.";
        } else {
            $_SESSION['settings_error'] = "Le mot de passe actuel fourni ne correspond pas à nos enregistrements.";
        }
    } catch (PDOException $e) {
        $_SESSION['settings_error'] = "Une erreur système fatale a bloqué la réinitialisation de sécurité.";
    }

    header('Location: ../View/parametres.php');
    exit();
}
