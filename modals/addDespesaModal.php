<div class="modal fade" id="addDespesaModal" tabindex="-1" aria-labelledby="addDespesaModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../public/cadastrar_despesas.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addDespesaModalLabel">Nova Despesa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="valor" class="form-label">Valor (R$)</label>
            <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
          </div>
          <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" id="categoria" name="categoria" required>
              <option value="">Selecione...</option>
              <option>Alimentação</option>
              <option>Transporte</option>
              <option>Lazer</option>
              <option>Outros</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
