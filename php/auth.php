<?php
chdir("../../civi4");
require_once("includes/bootstrap.inc");
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

header("Content-Type: application/json; charset=utf-8");

$response = array(
  "username" => "",
  "error"    => 0
);

if (user_is_logged_in()) {
  $response["username"] = $user->name;
  echo json_encode($response);
  exit;
}

if ($_POST["username"] && $_POST["password"]) {
  $loginArray = array(
    "name" => $_POST["username"],
    "pass" => $_POST["password"]
  );

  $login = user_authenticate($loginArray);

  if ($login) {
    $response["username"] = $user->name;
    echo json_encode($response);
    exit;
  }

  $response["error"] = 1;
  echo json_encode($response);
  exit;
}

echo json_encode($response);
exit;
?>
