<?php
chdir("../../civi4");
require_once("includes/bootstrap.inc");
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

header("Content-Type: application/json; charset=utf-8");

require_once("sog_api.php");
$sogApi = new SogApi();

$response = array(
  "error" => 0
);

$interval = $_POST["interval"];
$amount   = $_POST["amount"];

if (in_array($interval, array("vierteljährlich", "jährlich", "monatlich")) &&
    $amount >= 1) {

  $membership   = $sogApi->getMembershipForDrupalUser($user->uid);
  $membershipID = $membership->values[0]->{"api.Membership.getsingle"}->id;
  $contactID    = $membership->values[0]->contact_id;

  $sogApi->updateMembershipInterval($membershipID, $contactID, $interval);

  if ($interval == "vierteljährlich") {
    $amount = str_replace(".", ",", $amount * 3);
  } elseif ($interval == "jährlich") {
    $amount = str_replace(".", ",", $amount * 12);
  } else {
    $amount = str_replace(".", ",", $amount * 1);
  }

  $sogApi->updateMembershipPayment($membershipID, $contactID, $amount);
  echo json_encode($response);
  exit;

}

$response["error"] = 1;
echo json_encode($response);
exit;
?>
