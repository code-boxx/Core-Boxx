<?php
// @TODO - YOU MIGHT WANT TO LIMIT "UPDATE" OR "DELETE" COMMENTS.
// @TODO - YOU MIGHT WANT TO LIMIT TO USERS THAT ARE SIGNED IN ONLY.
switch ($_REQ) {
  // (A) INVALID REQUEST
  default:
    $_CORE->respond(0, "Invalid request");
    break;

  // (B) SAVE A COMMENT
  case "save":
    $_CORE->autoAPI("Comments", "save");
    break;

  // (C) DELETE A COMMENT
  case "del":
    $_CORE->autoAPI("Comments", "del");
    break;

  // (D) GET COMMENTS
  case "get":
    // @TODO - YOU MIGHT WANT TO ADD YOUR OWN PAGINATION
    $_CORE->respond(1, null, $_CORE->Comments->get($_POST['id']));
    break;
}