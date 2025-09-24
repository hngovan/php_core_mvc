<?php
use core\Application;

$currentUrl = Application::$app->request->getUrl();
$currentUrl = '/' . trim($currentUrl, '/');

if ($currentUrl === '/') {
  $currentUrl = '/';
}

$navItems = [
  [
    'label' => 'Dashboard',
    'url' => '/',
    'icon' => 'bi bi-house-door',
  ],
  [
    'label' => 'Profile',
    'url' => '/profile',
    'icon' => 'bi bi-person',
  ],
  [
    'label' => 'Posts',
    'url' => '/posts',
    'icon' => 'bi bi-file-post',
  ]
];
$user = Application::$app->user;
?>

<nav id="sidebar" class="col-md-3 col-lg-2 bg-light sidebar border border-right">
  <div class="position-sticky pt-3 overflow-auto">
    <ul class="nav flex-column">
      <?php foreach ($navItems as $item): ?>
        <?php if (isset($item['auth']) && $item['auth'] && Application::$app->isGuest()):
          continue;
        endif; ?>

        <?php
        $url = $item['url'] ?? '#';
        $isActive = ($url === '/' && $currentUrl === '/') ||
          ($url !== '/' && $currentUrl === $url) ||
          (isset($item['activePattern']) && preg_match($item['activePattern'], $currentUrl));
        $activeClass = $isActive ? 'active' : '';
        ?>

        <li class="nav-item">
          <a class="nav-link <?= $activeClass ?>" href="<?= $url ?>">
            <?php if (!empty($item['icon'])): ?>
              <i class="<?= $item['icon'] ?> me-2"></i>
            <?php endif; ?>
            <?= $item['label'] ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
