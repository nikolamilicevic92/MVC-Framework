<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <base href="/">
  <link rel="stylesheet" href="assets/css/core/framework.css">
  <!-- <link rel="stylesheet" href="assets/css/rich-text-editor.css"> -->
  @foreach($stylesheets as $stylesheet)
    <link rel="stylesheet" href="{{ $stylesheet }}.css">
  @endforeach
  <meta name="csrf" value="{{ csrf() }}">
  <meta name="keywords" value="{{ $keywords }}">
  <meta name="description" value="{{ $description }}">
  <title>{{ $title }}</title>
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
     
      @if(AUTHENTICATION === 'ON')
        @include(auth-nav)
      @endif
    </nav>
    
  </header>

  <main class="container">
    @content
  </main>

  <footer>

  </footer>

  <script src="assets/js/core/framework.js"></script>
  <!-- <script src="assets/js/core/rich-text-editor.js"></script> -->
  <!-- <script src="assets/js/main.js"></script> -->
  @foreach($scripts as $script)
    <script src="assets/js/{{ $script }}.js"></script>
  @endforeach
</body>
</html>