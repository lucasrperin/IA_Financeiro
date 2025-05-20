<?php include __DIR__ . '/../php/despesas_php.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Despesas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light">
  <div class="container py-4">
    <h1 class="mb-4">Despesas Cadastradas</h1>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDespesaModal">
      Adicionar Despesa
    </button>
    <?php include __DIR__ . '/../modals/addDespesaModal.php'; ?>

    <table id="tblDespesas" class="table table-striped">
      <thead>
        <tr>
          <th>Valor (R$)</th>
          <th>Categoria</th>
          <th>Data</th>
          <th>Descrição</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($despesas as $d): ?>
        <tr>
          <td><?= number_format($d['valor'], 2, ',', '.') ?></td>
          <td><?= htmlspecialchars($d['categoria']) ?></td>
          <td><?= date('d/m/Y', strtotime($d['data'])) ?></td>
          <td><?= htmlspecialchars($d['descricao']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="mb-3">
      <label for="userPrompt" class="form-label">Prompt da Análise</label>
      <textarea id="userPrompt" class="form-control" rows="2" placeholder="Escreva aqui seu prompt personalizado"></textarea>
    </div>

    <button id="btnAnalise" class="btn btn-success">Gerar Análise</button>
    <div id="resultadoAnalise" class="mt-4 p-3 bg-white border rounded"></div>
  </div>

  <script src="../scripts/ia_analise.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>