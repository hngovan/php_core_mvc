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
                  <label for="image" class="form-label">Featured Image</label>
                  <input type="file" class="form-control <?= $model->hasError('image') ? 'is-invalid' : '' ?>"
                    id="image" name="image" accept="image/*">
                  <!-- Preview container -->
                  <div id="image-preview-container" class="mb-2">
                    <?php if ($model->image): ?>
                      <div class="current-image">
                        <img src="<?= $model->getImageUrl() ?>" alt="Current image" class="img-thumbnail"
                          style="max-height: 150px;">
                        <small class="d-block mt-1">Current image</small>
                      </div>
                    <?php endif; ?>
                  </div>
                  <?php if ($model->hasError('image')): ?>
                    <div class="invalid-feedback"><?= $model->getFirstError('image') ?></div>
                  <?php endif; ?>
                  <div class="form-text">Upload JPG, PNG, or GIF (max 5MB) - Leave empty to keep current image</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="reading_time" class="form-label">Reading Time (minutes)</label>
                  <input type="number" class="form-control <?= $model->hasError('reading_time') ? 'is-invalid' : '' ?>"
                    id="reading_time" name="reading_time" value="<?= $model->reading_time ?? 5 ?>" min="1">
                  <?php if ($model->hasError('reading_time')): ?>
                    <div class="invalid-feedback"><?= $model->getFirstError('reading_time') ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="submit" class="btn btn-primary">Update Post</button>
              <a href="/posts/<?= $model->id ?>" class="btn btn-secondary">Cancel</a>
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                data-bs-target="#deleteModal">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this post?</p>
        <p><strong><?= htmlspecialchars($model->title ?? '', ENT_QUOTES) ?></strong></p>
        <p class="text-muted">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="post" action="/posts/<?= $model->id ?>/delete" class="d-inline">
          <button type="submit" class="btn btn-danger">Delete Post</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('image-preview-container');

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();

      reader.onload = function (e) {
        previewContainer.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                <small class="d-block mt-1">New image preview</small>
            `;
      }

      reader.readAsDataURL(file);
    } else if (!file) {
      const currentImage = document.querySelector('.current-image');
      if (currentImage) {
        previewContainer.innerHTML = currentImage.outerHTML;
      } else {
        previewContainer.innerHTML = '';
      }
    }
  });
</script>
