// ia_analise.js (ajuste em appendMessage)
const chatWindow = document.getElementById('chatWindow');

function appendMessage(text, isUser) {
  const wrapper = document.createElement('div');
  // adiciona as classes corretas para CSS
  wrapper.className = (isUser ? 'message user' : 'message bot') + ' mb-2';

  const bubble = document.createElement('div');
  bubble.className = 'bubble';
  bubble.innerText = text;

  wrapper.appendChild(bubble);
  chatWindow.appendChild(wrapper);
  chatWindow.scrollTop = chatWindow.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnAnalise');
  if (!btn) return;

  btn.addEventListener('click', async () => {
    const promptEl = document.getElementById('promptDiv');
    const userPrompt = promptEl.innerText.trim();
    if (!userPrompt) return alert('Escreva um prompt antes de enviar.');

    promptEl.innerText = '';
    appendMessage(userPrompt, true);

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

    appendMessage('...', false);
    try {
      const res = await axios.post('../php/chatCohere.php', {
        resumo: { totCategoria, despesas: rows },
        prompt: userPrompt
      });
      chatWindow.lastChild.querySelector('.bubble').innerText = res.data;
    } catch (err) {
      chatWindow.lastChild.querySelector('.bubble').innerText = 'Erro: ' + err;
    }
  });
});
