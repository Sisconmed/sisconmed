<?php
session_start(); // Inicia a sessão

$host = 'localhost'; // ou o IP do seu servidor de banco de dados
$dbname = 'agendador';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $CPF = $_POST['CPF'] ?? '';  // Coalescing operator para evitar erros de undefined index
    $senha = $_POST['senha'] ?? '';
    $redirect = $_POST['redirect'] ?? 'default';

    

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE CPF = :CPF");
    $stmt->execute(['CPF' => $CPF]);
    $userdata = $stmt->fetch(PDO::FETCH_ASSOC);

    //Verifica se o CPF está cadastrado no Banco
    if (!$userdata) {
        // CPF não está cadastrado
       echo "<script>alert('CPF não cadastrado! Crie uma conta.'); window.location.href = 'Login.html?redirect=$redirect';</script>";
        exit;
    }
    //verificação de CPF e senha no Banco para realizar o login
    if ($userdata && $senha == $userdata['senha']) {
        // Login bem-sucedido
        $_SESSION['CPF'] = $CPF;
        switch ($redirect) {
            case 'busca':
                header("Location: buscar_consulta.php");
                break;
            default:
                header("Location: agendamento.php");
                break;
        }
        exit;
    } else {

        //Falha de login, senha incorreta
        echo "<script>alert('Senha incorreta! Tente novamente.'); window.location.href = 'Login.html?redirect=$redirect';</script>";
       
    }
    
    //apresentar erro caso não conecte no banco de dados
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }

?>
