<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $annee_academique = trim($_POST['annee_academique'] ?? '2026-2027');

    try {
        // On récupère l'instance PDO
        $pdo = tracksDatabase();
        
        // Extraction des valeurs actuelles pour préserver l'état des colonnes non soumises
        $select_stmt = $pdo->prepare("SELECT RenduAppel_set, FormatExport_set FROM parametre WHERE UtilisateurID = :id LIMIT 1");
        $select_stmt->execute(['id' => $user_id]);
        $current_settings = $select_stmt->fetch();
        
        // Correction de la logique de repli : utilise POST s'il existe, sinon l'ancienne valeur, sinon valeur par défaut
        $rendu_appel = isset($_POST['rendu_appel']) ? $_POST['rendu_appel'] : ($current_settings['RenduAppel_set'] ?? 'liste');
        $format_export = isset($_POST['format_export']) ? $_POST['format_export'] : ($current_settings['FormatExport_set'] ?? 'pdf');

        // Correction SQL : Ajout de suffixes (2) pour différencier les marqueurs de l'UPDATE
        $sql = "INSERT INTO parametre (UtilisateurID, AnneeAcademique_set, RenduAppel_set, FormatExport_set) 
                VALUES (:user_id, :annee, :rendu, :export)
                ON DUPLICATE KEY UPDATE 
                    AnneeAcademique_set = :annee2, 
                    RenduAppel_set = :rendu2, 
                    FormatExport_set = :export2";
        
        $query = $pdo->prepare($sql);
        $query->execute([
            'user_id'  => $user_id,
            'annee'    => $annee_academique,
            'rendu'    => $rendu_appel,
            'export'   => $format_export,
            'annee2'   => $annee_academique,
            'rendu2'   => $rendu_appel,
            'export2'  => $format_export
        ]);

        // Mise à jour de la session
        $_SESSION['pref_annee_academique'] = $annee_academique;
        $_SESSION['pref_rendu_appel'] = $rendu_appel;
        $_SESSION['pref_format_export'] = $format_export;

        $_SESSION['settings_success'] = "Vos préférences de rendu d'affichage ont été mémorisées.";
    } catch (PDOException $e) {
        $_SESSION['settings_error'] = "Impossible de consigner vos préférences dans le stockage persistant.";
    }

    header('Location: ../View/parametres.php');
    exit();
}
