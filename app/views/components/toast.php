<?php
use core\Application;

if (
  !Application::$app->session->getFlash('success') &&
  !Application::$app->session->getFlash('error') &&
  !Application::$app->session->getFlash('warning') &&
  !Application::$app->session->getFlash('info')
) {
  return;
}
?>

<div class="toast-container">
  <?php if (Application::$app->session->getFlash('success')): ?>
    <?php echo renderToast('success', 'Success', Application::$app->session->getFlash('success'), 'check-circle'); ?>
  <?php endif; ?>

  <?php if (Application::$app->session->getFlash('error')): ?>
    <?php echo renderToast('error', 'Error', Application::$app->session->getFlash('error'), 'exclamation-triangle'); ?>
  <?php endif; ?>

  <?php if (Application::$app->session->getFlash('warning')): ?>
    <?php echo renderToast('warning', 'Warning', Application::$app->session->getFlash('warning'), 'exclamation-circle'); ?>
  <?php endif; ?>

  <?php if (Application::$app->session->getFlash('info')): ?>
    <?php echo renderToast('info', 'Info', Application::$app->session->getFlash('info'), 'info-circle'); ?>
  <?php endif; ?>
</div>

<?php
function renderToast($type, $title, $message, $icon)
{
  $config = [
    'success' => ['bg' => 'bg-success', 'text' => 'text-white', 'btn' => 'btn-close-white', 'delay' => '3000'],
    'error' => ['bg' => 'bg-danger', 'text' => 'text-white', 'btn' => 'btn-close-white', 'delay' => '5000'],
    'warning' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'btn' => '', 'delay' => '4000'],
    'info' => ['bg' => 'bg-info', 'text' => 'text-white', 'btn' => 'btn-close-white', 'delay' => '3000']
  ][$type];

  return "
    <div class=\"toast show\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\" data-bs-delay=\"{$config['delay']}\">
        <div class=\"toast-header {$config['bg']} {$config['text']}\">
            <strong class=\"me-auto\"><i class=\"bi bi-{$icon} me-2\"></i>{$title}</strong>
            <button type=\"button\" class=\"btn-close {$config['btn']}\" data-bs-dismiss=\"toast\" aria-label=\"Close\"></button>
        </div>
        <div class=\"toast-body\">
            " . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "
        </div>
    </div>
    ";
}
?>

