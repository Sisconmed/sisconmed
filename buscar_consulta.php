<?php
session_start();
if (!isset($_SESSION['CPF'])) {
    // Redireciona para o login se não houver CPF definido
    header("Location: login.html");
    exit;
}

$cpf = $_SESSION['CPF'];// Inicia a sessao com o mesmo CPF do login

$host = 'localhost'; // ou o IP do seu servidor de banco de dados
$dbname = 'agendador';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consultar as especializações
    $stmt = $pdo->query('SELECT id, nome FROM especializacao');
    $especializacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Não foi possível conectar ao banco de dados: " . $e->getMessage());
}


$consultas = [];
if (isset($_GET['query'])) {
    $cpf = $_SESSION['CPF'];
    $consultaID = $_GET['query'];

    try {
        $sql = "SELECT c.ID, c.Data, c.Hora, e.Nome AS Especializacao, e.Medico, u.Nome AS Usuario
                FROM consultas c
                JOIN especializacao e ON c.Especializacao = e.ID
                JOIN usuarios u ON c.CPF = u.CPF
                WHERE c.ID = :consultaID AND c.CPF = :CPF";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':consultaID', $consultaID, PDO::PARAM_INT);
        $stmt->bindParam(':CPF', $cpf, PDO::PARAM_STR);
        $stmt->execute();
        $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca</title>
</head>
<body>
<style>
        /* Estilo Básico e Reset */
        html, body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        height: 100%;
        }

        /* Estilos de botões gerais */
        button, input[type="submit"] { /* Aplica estilos a todos os botões e submits */
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        }

        button:hover, input[type="submit"]:hover {
        background-color: #0056b3;
        }

        /* Layout Principal */
        header {
        background-color: #009c7a;
        color: white;
        padding: 20px;
        text-align: center;
        }

        h1, h2 {
        margin: 0;
        text-align: center;
        }

        section, footer {
        padding: 20px;
        text-align: center;
        }

        footer {
        background-color: #009c7a;
        color: white;
        text-align: center;
        position: absolute;
        bottom: 0;
        width: 100%;
        }

        /* Navegação */
        nav {
        background-color: #007777;
        padding: 10px;
        width: 100%;
        }

        ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        text-align: center;
        }

        li {
        display: inline;
        }

        a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        }

        a:hover {
        background-color: #6eddff;
        }

        /* Estilos Específicos de Botões */
        .botao-busca {
        background-color: #007bff;
        width: auto; /* Remove a largura fixa anterior */
        }

        .botao-limpar {
        background-color: #ff6347; /* cor vermelha clara */
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        }

        .botao-limpar:hover {
        background-color: #e53e30; /* cor vermelha escura */
        }
   
</style>
    
    <header>
        <h1>Consultas Agendadas</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.html">Início</a></li>
        </ul>
    </nav>
    <br>
    <h2>Realize a busca de sua consulta</h2>
    <section>
        <form action="buscar_consulta.php" method="get">
            <input type="text" name="query" placeholder="Digite o Numero da consulta" required>
            <input type="submit" value="Buscar" class="botao-busca">
            <button type="button" class="botao-limpar" onclick="clearResults()">Limpar</button>
        </form>

        <script>
            function clearResults() {
            // Limpa os resultados
            var consultaLista = document.querySelector('.consulta-lista');
            if (consultaLista) {
                consultaLista.innerHTML = '';
            }
            // Limpa o campo de busca
            document.querySelector('input[name="query"]').value = '';
            }
        </script>

        <?php if (!empty($consultas)): ?>
            <div class="consulta-lista">
                <?php foreach ($consultas as $consulta): ?>
                    <div class="consulta-item">
                        <?php
                        $dataConsulta = new DateTime($consulta['Data']);
                        $horaConsulta = new DateTime($consulta['Hora']);
                        ?>
                        <p> </p>
                        <p> </p>
                        <h2>Consulta com <?= htmlspecialchars($consulta['Medico']) ?></h2>
                        <p><strong>Paciente:</strong> <?= htmlspecialchars($consulta['Usuario']) ?></p>
                        <p><strong>Data:</strong> <?= $dataConsulta->format('d/m/Y') ?></p>
                        <p><strong>Horário:</strong> <?= $horaConsulta->format('H:i') ?></p>
                        <p><strong>Especialidade:</strong> <?= htmlspecialchars($consulta['Especializacao']) ?></p>
                        <h2><strong>Número da Consulta:</strong> <?= htmlspecialchars($consulta['ID']) ?></h2>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_GET['query'])): ?>
            <p> </p>
            <p>Nenhuma consulta encontrada com esse número.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2024 Agenda Consult. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
