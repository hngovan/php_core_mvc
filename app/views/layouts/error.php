<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($this->title ?? 'Home', ENT_QUOTES, 'UTF-8') ?></title>
  <style>
    .error-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .error-content {
      text-align: center;
    }

    .error-content h1 {
      font-size: 6rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .error-content p {
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }

    .lottie-animation {
      max-width: 400px;
      margin-bottom: 2rem;
    }
  </style>
</head>

<body>
  <div class="error-container">
    <div class="lottie-animation"></div>
    <div class="error-content">
      {{ content }}
    </div>
    <a href="/" class="btn btn-primary">Go to Homepage</a>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
  <script>
    console.log
    const animation = lottie.loadAnimation({
      container: document.querySelector('.lottie-animation'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: 'https://lottie.host/d987597c-7676-4424-8817-7fca6dc1a33e/BVrFXsaeui.json'
    });
  </script>
</body>

</html>
