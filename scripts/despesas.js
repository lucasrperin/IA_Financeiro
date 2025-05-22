document.addEventListener('DOMContentLoaded', () => {
  const icon    = document.getElementById('sidebarToggleIcon');
  const sidebar = document.getElementById('sidebar');
  if (!icon || !sidebar) return;

  icon.addEventListener('click', () => {
    const isCollapsed = sidebar.classList.toggle('collapsed');

    // troca a seta
    icon.classList.toggle('fa-angle-left', !isCollapsed);
    icon.classList.toggle('fa-angle-right', isCollapsed);

    // salva no cookie para toda a aplicação
    document.cookie = `sidebarCollapsed=${isCollapsed}; path=/; max-age=${60*60*24*30}`;
  });
});
