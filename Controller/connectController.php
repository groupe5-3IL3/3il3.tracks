<?php
session_start();
require_once '../config/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profil = trim($_POST['profil'] ?? '');
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? ''; // On récupère le ticket envoyé

    if(empty($profil) || empty($identifiant) || empty($mot_de_passe)) {
        $_SESSION['login_error'] = 'Veuillez remplir tous les champs.';
        header('Location: ../index.php');
        exit();
    }

    // Protection CSRF : On vérifie si le ticket est valide
    if (empty($csrf_token) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrf_token)) {
        $_SESSION['login_error'] = 'Échec de la validation de sécurité (CSRF).';
        header('Location: ../index.php');
        exit();
    }

    try{

        //On récupère l'instance PDO en appelant votre fonction
        $pdo = tracksDatabase();

        $stmt = $pdo->prepare('SELECT * FROM utilisateur2 WHERE (Email = :email OR Matricule = :matricule) AND Role = :role AND EstActif = 1 LIMIT 1');
        $stmt->execute([
            'email'     => $identifiant, 
            'matricule' => $identifiant, 
            'role'      => $profil
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe haché
        if($user && password_verify($mot_de_passe, $user['MotDePasse'])) {
            //Sécurisation de la session pour éviter le détournement de session
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['ID2']; 
            $_SESSION['user_role'] = $user['Role'];
            $_SESSION['user_nom'] = $user['Nom'];
            $_SESSION['user_prenom'] = $user['Prenom'];
            $_SESSION['user_email'] = $user['Email'];
           
            // Redirection vers le tableau de bord après la connexion réussie
            header('Location: ../View/dashboard.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Identifiant ou mot de passe incorrect, profil erroné ou compte inactif.';
            header('Location: ../index.php');
            exit();
        }

    }catch(PDOException $e) {
        $_SESSION['login_error'] = 'Une erreur technique est survenue.';
        header('Location: ../index.php');
        exit();
    }

}
?>