<?php
class Route extends Core {
  // (A) PROPERTIES
  private $routes = []; // manual route override
  private $wild = []; // wildcard route override

  // (B) GENERATE HTACCESS FILE
  function init () {
    // (B1) BASE HTACCESS
    $htaccess = PATH_BASE . ".htaccess";
    if (file_put_contents($htaccess, implode("\r\n", [
      "RewriteEngine On",
      "RewriteBase " . HOST_BASE_PATH,
      "RewriteRule ^index\.php$ - [L]",
      "RewriteCond %{REQUEST_FILENAME} !-f",
      "RewriteCond %{REQUEST_FILENAME} !-d",
      "RewriteRule . " . HOST_BASE_PATH . "index.php [L]"
    ])) === false) { throw new Exception("Failed to create $htaccess"); }

    // (B2) API HTACCESS
    $htaccess = PATH_API . ".htaccess";
    if (file_put_contents($htaccess, implode("\r\n", [
      "RewriteEngine On",
      "RewriteBase " . HOST_API,
      "RewriteRule ^index\.php$ - [L]",
      "RewriteRule . " . HOST_API . "index.php [L]"
    ])) === false) { throw new Exception("Failed to create $htaccess"); }
    return true;
  }

  // (C) ADD MANUAL ROUTES
  //  $route : target route, see index.php for examples
  //  $resolve : resolves to this file in the pages/ folder
  function add ($route, $resolve) {
    if (substr($route, -2) == "/*") {
      $this->wild[substr($route, 0, -1)] = $resolve;
    } else { $this->routes[rtrim($route)] = $resolve; }
  }

  // (D) RESOLVE URL ROUTE
  function run () {
    // (D1) GET URL PATH
    // HTTP://SITE.COM/ > "/"
    // HTTP://SITE.COM/HELLO/WORLD/ > "HELLO/WORLD/"
    $_PATH = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if (substr($_PATH, 0, strlen(HOST_BASE_PATH)) == HOST_BASE_PATH) {
      $_PATH = substr($_PATH, strlen(HOST_BASE_PATH));
    }
    $_PATH = rtrim($_PATH, "/") . "/"; // current url path
    $loaded = false;

    // (D2) EXACT ROUTES HAVE PRECEDENCE
    if (isset($this->routes[$_PATH])) {
      $loaded = true;
      $this->load($this->routes[$_PATH], $_PATH);
    }

    // (D3) WILDCARD
    if (!$loaded && count($this->wild)>0) {
      $pathlen = strlen($_PATH);
      foreach ($this->wild as $p=>$f) {
        $wildlen = strlen($p);
        if ($wildlen > $pathlen) { continue; }
        if (substr($_PATH, 0, $wildlen) == $p) {
          $loaded = true;
          $_PATH = substr($_PATH, $wildlen);
          if ($_PATH=="") { $_PATH = "/"; }
          unset($pathlen); unset($wildlen);
          $this->load($f, $_PATH);
        }
      }
    }

    // (D4) WILL AUTO RESOLVE OTHERWISE
    if (!$loaded) { $this->pathload($_PATH); }
  }

  // (E) LOAD PAGE FROM GIVEN PATH
  //  $_PATH : current url path (or whatever you want to start)
  //  $prefix : file prefix, defaults to "page"
  function pathload ($_PATH, $prefix="PAGE") {
    $this->load(($_PATH=="/"
      ? "$prefix-home.php"
      : "$prefix-" . str_replace("/", "-", rtrim($_PATH, "/")) . ".php"),
    $_PATH);
  }

  // (F) LOAD TARGET FILE IN THE PAGE/ FOLDER
  //  $_PAGE : exact file name to load
  //  $_PATH : current url path (or whatever you want to pick up in $file)
  function load ($_PAGE, $_PATH="") {
    if (file_exists(PATH_PAGES . $_PAGE)) {
      global $_CORE; // all pages can access the core engine
      require PATH_PAGES . $_PAGE;
    } else {
      http_response_code(404);
      require PATH_PAGES . "PAGE-404.php";
    }
  }
}