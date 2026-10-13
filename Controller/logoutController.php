<?php
// On démarre la session pour pouvoir y accéder
session_start();

// On vide toutes les variables de session enregistrés (ID, Nom, Prenom, email, Role)
$_SESSION = array();

// On détruit le cookie de session dans le navigateur pour une sécurité totale
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// On  détruit officiellement la session côté serveur pour libérer les ressources
session_destroy();

//Redirection immédiate vers la page de connexion 
header('Location: ../index.php');
exit();
?>