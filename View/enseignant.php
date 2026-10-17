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

          <?php
// Vue : affichage uniquement, la logique est dans le contrôleur
require_once __DIR__ . '/../Controller/enseignantController.php';
extract(EnseignantController::handle());   // $message, $type, $old, ...
?>

<main class="container my-4">

  <?php if ($message): ?>
    <div class="alert alert-<?= e($type) ?> alert-dismissible fade show" role="alert">
      <?= e($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- Formulaire d'inscription -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h2 class="h5 fw-bold mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Enregistrer un enseignant</h2>
          <p class="text-muted small mb-4">Les champs marqués * sont obligatoires.</p>

          <form method="post" action="" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label for="nom" class="form-label">Nom *</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= e($old['nom']) ?>" required>
              </div>
              <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom *</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= e($old['prenom']) ?>" required>
              </div>
              <div class="col-12">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= e($old['email']) ?>" required>
              </div>
              <div class="col-md-6">
                <label for="matricule" class="form-label">Matricule *</label>
                <input type="text" class="form-control" id="matricule" name="matricule" value="<?= e($old['matricule']) ?>" required>
              </div>
              <div class="col-12">
                <label for="specialite" class="form-label">Spécialité *</label>
                <input type="text" class="form-control" id="specialite" name="specialite" value="<?= e($old['specialite']) ?>" placeholder="ex. Réseaux, Génie logiciel" required>
              </div>
              <div class="col-md-6">
                <label for="mot_de_passe" class="form-label">Mot de passe *</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" minlength="6" required>
              </div>
              <div class="col-md-6">
                <label for="mot_de_passe2" class="form-label">Confirmation *</label>
                <input type="password" class="form-control" id="mot_de_passe2" name="mot_de_passe2" minlength="6" required>
              </div>
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="est_actif" name="est_actif" checked>
                  <label class="form-check-label" for="est_actif">Compte actif</label>
                </div>
              </div>
              <div class="col-12 d-grid">
                <button type="submit" name="ajouter_enseignant" class="btn btn-primary">
                  <i class="bi bi-person-plus me-1"></i>Enregistrer l'enseignant
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Liste des étudiants -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Enseignants enregistrés</h2>
            <span class="badge text-bg-primary"><?= count($enseignants) ?></span>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Matricule</th>
                  <th>Nom complet</th>
                  <th>Email</th>
                  <th>Spécialité</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$enseignants): ?>
                  <tr><td colspan="5" class="text-center text-muted py-4">Aucun enseignant pour le moment. Utilise le formulaire pour en ajouter un.</td></tr>
                <?php endif; ?>
                <?php foreach ($enseignants as $et): ?>
                  <tr>
                    <td><?= e($et['Matricule_etu']) ?></td>
                    <td><?= e($et['Nom_user'] . ' ' . $et['Prenom_user']) ?></td>
                    <td><?= e($et['Email_user']) ?></td>
                    <td><?= e($et['Specialite_user'] ?? '—') ?></td>
                    <td>
                      <?php if ((int)$et['actif'] === 1): ?>
                        <span class="badge text-bg-success">Actif</span>
                      <?php else: ?>
                        <span class="badge text-bg-secondary">Inactif</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>
  <?php include 'footer.php'; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
