<?php

session_start(); // Inicia a sessão

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
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="styles.css"> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Consultas Médicas</title>
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
    <style>
			/* Aplica a imagem de fundo ao corpo inteiro da página */
			body {
			  background-image: url('https://s2-techtudo.glbimg.com/l626XNU8faximhMVJ-wUt5KUDgo=/1200x/smart/filters:cover():strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2019/P/Y/w0gSH5RXiEwdnQbVReCQ/appointment-book-blur-care-40568.jpg'); /* Caminho para a imagem */
			  background-repeat: no-repeat; /* Não repetir a imagem */
			  background-size: cover; /* Cobrir toda a página */
			  background-position: center; /* Centralizar imagem na página */
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
            </style>
            <a class="botao-voltar" href="index.html">Sair</a>
            <div class="container">
                <h1>Agendamento de Consultas Médicas</h1>
                <form action="processar_agendamento.php" method="post">
                   <br></br> 
                    <div class="form-group">
                        <label for="specialization">Especialização:</label>
                        <select id="specialization" name="specialization" required>
                            <option value="">Selecione a especialização</option>
                            <?php foreach ($especializacoes as $especializacao): ?>
                                <option value="<?php echo htmlspecialchars($especializacao['id']); ?>">
                                    <?php echo htmlspecialchars($especializacao['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Data:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Hora:</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="doctor">Médico:</label>
                        <select id="doctor" name="doctor" required>
                        <option value="">Selecione um médico</option>
<!-- As opções serão preenchidas pelo JavaScript para preencher o campo médico de acordo com especialização selecionada-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('specialization').addEventListener('change', function() {
        // Encontra o nome da especialização baseado na opção selecionada
        var specializationName = this.options[this.selectedIndex].text; // Mudança aqui
        if(specializationName === "Selecione a especialização") {
            specializationName = ""; // Evita enviar este texto como parâmetro
        }

        fetch('buscar_medico.php?name=' + encodeURIComponent(specializationName))
        .then(response => response.json())
        .then(data => {
            var doctorSelect = document.getElementById('doctor');
            doctorSelect.innerHTML = '<option value=""></option>'; // Reinicia a lista

            if (data && data.medico) {
                var option = new Option(data.medico, data.id);
                doctorSelect.add(option);
                option.selected = true;
            } else {
                doctorSelect.innerHTML += '<option value="">Nenhum médico encontrado</option>';
            }
        })
        .catch(error => console.error('Erro ao buscar médicos:', error));
    });
});
</script>

                    </select>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <button type="submit">Agendar Consulta</button>
                    </div>
                </form>
            </div>
        </form>
    </div>
</body>
</html>