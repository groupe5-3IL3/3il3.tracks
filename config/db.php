<?php
declare(strict_types=1);
require_once '../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();
function tracksDatabase(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = $_ENV['TRACKS_DB_HOST'] ?? getenv('TRACKS_DB_HOST');
    $port = $_ENV['TRACKS_DB_PORT'] ?? getenv('TRACKS_DB_PORT');
    $name = $_ENV['TRACKS_DB_NAME'] ?? getenv('TRACKS_DB_NAME');
    $user = $_ENV['TRACKS_DB_USER'] ?? getenv('TRACKS_DB_USER');
    $password = $_ENV['TRACKS_DB_PASSWORD'] ?? getenv('TRACKS_DB_PASSWORD');
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}