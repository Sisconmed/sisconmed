<?php
$host = 'localhost'; // ou o IP do servidor de banco de dados
$dbname = 'agendador';
$username = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se a senha e a confirmação são iguais
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        echo "<script>alert('Senhas não coincidem, tente novamente!'); window.location.href = 'cadastro.html';</script>";
    }

    // Prepara a inserção dos dados recebidos do formulário
    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, cpf, email, senha) VALUES (:nome, :cpf, :email, :senha)');

    // Vincula parâmetros ao comando SQL (proteção contra injeção SQL)
    $stmt->bindParam(':nome', $_POST['nome']);
    $stmt->bindParam(':cpf', $_POST['CPF']);
    $stmt->bindParam(':email', $_POST['email']);
    
    // Criptografa a senha antes de armazenar no banco
    $senha = $_POST['senha'];
    $stmt->bindParam(':senha', $senha);

    // Executa o comando SQL
    $stmt->execute();

    echo "<script>alert('Cadastro realizado com sucesso !'); window.location.href = 'Login.html';</script>";
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
