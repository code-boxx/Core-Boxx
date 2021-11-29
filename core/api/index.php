<?php
// (A) API MODE FLAG
// use this to tweak your system behaviors
// e.g. if (defined("api_mode")) { json_encode(data) } else { display html }
define("API_MODE", true);

// (B) LOAD CORE
require dirname(__DIR__) . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "GO.php";

// (C) ENFORCE HTTPS (RECOMMENDED)
if (API_HTTPS && empty($_SERVER["HTTPS"])) {
  $_CORE->respond(0, "Please use HTTPS", null, null, 426);
}

// (D) GET CLIENT ORIGIN
$origin = $_SERVER["HTTP_ORIGIN"] ??
          $_SERVER["HTTP_REFERER"] ??
          $_SERVER["REMOTE_ADDR"] ??
          "" ;
$originHost = parse_url($origin, PHP_URL_HOST);

// (E) CORS SUPPORT
// (E1) FALSE - ONLY CALLS FROM HOST_NAME
if (API_CORS===false && $originHost!=HOST_NAME) { $access = false; }

// (E2) STRING - ALLOW CALLS FROM API_CORS ONLY
else if (is_string(API_CORS) && $originHost!=API_CORS) { $access = false; }

// (E3) ARRAY - SPECIFIED DOMAINS IN API_CORS ONLY
else if (is_array(API_CORS) && !in_array($originHost, API_CORS)) { $access = false; }

// (E4) TRUE - ANYTHING GOES
else { $access = true; $origin = "*"; }

// (E5) ACCESS DENIED
if ($access === false) {
  $_CORE->respond(0, "Calls from $origin not allowed", null, null, 403);
}

// (E6) OUTPUT CORS HEADERS IF REQUIRED
if ($originHost != HOST_NAME) {
  header("Access-Control-Allow-Origin: $origin");
  if (API_CORS_CREDS) { header("Access-Control-Allow-Credentials: true"); }
}

// (F) AUTO REGENERATE HTACCESS IF NOT FOUND
$htaccess = PATH_API . ".htaccess";
if (!file_exists($htaccess)) {
  file_put_contents($htaccess, implode("\r\n", [
    "RewriteEngine On",
    "RewriteBase " . HOST_API,
    "RewriteRule ^index\.php$ - [L]",
    "RewriteCond %{REQUEST_FILENAME} !-f",
    "RewriteCond %{REQUEST_FILENAME} !-d",
    "RewriteRule . " . HOST_API . "index.php [L]"
  ]));
}

// (G) PARSE URL PATH INTO AN ARRAY
// (G1) EXTRACT PATH FROM FULL URL
// e.g. http://site.com/api/foo/bar/ >>> $path="/api/foo/bar/"
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// (G2) REMOVE "API" SEGMENT FROM PATH
// e.g. $path="/api/foo/bar/" >>> $path="foo/bar/"
if (substr($path, 0, strlen(HOST_API)) == HOST_API) {
  $path = substr($path, strlen(HOST_API));
}

// (G3) EXPLODE INTO AN ARRAY
// e.g. $path="foo/bar/" >>> $path=["foo", "bar"]
$path = explode("/", rtrim($path, "/"));

// (H) MANAGE REQUEST
// (H1) VALID API REQUEST?
// $_mod = $path[0] = module code
// $_req = $path[1] = request
$valid = count($path)==2;
if ($valid) { $valid = $path[0]!="index"; }
if ($valid) {
  $_MOD = $path[0];
  $_REQ = $path[1];
  $valid = file_exists(PATH_API . "API-$_MOD.php");
}

// (H2) LOAD API HANDLER
if ($valid) {
  unset($origin); unset($originHost); unset($access);
  unset($htaccess); unset($path); unset($valid);
  require PATH_API . "API-$_MOD.php";
} else { $_CORE->respond(0, "Invalid request", null, null, 400); }
