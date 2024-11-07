<?php
// Inicia conexão com o banco de dados
$host = 'localhost';
$dbname = 'agendador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta as consultas agendadas no banco de dados
    
    session_start();

    // Verifica se o CPF está definido na sessão
    if (!isset($_SESSION['CPF'])) {
        // Redireciona para o login se caso não haver CPF definido
        header("Location: login.html");
        exit;
        
    }
    
    $cpf = $_SESSION['CPF'];
    
    $sql = "SELECT c.ID, c.Data, c.Hora, e.Nome AS Especializacao, e.Medico, u.Nome AS Usuario
            FROM consultas c
            JOIN especializacao e ON c.Especializacao = e.ID
            JOIN usuarios u ON c.CPF = u.CPF
            WHERE c.CPF = :CPF"; // :CPF é um parametro para segurança, apenas o CPF do usuário logado será mostrado
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CPF', $cpf, PDO::PARAM_STR);
    $stmt->execute();
    
// Armazena os resultados
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
 
</head>
<body>

<style>
        body {
            font-family: Arial, sans-serif;
            
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 30px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        select {
        width: 100%;
        padding: 8px; /* Adiciona espaço dentro do elemento select */
        margin-bottom: 10px; /* Adiciona espaço abaixo do select */
    }
    </style>
</head>
<body>
<style>
            .botao-voltar {
        position: absolute;
          top: 20px; /* Distância do topo da página */
          left: 20px; /* Distância da direita da página */
          background-color: #007bff; /* Cor de fundo azul */
          color: #fff; /* Cor do texto branco */
          border: none;
          padding: 10px 20px;
          font-size: 16px;
          cursor: pointer;
          border-radius: 5px;
          z-index: 999; /* Para garantir que o botão fique sobre outros elementos */
  }
   
  .botao-voltar:hover {
    background-color: #0056b3;
  }

  .botao-agenda {
        position: absolute;
          top: 20px; /* Distância do topo da página */
          right: 20px; /* Distância da direita da página */
          background-color: #007bff; /* Cor de fundo azul */
          color: #fff; /* Cor do texto branco */
          border: none;
          padding: 10px 20px;
          font-size: 16px;
          cursor: pointer;
          border-radius: 5px;
          z-index: 999; /* Para garantir que o botão fique sobre outros elementos */
  }
   
  .botao-agenda:hover {
    background-color: #0056b3;
  }
</style>

    <a class="botao-voltar":hover href="index.html">Sair</a>   
    <a class="botao-agenda":hover href="agendamento.php">Realizar novo agendamento</a>     
    <div class="container">
    <h1>Consultas Agendadas</h1>
    <div class="consulta-lista">
    <?php foreach ($consultas as $consulta): ?>
    <div class="consulta-item">
        <?php
        // Cria um objeto DateTime para a data e hora
        $dataConsulta = new DateTime($consulta['Data']);
        $horaConsulta = new DateTime($consulta['Hora']);
        
        // Formata a data e a hora para o padrão brasileiro
        $dataFormatada = $dataConsulta->format('d/m/Y');
        $horaFormatada = $horaConsulta->format('H:i');
        ?>
        <h2>Consulta com <?= htmlspecialchars($consulta['Medico']) ?></h2>
        <p><strong>Paciente:</strong> <?= htmlspecialchars($consulta['Usuario']) ?></p>
        <p><strong>Data:</strong> <?= $dataFormatada ?></p>
        <p><strong>Horário:</strong> <?= $horaFormatada ?></p>
        <p><strong>Especialidade:</strong> <?= htmlspecialchars($consulta['Especializacao']) ?></p>
        <h2><strong>Número da Consulta:</strong> <?= htmlspecialchars($consulta['ID']) ?></h2>
    </div>
    <?php endforeach; ?>
</div>
    </div>
</body>
</html>
