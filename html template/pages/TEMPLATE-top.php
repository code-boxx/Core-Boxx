<!DOCTYPE html>
<html>
  <head>
    <!-- (A) HEAD -->
    <!-- (A1) TITLE, DESC, CHARSET, FAVICON, VIEWPORT -->
    <title><?=isset($_PMETA["title"])?$_PMETA["title"]:"Core Boxx"?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?=isset($_PMETA["desc"])?$_PMETA["desc"]:"Core Boxx"?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">
    <!-- @TODO NOINDEX?
    <meta name="robots" content="noindex"> -->
    <link rel="icon" href="<?=HOST_ASSETS?>favicon.png" type="image/png">

    <!-- (A2) ANDROID/CHROME -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="white">

    <!-- (A3) IOS APP ICON + MOBILE SAFARI -->
    <link rel="apple-touch-icon" href="<?=HOST_ASSETS?>icon-512.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Hello World">

    <!-- (A4) WINDOWS -->
    <meta name="msapplication-TileImage" content="<?=HOST_ASSETS?>icon-512.png">
    <meta name="msapplication-TileColor" content="#ffffff">

    <?php /* @TODO - ENABLE PWA ONLY IF SIGNED IN?
    if (isset($_SESS["user"])) {} */ ?>
    <!-- (A5) WEB APP MANIFEST -->
    <!-- https://web.dev/add-manifest/ -->
    <link rel="manifest" href="<?=HOST_BASE?>manifest.json">

    <!-- (A6) SERVICE WORKER -->
    <script>if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("<?=HOST_BASE?>CB-worker.js", {scope: "./"});
    }</script>

    <!-- (A7) BOOTSTRAP -->
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="<?=HOST_ASSETS?>bootstrap.min.css">
    <script defer src="<?=HOST_ASSETS?>bootstrap.bundle.min.js"></script>

    <!-- (A8) BURN-IN CSS -->
    <style>
    /* MATERIAL ICONS */
    /* https://fonts.google.com/icons */
    @font-face{font-family:"Material Icons";font-style:normal;font-weight:400;src:url(<?=HOST_ASSETS?>maticon.woff2) format("woff2");}
    .mi{font-family:"Material Icons";font-weight:400;font-style:normal;font-size:24px;letter-spacing:normal;text-transform:none;display:inline-block;white-space:nowrap;word-wrap:normal;direction:ltr;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased}
    .mi-big{font-size:32px}.mi-smol{font-size:18px}
    /* SHOW-HIDE */
    #cb-loading{transition:opacity .3s}.cb-hide{opacity:0;visibility:hidden;height:0}.cb-pg-hide{display:none}
    /* NOW LOADING */
    #cb-loading{width:100vw;height:100vh;position:fixed;top:0;left:0;z-index:999;background:rgba(0,0,0,.7)}#cb-loading .spinner-border{width:80px;height:80px}
    /* COMMON FORM */
    .zebra .d-flex:nth-child(odd){background-color:#efefef}#reader video{height:400px}.pagination{background:#f0f8ff}
    </style>

    <!-- (A9) COMMON INTERFACE -->
    <script>var cbhost={base:"<?=HOST_BASE?>",api:"<?=HOST_API_BASE?>",assets:"<?=HOST_ASSETS?>"};</script>
    <script defer src="<?=HOST_ASSETS?>PAGE-cb.js"></script>

    <!-- (A10) ADDITIONAL SCRIPTS -->
    <?php if (isset($_PMETA["load"])) { foreach ($_PMETA["load"] as $load) {
      if ($load[0]=="s") {
        printf("<script src='%s'%s></script>", $load[1], isset($load[2]) ? " ".$load[2] : "");
      } else {
        printf("<link rel='stylesheet' href='%s'>", $load[1], isset($load[2]) ? " ".$load[2] : "");
      }
    }}
    if (isset($_PMETA)) { unset($_PMETA); } ?>
  </head>
  <body class="bg-light">
    <!-- (B) COMMON SHARED INTERFACE -->
    <!-- (B1) NOW LOADING -->
    <div id="cb-loading" class="d-flex justify-content-center align-items-center cb-hide">
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- (B2) TOAST MESSAGE -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:11">
      <div id="cb-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <span id="cb-toast-icon" class="mi"></span>
          <strong id="cb-toast-head" class="me-auto p-1"></strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="cb-toast-body" class="toast-body"></div>
      </div>
    </div>

    <!-- (B3) MODAL DIALOG BOX -->
    <div id="cb-modal" class="modal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
      <div class="modal-header">
        <h5 id="cb-modal-head" class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="cb-modal-body" class="modal-body"></div>
      <div id="cb-modal-foot" class="modal-footer">
      </div>
    </div></div></div>

    <!-- (C) MAIN NAV BAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top"><div class="container-fluid">
      <!-- (C1) MENU TOGGLE BUTTON -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- (C2) COLLAPSABLE WRAPPER -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- (C2-1) BRANDING LOGO -->
        <a class="navbar-brand" href="<?=HOST_BASE?>">
          <img src="<?=HOST_ASSETS?>ico-light.png" loading="lazy" width="32" height="32"/>
        </a>

        <!-- (C2-2) LEFT MENU ITEMS -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?=HOST_BASE?>demo">
              Demo
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?=HOST_BASE?>users">
              Users
            </a>
          </li>
        </ul>
      </div>

      <!-- (C3) RIGHT ITEMS -->
      <div class="d-flex align-items-center">
        <!-- (C3-1) NOTIFICATIONS -->
        <a class="text-decoration-none text-white mx-2" href="<?=HOST_BASE?>notifications">
          <span class="mi">notifications</span>
          <span id="cart-count" class="badge bg-danger rounded-pill">9</span>
        </a>

        <!-- (C3-2) USER -->
        <a class="dropdown-toggle text-decoration-none text-white mx-2"
          id="userMenuButton"
          data-bs-toggle="dropdown"
          aria-expanded="false">
            <span class="mi">person</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
          <li><a class="dropdown-item" href="<?=HOST_BASE?>login">Login</a></li>
          <li><a class="dropdown-item" href="<?=HOST_BASE?>forgot">Forgot Password</a></li>
          <li><a class="dropdown-item" href="<?=HOST_BASE?>register">Register</a></li>
          <li><a class="dropdown-item" href="<?=HOST_BASE?>myaccount">My Account</a></li>
          <li><div class="dropdown-item" onclick="cb.bye()">Logout</div></li>
        </ul>
      </div>
    </div></nav>

    <!-- (D) MAIN PAGE -->
    <div class="container pt-4">
      <div id="cb-page-1">
