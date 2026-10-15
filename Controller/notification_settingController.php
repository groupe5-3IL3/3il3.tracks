<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Traitement des commutateurs : 1 si coché, 0 si absent du tableau $_POST
    $notify_absence = isset($_POST['notify_absence']) ? 1 : 0;
    $notify_syllabus = isset($_POST['notify_syllabus']) ? 1 : 0;
    $notify_justif = isset($_POST['notify_justif']) ? 1 : 0;

    try {

        //On récupère l'instance PDO en appelant votre fonction
        $pdo = tracksDatabase();

        // Enregistrement ou mise à jour forcée sur la contrainte de clé UtilisateurID
        $sql = "INSERT INTO parametre (UtilisateurID, NotifyAbsence_set, NotifySyllabus_set, NotifyJustif_set) 
                VALUES (:user_id, :absence, :syllabus, :justif)
                ON DUPLICATE KEY UPDATE NotifyAbsence_set = :absence2, NotifySyllabus_set = :syllabus2, NotifyJustif_set = :justif2";
        
        $query = $pdo->prepare($sql);
        $query->execute([
            'user_id' => $user_id,
            'absence' => $notify_absence,
            'syllabus'=> $notify_syllabus,
            'justif'  => $notify_justif,
            'absence2' => $notify_absence,
            'syllabus2'=> $notify_syllabus,
            'justif2'  => $notify_justif
        ]);

        // Persistance en session pour maintenir le rendu visuel de l'UI synchrone
        $_SESSION['notify_absence'] = $notify_absence;
        $_SESSION['notify_syllabus'] = $notify_syllabus;
        $_SESSION['notify_justif'] = $notify_justif;

        $_SESSION['settings_success'] = "La configuration de vos canaux de notification a été enregistrée.";
    } catch (PDOException $e) {
        $_SESSION['settings_error'] = "Erreur lors de la modification de vos autorisations d'alertes.";
    }

    header('Location: ../View/parametres.php');
    exit();
}
    
