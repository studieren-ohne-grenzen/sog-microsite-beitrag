<?php
chdir("../../civi4");
require_once("includes/bootstrap.inc");
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

header("Content-Type: application/json; charset=utf-8");

require_once("sog_api.php");
$sogApi = new SogApi();

$response = array(
  "username" => "",
  "interval" => "",
  "amount"   => 0
);

$membership = $sogApi->getMembershipForDrupalUser($user->uid);
$interval   = $membership->values[0]->{"api.Membership.getsingle"}->custom_36;
$amount     = $membership->values[0]->{"api.Membership.getsingle"}->custom_37;
$amount     = str_replace(",", ".", $amount);

if ($interval == "vierteljährlich") {
  $amount = $amount / 3;
} elseif ($interval == "jährlich") {
  $amount = $amount / 12;
} else {
  $amount = $amount / 1;
}

$response["username"] = $user->name;
$response["interval"] = $interval;
$response["amount"]   = $amount;

echo json_encode($response);
exit;
?>
