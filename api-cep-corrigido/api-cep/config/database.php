<?php

$host = 'localhost';
$db   = 'db_cep';
$user = 'root';
$pass = ''; // troque pela sua senha, se necessário

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'erro'    => 'Falha na conexão com o banco de dados',
        'detalhe' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
