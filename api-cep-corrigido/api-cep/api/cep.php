<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Trata preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$cep    = isset($_GET['cep']) ? preg_replace('/\D/', '', $_GET['cep']) : null;

// ──────────────────────────────────────────
// GET — buscar por CEP ou listar todos
// ──────────────────────────────────────────
if ($method === 'GET' && !empty($cep)) {

    $stmt = $pdo->prepare("SELECT * FROM cep WHERE REPLACE(cep, '-', '') = ?");
    $stmt->execute([$cep]);
    $resultado = $stmt->fetch();

    if ($resultado) {
        http_response_code(200);
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado'], JSON_UNESCAPED_UNICODE);
    }

} elseif ($method === 'GET') {

    $stmt = $pdo->query("SELECT * FROM cep");
    $resultado = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

// ──────────────────────────────────────────
// POST — inserir novo CEP
// ──────────────────────────────────────────
} elseif ($method === 'POST') {

    $body = json_decode(file_get_contents('php://input'), true);

    if (empty($body['cep']) || empty($body['rua']) || empty($body['cidade']) || empty($body['estado'])) {
        http_response_code(400);
        echo json_encode(['erro' => 'Campos obrigatórios: cep, rua, cidade, estado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Verifica se CEP já existe
    $check = $pdo->prepare("SELECT id FROM cep WHERE REPLACE(cep, '-', '') = ?");
    $check->execute([preg_replace('/\D/', '', $body['cep'])]);
    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode(['erro' => 'CEP já cadastrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO cep (cep, rua, bairro, cidade, estado)
         VALUES (:cep, :rua, :bairro, :cidade, :estado)"
    );
    $stmt->execute([
        ':cep'    => $body['cep'],
        ':rua'    => $body['rua'],
        ':bairro' => $body['bairro'] ?? '',
        ':cidade' => $body['cidade'],
        ':estado' => strtoupper(substr($body['estado'], 0, 2)),
    ]);

    http_response_code(201);
    echo json_encode([
        'mensagem' => 'CEP cadastrado com sucesso',
        'id'       => (int) $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

// ──────────────────────────────────────────
// DELETE — remover CEP
// ──────────────────────────────────────────
} elseif ($method === 'DELETE') {

    if (empty($cep)) {
        http_response_code(400);
        echo json_encode(['erro' => 'CEP não informado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM cep WHERE REPLACE(cep, '-', '') = ?");
    $stmt->execute([$cep]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['mensagem' => 'CEP deletado com sucesso'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado'], JSON_UNESCAPED_UNICODE);
    }

// ──────────────────────────────────────────
// Método não suportado
// ──────────────────────────────────────────
} else {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido'], JSON_UNESCAPED_UNICODE);
}
