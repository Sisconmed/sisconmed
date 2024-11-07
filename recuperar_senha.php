<?php
// Configurações de e-mail
$host = 'smtp.exemplo.com'; // Exemplo: smtp.gmail.com
$username = 'seu-email@exemplo.com';
$password = 'sua-senha';
$port = 587; // Porta para SMTP com STARTTLS

// Configurações de banco de dados
$host_db = 'localhost';
$user_db = 'root';
$pass_db = '';
$name_db = 'seubanco';

// Conectar ao banco de dados
$conn = new mysqli($host_db, $user_db, $pass_db, $name_db);
if ($conn->connect_error) {
    die('Conexão falhou: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);

    // Verificar se o e-mail está registrado
    $result = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
    if ($result->num_rows > 0) {
        // Gerar um token de recuperação
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 3600; // Token expira em 1 hora

        // Inserir token no banco de dados
        $conn->query("INSERT INTO tokens (email, token, expires) VALUES ('$email', '$token', '$expires')");

        // Enviar o e-mail
        require 'PHPMailer/PHPMailerAutoload.php'; // Biblioteca PHPMailer

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $port;

        $mail->setFrom($username, 'Seu Nome ou Empresa');
        $mail->addAddress($email);
        $mail->Subject = 'Recuperação de Senha';
        $mail->Body    = 'Clique no link para redefinir sua senha: http://localhost/seusite/redefinir_senha.php?token=' . $token;

        if ($mail->send()) {
            echo 'Um e-mail foi enviado para sua conta com instruções para redefinir sua senha.';
        } else {
            echo 'Não foi possível enviar o e-mail. Por favor, tente novamente.';
        }
    } else {
        echo 'E-mail não encontrado.';
    }

    $conn->close();
}
?>