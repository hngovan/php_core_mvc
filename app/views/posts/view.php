<div class="row mb-2 w-100">
  <div class="col-md-12">
    <div class="card shadow-sm">
      <img src="<?= $post->getImageUrl() ?>" class="card-img-top bg-secondary"
        alt="<?= htmlspecialchars($post->title, ENT_QUOTES) ?>" style="height: 400px; object-fit: contain;">
      <div class="card-body">
        <h1 class="card-title"><?= htmlspecialchars($post->title, ENT_QUOTES) ?></h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <small class="text-muted">
              by <strong><?= htmlspecialchars($post->getAuthorName(), ENT_QUOTES) ?></strong>
            </small>
            <br>
            <small class="text-muted">
              <i class="bi bi-clock"></i> <?= $post->reading_time ?> minutes read
              <span class="mx-2">•</span>
              <i class="bi bi-calendar"></i> <?= date('M j, Y', strtotime($post->created_at)) ?>
            </small>
          </div>
          <?php if ($post->canEdit()): ?>
            <div class="btn-group">
              <a href="/posts/<?= $post->id ?>/edit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
              </a>
            </div>
          <?php endif; ?>
        </div>
        <hr>
        <div class="card-text">
          <?= nl2br(htmlspecialchars($post->content, ENT_QUOTES)) ?>
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
      <a href="/posts" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Posts
      </a>
    </div>
  </div>
</div>
