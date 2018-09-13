<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <base href="/">
  <link rel="stylesheet" href="assets/css/core/framework.css">
  <!-- <link rel="stylesheet" href="assets/css/rich-text-editor.css"> -->
  <?php foreach($stylesheets as $stylesheet): ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars( $stylesheet ); ?>.css">
  <?php endforeach; ?>
  <meta name="csrf" value="<?php echo htmlspecialchars( csrf() ); ?>">
  <meta name="keywords" value="<?php echo htmlspecialchars( $keywords ); ?>">
  <meta name="description" value="<?php echo htmlspecialchars( $description ); ?>">
  <title><?php echo htmlspecialchars( $title ); ?></title>
</head>
<body>

  <header class="dark">
    <nav>
      <!-- Place your navigation menu here -->
      <ul class="navbar float-left">
        <li><a href="#">Menu 1</a></li>
        <li><a href="#">Menu 2</a></li>
        <li><a href="#">Menu 3</a></li>
      </ul> 
     
      <?php if(AUTHENTICATION === 'ON'): ?>
        <?php require $__includes__ . "includes.auth-nav.php"; ?>
      <?php endif; ?>
    </nav>
    
  </header>

  <main class="container">
    <?php require $__content__; ?>
  </main>

  <footer>

  </footer>

  <script src="assets/js/core/framework.js"></script>
  <!-- <script src="assets/js/core/rich-text-editor.js"></script> -->
  <!-- <script src="assets/js/main.js"></script> -->
  <?php foreach($scripts as $script): ?>
    <script src="assets/js/<?php echo htmlspecialchars( $script ); ?>.js"></script>
  <?php endforeach; ?>
</body>
</html>