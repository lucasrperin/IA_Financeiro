// mantém histórico em memória
const chatWindow = document.getElementById('chatWindow');

function appendMessage(text, isUser) {
  const wrapper = document.createElement('div');
  wrapper.className = isUser ? 'text-end mb-2' : 'text-start mb-2';
  const bubble = document.createElement('span');
  bubble.className = isUser 
    ? 'badge bg-primary text-wrap' 
    : 'badge bg-secondary text-wrap';
  bubble.style.maxWidth = '75%';
  bubble.innerText = text;
  wrapper.appendChild(bubble);
  chatWindow.appendChild(wrapper);
  chatWindow.scrollTop = chatWindow.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnAnalise');
  if (btn) {
    btn.addEventListener('click', async () => {
      const promptEl = document.getElementById('userPrompt');
      const userPrompt = promptEl.value.trim();
      if (!userPrompt) return alert('Escreva um prompt antes de enviar.');

      // limpa campo e adiciona prompt na conversa
      promptEl.value = '';
      appendMessage(userPrompt, true);

      // monta os dados de despesas
      const rows = window.despesasData.map(d => ({
        valor: parseFloat(d.valor),
        categoria: d.categoria,
        data: d.data,
        descricao: d.descricao
      }));
      const totCategoria = rows.reduce((acc, cur) => {
        acc[cur.categoria] = (acc[cur.categoria]||0) + cur.valor;
        return acc;
      }, {});

      // envia tudo para a IA
      appendMessage('...', false);
      try {
        const res = await axios.post('../php/chatCohere.php', {
          resumo: { totCategoria, despesas: rows },
          prompt: userPrompt
        });
        // substitui o "..." pela resposta
        chatWindow.lastChild.querySelector('span').innerText = res.data;
      } catch (err) {
        chatWindow.lastChild.querySelector('span').innerText = 'Erro: ' + err;
      }
    });
  };
});
