<div class="d-flex justify-content-between align-items-center mb-4 w-100">
  <h2>Blog Posts</h2>
  <a href="/posts/create" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Create New Post
  </a>
</div>

<?php if (empty($posts)): ?>
  <div class="text-center py-5 w-100">
    <h4>No posts found</h4>
    <p class="text-muted">Be the first to create a post!</p>
  </div>
<?php else: ?>
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($posts as $post): ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="<?= $post->getImageUrl() ?>" class="card-img-top"
            alt="<?= htmlspecialchars($post->title, ENT_QUOTES) ?>" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($post->title, ENT_QUOTES) ?></h5>
            <p class="card-text"><?= htmlspecialchars($post->getExcerpt(150), ENT_QUOTES) ?></p>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <div class="d-flex justify-content-between align-items-center">
              <div class="btn-group">
                <a href="/posts/<?= $post->id ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                <?php if ($post->canEdit()): ?>
                  <a href="/posts/<?= $post->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                <?php endif; ?>
              </div>
              <small class="text-muted">
                <i class="bi bi-clock"></i> <?= $post->reading_time ?> mins read
              </small>
            </div>
            <div class="mt-2">
              <small class="text-muted">
                by <?= htmlspecialchars($post->getAuthorName(), ENT_QUOTES) ?>
              </small>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

