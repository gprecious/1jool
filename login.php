<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/login.css';
$_JS[] = $yh['path'].'/js/login.js';
include_once './head.php';
?>
<div id="login">
	<input type='text' name='mb_id' onkeydown='if (event.keyCode==13) doLogin();' />
	<input type='password' name='mb_pw' onkeydown='if (event.keyCode==13) doLogin();' />
	<button type='button' onclick='doLogin();'>로그인</button>
</div>
<?php
include_once './tail.php';
?>