<?php
include_once './_common.php';

$email = $_POST["email"];

$checkQuery = "SELECT * FROM `yh_member` WHERE `mb_id` = '$email'";

try {
	$result = $sql->query($checkQuery);
	if (mysqli_num_rows($result) > 0) {
		echo "1";
	} else {
		echo "0";
	}
} catch (Exception $e) {
	echo "Exception occured " . $e->getMessage();
}
?>