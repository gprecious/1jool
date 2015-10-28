<?php
/**
* 기본 라이브러리
**/
// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire) {
    setcookie(md5('yh'.$cookie_name), base64_encode($value), time()+$expire, '/');
}


// 쿠키변수값 얻음
function get_cookie($cookie_name) {
    return base64_decode($_COOKIE[md5('yh'.$cookie_name)]);
}

//정해진 자리수만큼 0을 추가해서 맞추는 함수
function zerofill($num,$len) {
	if (strlen($num) >= $len) return $num;
	if (strlen($num) < $len) return str_repeat('0',$len-strlen($num)).$num;
}

//htmlentitle의 축약함수
function escapeHtml($str) {
	return htmlspecialchars($str,ENT_QUOTES | ENT_IGNORE, "UTF-8");
}

//SNS식 시간 표기
function snsTime($timestamp) {
	$b = time() - $timestamp;

	if ($b<30) {return '몇 초전';}
	else if ($b<3600) {return round($b/60).'분 전';}
	else if ($b<86400) {return round($b/3600).'시간 전';}
	else if ($b<2592000) {return round($b/86400).'일 전';}
	else if ($b<31536000) {return round($b/2592000).'달 전';}
	else {return round($b/31536000).'년 전';}
}

//nl2br의 반대
function br2nl($str) {
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $str);
}
?>