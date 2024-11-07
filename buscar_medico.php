<?php

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'agendador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Captura o nome da especialização da query string usando 'name'
    $specializationName = $_GET['name'] ?? '';

    // A query é ajustada para usar ':name' como placeholder
    $stmt = $pdo->prepare("SELECT id, medico FROM especializacao WHERE nome = :name");
    $stmt->execute(['name' => $specializationName]);

    // Utiliza fetch() para obter o registro único correspondente
    $medico = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($medico) {
        echo json_encode($medico); // Se um médico for encontrado, retorna os detalhes
    } else {
        echo json_encode(['message' => 'Nenhum médico encontrado para esta especialização.']); // Mensagem de erro amigável
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao acessar o banco de dados']); // Tratamento de erro
}
?>