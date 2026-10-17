<?php
// Connexion : on réutilise config/db.php s'il définit $pdo, sinon connexion de secours
$dbFile = __DIR__ . '/../config/db.php';
if (file_exists($dbFile)) { require_once $dbFile; }

if (!function_exists('e')) {
    function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

class EtudiantController
{
    private static function pdo(): PDO
    {
        global $pdo;
        if (!isset($pdo) || !($pdo instanceof PDO)) {
            $pdo = new PDO(
                'mysql:host=127.0.0.1;dbname=tracks;charset=utf8mb4',
                'root',
                '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return $pdo;
    }

    /** Traite le formulaire (si envoyé) et renvoie toutes les données utiles à la vue */
    public static function handle(): array
    {
        $pdo     = self::pdo();
        $message = '';
        $type    = 'success';
        $old     = ['nom' => '', 'prenom' => '', 'email' => '', 'matricule' => '', 'filiere' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_etudiant'])) {
            $old = [
                'nom'       => trim($_POST['nom'] ?? ''),
                'prenom'    => trim($_POST['prenom'] ?? ''),
                'email'     => trim($_POST['email'] ?? ''),
                'matricule' => trim($_POST['matricule'] ?? ''),
                'filiere'   => $_POST['filiere'] ?? '',
            ];
            $mdp      = $_POST['mot_de_passe'] ?? '';
            $mdp2     = $_POST['mot_de_passe2'] ?? '';
            $estActif = isset($_POST['est_actif']) ? 1 : 0;

            if ($old['nom'] === '' || $old['prenom'] === '' || $old['email'] === '' || $old['matricule'] === '' || $old['filiere'] === '' || $mdp === '') {
                $message = 'Remplis tous les champs obligatoires.';
                $type = 'danger';
            } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $message = 'Adresse email invalide.';
                $type = 'danger';
            } elseif (strlen($mdp) < 6) {
                $message = 'Le mot de passe doit contenir au moins 6 caractères.';
                $type = 'danger';
            } elseif ($mdp !== $mdp2) {
                $message = 'Les deux mots de passe ne correspondent pas.';
                $type = 'danger';
            } else {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE Email_user = ? OR Matricule_etu = ?');
                $stmt->execute([$old['email'], $old['matricule']]);
                if ($stmt->fetchColumn() > 0) {
                    $message = 'Cet email ou ce matricule existe déjà.';
                    $type = 'danger';
                } else {
                    try {
                        $pdo->beginTransaction();
                        $ins = $pdo->prepare(
                            "INSERT INTO utilisateur
                             (FiliereID, Id_user, Nom_user, Prenom_user, Email_user, MotDePasse_user, Role_user, EstActif_user, Matricule_etu, Specialite_ens, Discriminator)
                             VALUES (?, 0, ?, ?, ?, ?, 'etudiant', ?, ?, NULL, 'Utilisateur')"
                        );
                        $ins->execute([
                            (int)$old['filiere'],
                            $old['nom'],
                            $old['prenom'],
                            $old['email'],
                            password_hash($mdp, PASSWORD_DEFAULT),
                            $estActif,
                            $old['matricule'],
                        ]);
                        $newId = (int)$pdo->lastInsertId();
                        $pdo->prepare('UPDATE utilisateur SET Id = ID WHERE ID = ?')->execute([$newId]);
                        $pdo->commit();

                        $message = 'Étudiant enregistré.';
                        $old = ['nom' => '', 'prenom' => '', 'email' => '', 'matricule' => '', 'filiere' => ''];
                    } catch (Exception $ex) {
                        if ($pdo->inTransaction()) { $pdo->rollBack(); }
                        $message = "Erreur lors de l'enregistrement : " . $ex->getMessage();
                        $type = 'danger';
                    }
                }
            }
        }

        return [
            'message'   => $message,
            'type'      => $type,
            'old'       => $old,
            'filieres'  => self::filieres(),
            'etudiants' => self::liste(),
        ];
    }

    public static function filieres(): array
    {
        return self::pdo()->query('SELECT ID, Nom_filiere FROM filiere ORDER BY Nom_filiere')->fetchAll();
    }

    public static function liste(): array
    {
        return self::pdo()->query(
            "SELECT u.ID, u.Nom_user, u.Prenom_user, u.Email_user, u.Matricule_etu, u.EstActif_user + 0 AS actif, f.Nom_filiere AS filiere
             FROM utilisateur u
             LEFT JOIN filiere f ON f.ID = u.FiliereID
             WHERE u.Role_user = 'etudiant'
             ORDER BY u.ID DESC"
        )->fetchAll();
    }
}