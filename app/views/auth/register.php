<div class="card shadow-lg w-100" style="max-width: 480px;">
  <div class="card-body">
    <div class="text-center">
      <h1 class="card-title h3">Sign up</h1>
      <p class="card-text text-muted">Create an account</p>
    </div>

    <form method="post" class="row g-3 mt-4">
      <div class="col-md-6 form-floating">
        <input name="first_name" id="first_name" placeholder="First name" class="form-control"
          value="<?= htmlspecialchars($model->first_name ?? '') ?>">
        <label for="first_name">First Name</label>
        <?php if ($model->hasError('first_name')): ?>
          <div class="d-block invalid-feedback"><?= $model->getFirstError('first_name') ?></div>
        <?php endif; ?>
      </div>

      <div class="col-md-6 form-floating">
        <input name="last_name" id="last_name" placeholder="Last Name" class="form-control"
          value="<?= htmlspecialchars($model->last_name ?? '') ?>">
        <label for="last_name">Last Name</label>
        <?php if ($model->hasError('last_name')): ?>
          <div class="d-block invalid-feedback"><?= $model->getFirstError('last_name') ?></div>
        <?php endif; ?>
      </div>

      <div class="col-md-12 form-floating">
        <input name="email" type="email" id="email" placeholder="Email Address" class="form-control"
          value="<?= htmlspecialchars($model->email ?? '') ?>">
        <label for="email">Email Address</label>
        <?php if ($model->hasError('email')): ?>
          <div class="d-block invalid-feedback"><?= $model->getFirstError('email') ?></div>
        <?php endif; ?>
      </div>

      <div class="col-md-12 form-floating">
        <input type="password" name="password" id="password" placeholder="your password" class="form-control"
          value="<?= htmlspecialchars($model->password ?? '') ?>">
        <label for="password">Password</label>
        <?php if ($model->hasError('password')): ?>
          <div class="d-block invalid-feedback"><?= $model->getFirstError('password') ?></div>
        <?php endif; ?>
      </div>

      <div class="col-md-12 form-floating">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm your password"
          class="form-control" value="<?= htmlspecialchars($model->confirm_password ?? '') ?>">
        <label for="confirm_password">Confirm Password</label>
        <?php if ($model->hasError('confirm_password')): ?>
          <div class="d-block invalid-feedback"><?= $model->getFirstError('confirm_password') ?></div>
        <?php endif; ?>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-dark btn-lg">Sign up</button>
      </div>

      <p class="text-center text-muted mt-4">Already have an account?
        <a href="/login" class="text-decoration-none">Login here</a>.
      </p>
    </form>
  </div>
</div>
