<div class="w-100">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control <?= $model->hasError('title') ? 'is-invalid' : '' ?>" id="title"
                name="title" value="<?= htmlspecialchars($model->title ?? '', ENT_QUOTES) ?>">
              <?php if ($model->hasError('title')): ?>
                <div class="invalid-feedback"><?= $model->getFirstError('title') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="content" class="form-label">Content</label>
              <textarea class="form-control <?= $model->hasError('content') ? 'is-invalid' : '' ?>" id="content"
                name="content" rows="10"><?= htmlspecialchars($model->content ?? '', ENT_QUOTES) ?></textarea>
              <?php if ($model->hasError('content')): ?>
                <div class="invalid-feedback"><?= $model->getFirstError('content') ?></div>
              <?php endif; ?>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="reading_time" class="form-label">Reading Time (minutes)</label>
                  <input type="number" class="form-control <?= $model->hasError('reading_time') ? 'is-invalid' : '' ?>"
                    id="reading_time" name="reading_time" value="<?= $model->reading_time ?? 5 ?>">
                  <?php if ($model->hasError('reading_time')): ?>
                    <div class="invalid-feedback"><?= $model->getFirstError('reading_time') ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="image" class="form-label">Featured Image</label>
                  <input type="file" class="form-control <?= $model->hasError('image') ? 'is-invalid' : '' ?>"
                    id="image" name="image" accept="image/*">
                  <?php if ($model->hasError('image')): ?>
                    <div class="invalid-feedback"><?= $model->getFirstError('image') ?></div>
                  <?php endif; ?>
                  <div class="form-text">Upload JPG, PNG, or GIF (max 5MB)</div>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Create Post</button>
              <a href="/posts" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
