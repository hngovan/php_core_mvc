<?php use core\Application; ?>

<div class="alert alert-success">
  <div class="d-flex align-items-center gap-1">
    <span>Welcome</span>
    <strong><?php echo Application::$app->user->getDisplayName() ?></strong>
  </div>
</div>
