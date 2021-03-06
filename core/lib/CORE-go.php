<?php
// (A) LOAD CONFIG + CORE LIBRARY
require __DIR__ . DIRECTORY_SEPARATOR . "CORE-config.php";
require PATH_LIB . "LIB-Core.php";
$_CORE = new CoreBoxx();

// (B) GLOBAL ERROR HANDLING
function _CORERR ($ex) { global $_CORE; $_CORE->ouch($ex); }
set_exception_handler("_CORERR");

// (C) LOAD DEFAULT MODULES + STARTING SEQUENCE
// session_start(); // START SESSION IF YOU WANT
$_CORE->load("DB");

// @TODO - ENABLE IF USING DATABASE OPTIONS
// $_CORE->load("Options");

// @TODO - ENABLE IF USING USER MODULE
// $_CORE->load("Session");

// ADD MORE MODULES AS REQUIRED
