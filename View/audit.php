<?php
require_once '../Controller/auditController.php';

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$user_nom = $_SESSION['user_nom'];
$user_prenom = $_SESSION['user_prenom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#10183b" />
    <link rel="icon" href="../assets/images/favicon.ico" />    
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <title>Auditoire de Surveillance | TrackS</title>
</head>
<body class="container d-flex flex-column min-vh-100 bg-light">

    <?php include 'header.php'; ?>

    <main class="flex-grow-1 my-4">
        
        <!-- SECTION EN-TÊTE BIENVENUE -->
        <section class="row border border-1 rounded shadow-sm bg-white p-4 align-items-center mb-4 zoom-hover">
            <div class="col-md-8">
                <h2 class="text-primary fw-bold mb-2"><i class="bi bi-fingerprint me-2"></i>Auditoire de Surveillance</h2>
                <p class="text-muted mb-0 lh-lg">
                    Registre général de traçabilité de l'établissement. Analyse de masse en temps réel de l'activité du système et des requêtes de manipulation de données (LMD).
                </p>
            </div>
            <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fs-6 rounded-pill d-inline-flex align-items-center gap-1">
                    <span class="spinner-grow spinner-grow-sm text-primary" style="width: 8px; height: 8px;"></span>
                    Suivi Général Actif
                </span>
            </div>
        </section>

        <!-- CARTES DES MÉTRIQUES (KPIs) -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border border-1 shadow-sm rounded bg-white p-3 h-100 text-center text-lg-start zoom-hover">
                    <span class="text-muted small fw-bold text-uppercase tracking-wider">Volume Global</span>
                    <h3 class="fw-bold text-primary mt-2 m-0"><?= number_format($kpi['total_global'] ?? 0, 0, ',', ' ') ?></h3>
                    <div class="text-muted small mt-1"><i class="bi bi-database-fill me-1"></i>Opérations consignées</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border border-1 shadow-sm rounded bg-white p-3 h-100 text-center text-lg-start zoom-hover">
                    <span class="text-success small fw-bold text-uppercase tracking-wider">Créations (Insert)</span>
                    <h3 class="fw-bold text-success mt-2 m-0"><?= number_format($kpi['total_inserts'] ?? 0, 0, ',', ' ') ?></h3>
                    <div class="text-muted small mt-1"><i class="bi bi-plus-circle-fill text-success me-1"></i>Enregistrements</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border border-1 shadow-sm rounded bg-white p-3 h-100 text-center text-lg-start zoom-hover">
                    <span class="text-warning small fw-bold text-uppercase tracking-wider">Modifications (Update)</span>
                    <h3 class="fw-bold text-warning mt-2 m-0"><?= number_format($kpi['total_updates'] ?? 0, 0, ',', ' ') ?></h3>
                    <div class="text-muted small mt-1"><i class="bi bi-pencil-square text-warning me-1"></i>Mises à jour</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border border-1 shadow-sm rounded bg-white p-3 h-100 text-center text-lg-start zoom-hover">
                    <span class="text-danger small fw-bold text-uppercase tracking-wider">Suppressions (Delete)</span>
                    <h3 class="fw-bold text-danger mt-2 m-0"><?= number_format($kpi['total_deletes'] ?? 0, 0, ',', ' ') ?></h3>
                    <div class="text-muted small mt-1"><i class="bi bi-exclamation-octagon-fill text-danger me-1"></i>Actions critiques</div>
                </div>
            </div>
        </div>

        <!-- ZONE DE RECHERCHE FILTRABLE -->
        <div class="card border border-1 shadow-sm rounded bg-white mb-4">
            <div class="card-body p-3">
                <form method="GET" action="audit.php" class="row g-3 align-items-end small">
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted small fw-bold">Recherche par mot-clé</label>
                        <div class="input-group bg-light rounded border">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 form-control-sm" placeholder="Nom, matricule, détails..." value="<?= htmlspecialchars($search_term) ?>">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">Nature LMD</label>
                        <select name="action_filter" class="form-select form-select-sm bg-light">
                            <option value="">Toutes les requêtes</option>
                            <option value="INSERT" <?= $filter_action === 'INSERT' ? 'selected' : '' ?>>INSERT (Création)</option>
                            <option value="UPDATE" <?= $filter_action === 'UPDATE' ? 'selected' : '' ?>>UPDATE (Modification)</option>
                            <option value="DELETE" <?= $filter_action === 'DELETE' ? 'selected' : '' ?>>DELETE (Suppression)</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">Date de début</label>
                        <input type="date" name="date_start" class="form-control form-control-sm bg-light" value="<?= htmlspecialchars($date_start) ?>">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-bold">Date de fin</label>
                        <input type="date" name="date_end" class="form-control form-control-sm bg-light" value="<?= htmlspecialchars($date_end) ?>">
                    </div>
                    <div class="col-6 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm fw-medium"><i class="bi bi-funnel me-2"></i>Filtrer</button>
                        <?php if (!empty($search_term) || !empty($filter_action) || !empty($date_start) || !empty($date_end)): ?>
                            <a href="audit.php" class="btn btn-outline-secondary btn-sm px-3"><i class="bi bi-arrow-counterclockwise"></i></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger rounded shadow-sm" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error_msg) ?></div>
        <?php else: ?>
            
            <?php if (empty($timeline)): ?>
                <div class="card border border-1 shadow-sm rounded bg-white p-5 text-center text-muted">
                    <i class="bi bi-journal-x display-5 d-block mb-3 text-secondary"></i>
                    Aucun enregistrement d'audit dans ce segment de recherche.
                </div>
            <?php else: ?>
                <?php foreach ($timeline as $date_log => $day_logs): ?>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fw-bold rounded-3 me-2 shadow-sm">
                                <i class="bi bi-calendar-event me-2 text-primary"></i><?= date('d M Y', strtotime($date_log)) ?>
                            </span>
                            <div class="flex-grow-1 border-top border-secondary-subtle"></div>
                        </div>

                        <div class="card border border-1 shadow-sm rounded bg-white overflow-hidden">
                            <div class="list-group list-group-flush">
                                <?php foreach ($day_logs as $log): ?>
                                    <?php 
                                        $border_color = 'border-start-primary';
                                        $icon_class = 'bi-plus-circle text-success';
                                        if ($log['Action_log'] === 'INSERT') { $border_color = 'border-start border-4 border-success'; $icon_class = 'bi-plus-circle text-success'; }
                                        if ($log['Action_log'] === 'UPDATE') { $border_color = 'border-start border-4 border-warning'; $icon_class = 'bi-pencil-square text-warning'; }
                                        if ($log['Action_log'] === 'DELETE') { $border_color = 'border-start border-4 border-danger'; $icon_class = 'bi-exclamation-octagon text-danger'; }
                                    ?>
                                    <div class="list-group-item p-3 <?= $border_color ?> hover-transaction transition-all bg-white">
                                        <div class="row align-items-center g-2">
                                            <div class="col-auto">
                                            <div class="col-auto">
                                                <div class="fs-4 px-1"><i class="bi <?= $icon_class ?>"></i></div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="fw-bold text-dark mb-0">
                                                    <?= $log['Nom_user'] ? htmlspecialchars($log['Nom_user'] . ' ' . $log['Prenom_user']) : 'Action Système' ?>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mt-0.5">
                                                    <span class="text-muted text-capitalize small bg-light px-1.5 py-0.5 rounded border small" style="font-size: 0.75rem;"><?= $log['Role_user'] ? str_replace('_', ' ', htmlspecialchars($log['Role_user'])) : 'Automatique' ?></span>
                                                    <?php if (!empty($log['Matricule_etu'])): ?>
                                                        <span class="text-muted small font-monospace">[<?= htmlspecialchars($log['Matricule_etu']) ?>]</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7 text-secondary small lh-base">
                                                <span class="text-dark fw-medium">Opération #<?= htmlspecialchars($log['ID']) ?></span> — <?= htmlspecialchars($log['Description_log']) ?>
                                            </div>
                                            <div class="col-auto ms-auto text-end text-muted font-monospace small d-none d-md-block">
                                                Ref: LOG-00<?= htmlspecialchars($log['Id_log']) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- PAGINATION DE MASSE -->
            <?php if ($total_pages > 1): ?>
                <nav class="my-4">
                    <ul class="pagination justify-content-center gap-1">
                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link rounded-3 border border-1 bg-white text-dark shadow-sm" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search_term) ?>&action_filter=<?= urlencode($filter_action) ?>&date_start=<?= urlencode($date_start) ?>&date_end=<?= urlencode($date_end) ?>"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <?php 
                        $start = max(1, $current_page - 2);
                        $end = min($total_pages, $start + 4);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <li class="page-item <?= $current_page === $i ? 'active' : '' ?>">
                                <a class="page-link rounded-3 border border-1 shadow-sm <?= $current_page === $i ? 'bg-primary border-primary text-white' : 'text-dark bg-white' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search_term) ?>&action_filter=<?= urlencode($filter_action) ?>&date_start=<?= urlencode($date_start) ?>&date_end=<?= urlencode($date_end) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link rounded-3 border border-1 bg-white text-dark shadow-sm" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search_term) ?>&action_filter=<?= urlencode($filter_action) ?>&date_start=<?= urlencode($date_start) ?>&date_end=<?= urlencode($date_end) ?>"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

