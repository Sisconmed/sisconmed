<?php
session_start();

// Verifica se o CPF está armazenado na sessão
if (!isset($_SESSION['CPF'])) {
    // Se não estiver, redireciona para a página de login
    header("Location: login.html");
    exit;
}

// Recupera o CPF armazenado na sessão
$cpf = $_SESSION['CPF'];

// Recupera os dados do formulário
$especializacao = $_POST['specialization'];
$medico = $_POST['doctor'];
$data = $_POST['date'];
$hora = $_POST['time'];

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'agendador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepara a inserção no banco de dados
    $sql = "INSERT INTO consultas (CPF, Especializacao, Data, Hora, Medico) VALUES (:CPF, :Especializacao, :Data, :Hora, :Medico)";
    $stmt = $pdo->prepare($sql);

    // Executa a inserção
    $stmt->execute([
        ':CPF' => $cpf,
        ':Especializacao' => $especializacao,
        ':Data' => $data,
        ':Hora' => $hora,
        ':Medico' => $medico
    ]);

    // Apresenta a mensagem que a consulta foi agendada na tela
    echo "<script>alert('Consulta agendada com sucesso!'); window.location.href = 'Consultas_agendadas.php';</script>";
 
} catch (PDOException $e) {
    die("Erro ao agendar consulta: " . $e->getMessage());
}
?>

