<?php

// Conexão com o banco de dados
$host = 'localhost'; // ou o endereço do seu servidor de banco de dados
$dbname = 'agendador';
$username = 'root';
$password = '';


try {
    $pdo = new PDO('mysql:host=seu_host;dbname=seu_banco_de_dados', 'seu_usuario', 'sua_senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Não foi possível conectar ao banco de dados: " . $e->getMessage());
}

// Consultar as especializações
try {
    $stmt = $pdo->query('SELECT id, nome FROM especializacao');
    $especializacao = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na consulta ao banco de dados: " . $e->getMessage());
}
?>

<div class="form-group">
    <label for="specialization">Especialização:</label>
    <select id="specialization" name="specialization" required>
        <option value="">Selecione a especialização</option>
        <?php foreach ($especializaccao as $especializacoes): ?>
            <option value="<?php echo htmlspecialchars($especializacoes['id']); ?>">
                <?php echo htmlspecialchars($especializacoes['nome']); ?>
            </option>
        <?php endforeach; ?>
        <!-- As opções agora são geradas a partir do banco de dados -->
    </select>
</div>