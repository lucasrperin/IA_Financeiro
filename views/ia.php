<!-- ia.php -->
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
  <link href="../styles/ia.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light">
  <?php include __DIR__ . '/../components/sidebar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <!-- ia.php -->
<main class="col-12 col-lg-10 offset-lg-2 d-flex flex-column" style="height:100vh; padding:1rem;">
  <h1 class="mb-4 flex-shrink-0">Chat IA – Despesas</h1>
<!-- janela de conversa (maior que o prompt) -->
  <div id="chatWindow" class="flex-grow-1 bg-white rounded shadow-sm overflow-auto mb-3"></div>

  <!-- wrapper do prompt -->
  <div class="prompt-wrapper d-flex align-items-end flex-shrink-0">
    <!-- caixa de texto como div -->
    <div
      id="promptDiv"
      class="form-control prompt-input"
      contenteditable="true"
      data-placeholder="Digite sua mensagem...">
    </div>
    <!-- botão fixo abaixo -->
    <button id="btnAnalise" class="btn btn-primary">
      <i class="fa-solid fa-paper-plane"></i>
    </button>
  </div>
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
