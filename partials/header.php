<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="msapplication-TileColor" content="#0E0E0E">
  <meta name="template-color" content="#0E0E0E">
  <meta name="description" content="Index page">
  <meta name="keywords" content="index, page">
  <meta name="author" content="">
  <link rel="shortcut icon" type="image/x-icon" href="assets/imgs/template/fav.png">
  <link href="assets/css/style.css?v=2.0.0" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Montserrat:ital@0;1&family=Raleway:ital,wght@0,100..900;1,100..900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
   <!-- <link href="https://db.onlinewebfonts.com/c/xxxxxxxxxxxxxxxx?family=AkiraExpanded" rel="stylesheet"> -->


  <title>Cybertron-Labs</title>
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>

<body class="dark-mode page-transition alith-magic-cursor">
  <div class="page-loader">
    <div class="page-loader-logo "><img alt="neuron" src="assets/imgs/page/homepage1/logo.svg"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
  <div class="follower"></div>
  <div class="cursor"><span class="dot"></span><span class="play"><img src="assets/imgs/template/icons/cursor-play.svg"
        alt="neuron"><span>Play</span></span><span class="drag"><img src="assets/imgs/template/icons/cursor-drag.svg"
        alt="neuron"><span>Drag</span></span><span class="view"><img src="assets/imgs/template/icons/cursor-view.svg"
        alt="neuron"><span>View</span></span></div>
  
  <div class="scroll-container" id="scroll-container">

 <header class="header sticky-bar">
  <div class="container-fluid">
    <div class="main-header d-flex justify-content-between align-items-center">
      
      <!-- Logo -->
      <div class="header-logo">
        <a href="index.php"><img src="assets/imgs/page/homepage1/logo.svg" alt="neuron" class="img-900"></a>
      </div>

      <!-- Desktop Menu -->
      <nav class="nav-main-menu d-none d-xl-block">

         <div class="bgh">
        <ul class="main-menu">
          <li><a href="index.php">Home</a></li>
          <li><a href="#case.php">What We Do</a></li>
          <li><a href="who-we-are.php">Who We Are</a></li>
          <li><a href="#Blog">How we Deliver</a></li>
          <li><a href="join.php">Join Cybertron</a></li>
        </ul>
        </div>
      </nav>

      <!-- Mobile Menu Toggle Button -->
      <div class="mobile-menu-toggle d-xl-none">
        <button id="mobileMenuBtn">☰</button>
      </div>

      <!-- Get in Touch Button -->
      <div class="header-account d-none d-xl-block">
        <a href="contact-us.php" class="btn btn-default grow-up">Get in Touch</a>
      </div>

    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="mobile-menu d-none">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="#case.php">What We Do</a></li>
      <li><a href="who-we-are.php">Who We Are</a></li>
      <li><a href="#Blog">How we Deliver</a></li>
      <li><a href="join.php">Join Cybertron</a></li>
      <li><a href="contact-us.php">Get in Touch</a></li>
    </ul>
  </div>
</header>

<!-- JS for toggling mobile menu -->
<script>
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('d-none');
  });
</script>

<!-- Basic CSS -->
