<?php
    $currentPath = uri_string(); // dipakai untuk highlight menu aktif
    $menus = [
        ['label' => 'Dashboard',       'icon' => 'bi-house-door-fill', 'url' => 'dashboard',      'match' => ['', 'dashboard']],
        ['label' => 'Data Pengecekan', 'icon' => 'bi-clipboard-data',  'url' => 'data-pengecekan', 'match' => ['data-pengecekan']],
        ['label' => 'Analisis Data',   'icon' => 'bi-bar-chart-line',  'url' => 'analisis-data',   'match' => ['analisis-data']],
        ['label' => 'Form CERTA',      'icon' => 'bi-file-earmark-check', 'url' => 'form-certa',  'match' => ['form-certa']],
    ];
?>
<aside class="certa-sidebar" id="certaSidebar">
    <div class="certa-sidebar-brand">
        <a href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="CERTA Logo" class="certa-logo-img">
        </a>
    </div>

    <nav class="certa-sidebar-nav">
        <?php foreach ($menus as $menu): ?>
            <?php $active = in_array($currentPath, $menu['match'], true); ?>
            <a href="<?= base_url($menu['url']) ?>" class="certa-nav-link <?= $active ? 'active' : '' ?>">
                <i class="bi <?= $menu['icon'] ?>"></i>
                <span><?= esc($menu['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>

<!-- Overlay untuk sidebar mobile -->
<div class="certa-sidebar-overlay" id="certaSidebarOverlay"></div>
