<?php
$_CSS[] = $yh['path'].'/_admin/css/head.css';
include_once $yh['path'].'/head_clean.php';
?>
<div class="side">
	<div class="logo" onclick="location.href=yh_path">YH</div>
	<div class="menuWrap">
		<div class="e"><a href='<?=$yh['path']?>/_admin/'>기본관리</a></div>
		<div class="e"><a href='<?=$yh['path']?>/_admin/memberList.php'>사용자관리</a></div>
		<div class="e"><a href='<?=$yh['path']?>/_admin/boardList.php'>게시판관리</a></div>
	</div>
</div>
<div class="main">
	<div class='inner'>