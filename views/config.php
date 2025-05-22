
<?php include __DIR__ . '/../php/config_php.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Configurações</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="../styles/sidebar.css" rel="stylesheet">
  <link href="../styles/config.css" rel="stylesheet">
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
        <h1 class="mb-4">Configurações</h1>
        <form method="post">
          <div class="col-5 mb-3">
            <label for="api_key" class="form-label">Cohere API Key</label>
            <div class="input-group">
              <input 
                type="password" 
                id="api_key" 
                name="api_key" 
                class="form-control" 
                value="<?= htmlspecialchars($currentKey) ?>"
                autocomplete="new-password"
              >
              <button 
                class="btn" 
                type="button" 
                id="toggleApiKey"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Salvar</button>
          <?php if (!empty($saved)): ?>
            <div class="mt-3 alert alert-success">Chave salva com sucesso!</div>
          <?php endif; ?>
        </form>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../scripts/despesas.js"></script>
  <script src="../scripts/config.js"></script>
</body>
</html>