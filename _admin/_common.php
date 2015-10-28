<?php
$yh_path = ".."; // common.php 의 상대 경로
include_once("$yh_path/common.php");

if (!$is_member || !in_array('admin',$member['mb_group'])) {
	echo "Permission Denied!";
	exit;
}
?>