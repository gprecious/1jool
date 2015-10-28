<?php
include_once './_common.php';

session_destroy();
set_cookie('ck_mb_id', '', 0);
set_cookie('ck_auto', '', 0);
header("Location: ".$yh['path']."/");

?>