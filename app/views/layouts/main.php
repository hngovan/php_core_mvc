<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <title><?= htmlspecialchars($this->title ?? 'Home', ENT_QUOTES, 'UTF-8') ?></title>
  <style>
    #sidebar {
      min-height: 100vh;
      transition: all 0.3s;
      height: 100vh;
      position: fixed;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar-hidden {
      margin-left: -100%;
    }

    @media (max-width: 767.98px) {
      #sidebar {
        z-index: 1000;
        background-color: #f8f9fa;
        width: 80%;
        max-width: 250px;
      }
    }

    .nav-link {
      color: #333;
      transition: all 0.2s;
    }

    .nav-link:hover,
    .nav-link.active {
      background-color: #e9ecef;
    }

    .avatar {
      width: 32px;
      height: 32px;
      font-size: 14px;
      font-weight: 500;
    }

    /* Hide icon dropdown arrow */
    .dropdown-toggle::after {
      display: none;
    }

    /* Toast container */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 10000;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <?= $this->renderComponent('navbar'); ?>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-auto">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h3"><?= htmlspecialchars($this->title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></h1>
          <div class="d-flex justify-content-center align-items-center gap-2">
            <?php use core\Application; ?>
            <div class="dropdown">
              <div class="d-flex align-items-center gap-2 dropdown-toggle" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"
                title="<?= htmlspecialchars(Application::$app->user->getDisplayName(), ENT_QUOTES, 'UTF-8') ?>">
                <div
                  class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center avatar">
                  <?php
                  $displayName = Application::$app->user->getDisplayName();
                  echo strtoupper(substr($displayName, 0, 1));
                  ?>
                </div>
                <!-- <span class="d-none d-md-inline fw-medium">
                <?= htmlspecialchars(Application::$app->user->getDisplayName(), ENT_QUOTES, 'UTF-8') ?>
              </span> -->
              </div>

              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="/profile">
                    <i class="bi bi-person me-2"></i>Profile
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/settings">
                    <i class="bi bi-gear me-2"></i>Settings
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item text-danger" href="/logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </a>
                </li>
              </ul>

            </div>
            <button id="sidebarToggle" class="btn btn-primary d-md-none">
              <i class="bi bi-list"></i>
            </button>
          </div>

        </div>
        <div class="d-flex flex-column align-items-start">
          {{ content }}
        </div>
      </main>
    </div>
  </div>

  <!-- Toast Component -->
  <?= $this->renderComponent('toast'); ?>

  <!-- <script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const body = document.body;

      // Initialize toasts
      const toastElList = [].slice.call(document.querySelectorAll('.toast'));
      const toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, {
          delay: parseInt(toastEl.dataset.bsDelay) || 3000
        });
      });

      sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('sidebar-hidden');
      });

      // Close sidebar when clicking outside on mobile
      body.addEventListener('click', function (event) {
        if (window.innerWidth <= 767.98 && !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
          sidebar.classList.add('sidebar-hidden');
        }
      });

      // Update sidebar visibility on window resize
      window.addEventListener('resize', function () {
        if (window.innerWidth > 767.98) {
          sidebar.classList.remove('sidebar-hidden');
        }
      });

      // Highlight active nav item
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', function () {
          navLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
        });
      });

      // Auto hide toasts after delay
      setTimeout(function () {
        toastList.forEach(function (toast) {
          if (toast._element.classList.contains('show')) {
            toast.hide();
          }
        });
      }, 5000);
    });
  </script>
</body>

</html>
