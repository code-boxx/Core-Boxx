<?php
// CORE BOXX FIRE STARTER
// BY DEFAULT - STARTS SESSION + LOAD DATABASE MODULE
// FEEL FREE TO TWEAK THIS STARTER SCRIPT TO YOUR OWN NEEDS

// (A) SETTINGS
// (A1) AUTOMATIC SYSTEM PATH
define("PATH_LIB", __DIR__ . DIRECTORY_SEPARATOR);
define("PATH_BASE", dirname(PATH_LIB) . DIRECTORY_SEPARATOR);
define("PATH_API", PATH_BASE . "api" . DIRECTORY_SEPARATOR);

// (A2) HOST
define("HOST_BASE", "http://localhost/"); // CHANGE TO YOUR OWN!
define("HOST_BASE_PATH", parse_url(HOST_BASE, PHP_URL_PATH));
define("HOST_API", "/api/");

// (A3) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define("DB_HOST", "localhost");
define("DB_NAME", "test");
define("DB_CHARSET", "utf8");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// (A4) ENFORCE HTTPS FOR API ENDPOINT
define("API_HTTPS", false);

// (A5) PAGINATION
define("PAGE_PER", 20); // 20 ENTRIES PER PAGE BY DEFAULT

// (B) CORE START
// (B1) START SESSION
session_start();

// (B2) CORE LIBRARY
require PATH_LIB . "LIB-Core.php";
$_CORE = new CoreBoxx();

// (B3) PHP ERROR HANDLING
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 0);
// ini_set("log_errors", 0);
// ini_set("error_log", "error.log");

// (B4) GLOBAL ERROR HANDLING (CHANGE AS DESIRED)
// SET ERR_SHOW TO FALSE ON LIVE SYSTEMS!
// OR CREATE YOUR OWN $_CORE->BOOBOO->OUCH() TO HANDLE ERRORS
define("ERR_SHOW", true);
function _CORERR ($ex) {
  global $_CORE;
  // CUSTOM ERROR CONTROL
  if ($_CORE->loaded("BooBoo")) { $_CORE->BooBoo->ouch($ex); }

  // OR JUST OUTPUT AN ERROR MESSAGE
  else {
    $_CORE->respond(0,
    ERR_SHOW ? $ex->getMessage() : "OPPS! An error has occured.",
    ERR_SHOW ? [
      "code" => $ex->getCode(),
      "file" => $ex->getFile(),
      "line" => $ex->getLine()
      ] : null
    );
  }
}
set_exception_handler("_CORERR");

// (B5) DEFAULT MODULES TO LOAD
$_CORE->load("DB");
// ADD MORE IF REQUIRED, E.G. $_CORE->load("User");
