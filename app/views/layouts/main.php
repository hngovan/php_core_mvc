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
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar-hidden {
      margin-left: -100%;
    }

    @media (max-width: 767.98px) {
      #sidebar {
        position: fixed;
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
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <?= $this->renderComponent('navbar'); ?>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h3"><?= htmlspecialchars($this->title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></h1>
          <button id="sidebarToggle" class="btn btn-primary d-md-none">
            <i class="bi bi-list"></i>
          </button>
        </div>
        <div class="d-flex flex-column align-items-start">
          {{ content }}
        </div>
      </main>
    </div>
  </div>

  <!-- <script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const body = document.body;

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
    });
  </script>
</body>

</html>
