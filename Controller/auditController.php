<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php'; 

// 1. Contrôle d'accès de sécurité strict
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['scolarite', 'chef_departement'])) {
    header('Location: dashboard.php');
    exit();
}

try {
    $pdo = tracksDatabase();

    // 2. Traitement des filtres de recherche indexés
    $search_term   = isset($_GET['search']) ? trim($_GET['search']) : '';
    $filter_action = isset($_GET['action_filter']) ? trim($_GET['action_filter']) : '';
    $date_start    = isset($_GET['date_start']) ? trim($_GET['date_start']) : '';
    $date_end      = isset($_GET['date_end']) ? trim($_GET['date_end']) : '';

    $where_clauses = [];
    $query_params  = [];

    if (!empty($search_term)) {
        $where_clauses[] = "(u.Nom_user LIKE :search OR u.Prenom_user LIKE :search OR u.Matricule_etu LIKE :search OR l.Description_log LIKE :search)";
        $query_params['search'] = '%' . $search_term . '%';
    }
    if (!empty($filter_action)) {
        $where_clauses[] = "l.Action_log = :action";
        $query_params['action'] = $filter_action;
    }
    if (!empty($date_start)) {
        $where_clauses[] = "l.Timestamp_log >= :date_start";
        $query_params['date_start'] = $date_start;
    }
    if (!empty($date_end)) {
        $where_clauses[] = "l.Timestamp_log <= :date_end";
        $query_params['date_end'] = $date_end;
    }

    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

    // 3. Extraction des indicateurs clés (KPIs) de masse
    $stats_query = $pdo->query("
        SELECT 
            SUM(CASE WHEN Action_log = 'INSERT' THEN 1 ELSE 0 END) as total_inserts,
            SUM(CASE WHEN Action_log = 'UPDATE' THEN 1 ELSE 0 END) as total_updates,
            SUM(CASE WHEN Action_log = 'DELETE' THEN 1 ELSE 0 END) as total_deletes,
            COUNT(*) as total_global
        FROM logsaudit
    ");
    $kpi = $stats_query->fetch(PDO::FETCH_ASSOC);

    // 4. Calcul de la pagination matérielle (20 lignes)
    $logs_per_page = 20;
    $current_page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset        = ($current_page - 1) * $logs_per_page;

    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM logsaudit l LEFT JOIN utilisateur u ON l.UtilisateurID = u.ID $where_sql");
    $count_stmt->execute($query_params);
    $total_logs = $count_stmt->fetchColumn();
    $total_pages = ceil($total_logs / $logs_per_page);

    // 5. Chargement des données ciblées (LIMIT / OFFSET)
    $sql = "SELECT l.*, u.Nom_user, u.Prenom_user, u.Role_user, u.Matricule_etu 
            FROM logsaudit l
            LEFT JOIN utilisateur u ON l.UtilisateurID = u.ID
            $where_sql
            ORDER BY l.Timestamp_log DESC, l.ID DESC 
            LIMIT :limit OFFSET :offset";

    $query = $pdo->prepare($sql);
    $query->bindValue(':limit', $logs_per_page, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($query_params as $key => $val) {
        $query->bindValue(':' . $key, $val);
    }
    $query->execute();
    $logs = $query->fetchAll(PDO::FETCH_ASSOC);

    // Groupement par date pour créer l'effet de Frise Chronologique
    $timeline = [];
    foreach ($logs as $log) {
        $timeline[$log['Timestamp_log']][] = $log;
    }

} catch (PDOException $e) {
    $error_msg = "Échec d'analyse de l'auditoire de surveillance : " . $e->getMessage();
}