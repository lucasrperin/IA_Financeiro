document.addEventListener('DOMContentLoaded', () => {
  const btn   = document.getElementById('toggleApiKey');
  const input = document.getElementById('api_key');
  if (!btn || !input) return;

  btn.addEventListener('click', () => {
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('i')
       .classList.toggle('fa-eye',  !isHidden);
    btn.querySelector('i')
       .classList.toggle('fa-eye-slash', isHidden);
  });
});
