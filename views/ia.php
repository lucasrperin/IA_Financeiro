<?php
include __DIR__ . '/../php/despesas_php.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IA – Chat de Despesas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="../styles/sidebar.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light">
    <?php
        include __DIR__ . '/../components/sidebar.php';
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Conteúdo Principal -->
            <main class="col-12 col-lg-10 offset-lg-2">
                <h1 class="mb-4">Chat IA – Despesas</h1>

                <!-- Janela de conversa -->
                <div id="chatWindow"
                class="border rounded p-3 mb-4 bg-white"
                style="height:400px; overflow-y:auto;">
                <!-- mensagens serão injetadas aqui -->
                </div>

                <!-- Campo de prompt -->
                <div class="mb-3">
                    <label for="userPrompt" class="form-label">Seu prompt</label>
                    <textarea id="userPrompt" class="form-control" rows="2" placeholder="Escreva aqui seu prompt"></textarea>
                </div>
                <button id="btnAnalise" class="btn btn-success mb-5">Enviar</button>
            </main>
        </div>
    </div>

  <!-- Injeta dados para o script -->
  <script>
    window.despesasData = <?= json_encode($despesas, JSON_UNESCAPED_UNICODE) ?>;
  </script>
  <script src="../scripts/ia_analise.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../scripts/despesas.js"></script>
</body>
</html>
