<body class="bg-light d-flex align-items-center justify-content-center">
  <div class="card shadow-lg w-100" style="max-width: 480px;">
    <div class="card-body">
      <div class="text-center">
        <h1 class="card-title h3">Sign in</h1>
        <p class="card-text text-muted">Sign in below to access your account</p>
      </div>
      <div class="mt-4">
        <form method="post">
          <div class="form-floating mb-3">
            <input name="email" type="email" class="form-control" id="email" placeholder="Email Address"
              value="<?= htmlspecialchars($model->email ?? '') ?>">
            <label for="email" class="form-label text-muted">Email Address</label>
            <?php if ($model->hasError('email')): ?>
              <div class="d-block invalid-feedback"><?= $model->getFirstError('email') ?></div>
            <?php endif; ?>
          </div>
          <div class="form-floating mb-3">
            <input name="password" type="password" class="form-control" id="password" placeholder="Password"
              value="<?= htmlspecialchars($model->password ?? '') ?>">
            <label for="password" class="form-label text-muted">Password</label>
            <?php if ($model->hasError('password')): ?>
              <div class="d-block invalid-feedback"><?= $model->getFirstError('password') ?></div>
            <?php endif; ?>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-dark btn-lg">Sign in</button>
          </div>
          <p class="text-center text-muted mt-4">Don't have an account yet?
            <a href="/register" class="text-decoration-none">Sign up</a>.
          </p>
        </form>
      </div>
    </div>
  </div>
