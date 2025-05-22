<?php
  $currentPage = basename($_SERVER['SCRIPT_NAME']);
  // Cookie retorna string "true"/"false" ou não existe
  $isCollapsed = (isset($_COOKIE['sidebarCollapsed']) && $_COOKIE['sidebarCollapsed'] === 'true');
?>
<!-- Botão hamburguer (mobile) -->
<button
  class="btn btn-light d-lg-none position-fixed top-0 start-0 m-3"
  type="button"
  data-bs-toggle="offcanvas"
  data-bs-target="#sidebarOffcanvas"
  aria-controls="sidebarOffcanvas"
>
  <i class="fa-solid fa-bars"></i>
</button>

<!-- Offcanvas menu (mobile) -->
<div
  class="offcanvas offcanvas-start"
  tabindex="-1"
  id="sidebarOffcanvas"
  aria-labelledby="sidebarOffcanvasLabel"
>
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Menu</h5>
    <button
      type="button"
      class="btn-close text-reset"
      data-bs-dismiss="offcanvas"
      aria-label="Close"
    ></button>
  </div>
  <div class="offcanvas-body px-0">
    <ul class="nav nav-pills flex-column">
      <li class="nav-item mb-2">
        <a class="nav-link <?= $currentPage==='despesas.php'?'active':'' ?>" href="despesas.php">
          <i class="fa-solid fa-wallet me-2"></i><span>Despesas</span>
        </a>
      </li>
      <li class="nav-item mb-2">
        <a class="nav-link <?= $currentPage==='config.php'?'active':'' ?>" href="config.php">
          <i class="fa-solid fa-cog me-2"></i><span>Configurações</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $currentPage==='ia.php'?'active':'' ?>" href="ia.php">
          <i class="fa-solid fa-robot me-2"></i><span>IA</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Sidebar desktop -->
<nav
  id="sidebar"
  class="d-none d-lg-block bg-light vh-100 position-fixed <?= $isCollapsed ? 'collapsed' : '' ?>"
  style="top:0; left:0;"
>
  <div class="p-3 d-flex align-items-center justify-content-between">
    <h5 class="mb-0">Menu</h5>
    <i
      id="sidebarToggleIcon"
      class="fa-solid <?= $isCollapsed ? 'fa-angle-right' : 'fa-angle-left' ?>"
      style="cursor:pointer;"
    ></i>
  </div>
  <ul class="nav nav-pills flex-column px-3">
    <li class="nav-item mb-2">
      <a class="nav-link <?= $currentPage==='despesas.php'?'active':'' ?>"
         href="despesas.php">
        <i class="fa-solid fa-wallet me-2"></i><span>Despesas</span>
      </a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link <?= $currentPage==='config.php'?'active':'' ?>"
         href="config.php">
        <i class="fa-solid fa-cog me-2"></i><span>Configurações</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $currentPage==='ia.php'?'active':'' ?>"
         href="ia.php">
        <i class="fa-solid fa-robot me-2"></i><span>IA</span>
      </a>
    </li>
  </ul>
</nav>
