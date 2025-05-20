document.getElementById('btnAnalise').addEventListener('click', async () => {
  const rows = Array.from(
    document.querySelectorAll('#tblDespesas tbody tr')
  ).map(tr => {
    const [v, c, d, desc] = [...tr.children].map(td => td.innerText);
    return {
      valor: parseFloat(v.replace('.', '').replace(',', '.')),
      categoria: c,
      data: d,
      descricao: desc
    };
  });

  const totCategoria = rows.reduce((acc, cur) => {
    acc[cur.categoria] = (acc[cur.categoria] || 0) + cur.valor;
    return acc;
  }, {});

  const userPrompt = document.getElementById('userPrompt').value.trim();
  const payload = { 
    resumo: { totCategoria, despesas: rows },
    prompt: userPrompt 
  };

  const resultadoEl = document.getElementById('resultadoAnalise');
  resultadoEl.innerText = 'Gerando análise...';

  try {
    const res = await axios.post('../php/chatCohere.php', payload);
    resultadoEl.innerText = res.data;
  } catch (err) {
    resultadoEl.innerText = 'Erro: ' + err;
  }
});