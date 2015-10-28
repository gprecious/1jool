<?php
/**
* 공통변수 설정
**/
define('_YH_', 1);

if (!$yh_path || preg_match("/:\/\//", $yh_path)) {
    echo "<meta charset=UTF-8' /><script> alert('잘못된 방법으로 변수가 정의되었습니다.');</script>";
	exit;
}

/**
* 개발용과 라이브를 나눔
**/
/*DB연결 (라이브와 개발용을 나눔)*/
/*if (strpos($_SERVER['HTTP_HOST'],'klue.kr') === 0) {
	$devFlag = 0;
} else if (strpos($_SERVER['HTTP_HOST'],'dev.klue.kr') === 0) {
	$devFlag = 1;
} else {
	header('Location: http://klue.kr/');
	exit;
}*/


/**
* DB연결
**/
$sql = new mysqli('localhost','root','cjswo124','bigtrue');
$sql->set_charset('UTF8');

/**
* $yh변수 설정
**/
$yh['path'] = $yh_path;
$yh['url'] = 'http://'.$_SERVER['HTTP_HOST'];
$yh['loginSeed'] = 'yh';
$yh['title'] = '한줄약국';
//$yh['devFlag'] = $devFlag;

//release시 자동으로 생성되므로 수정하지 말 것
$yh['ver'] = '0000000000';

/**
* 세션 저장장소 변경
**/
session_save_path($yh['path']."/data/session");
session_start();


/**
* 기본 라이브러리 로드
**/
include_once $yh['path'].'/lib/common_lib.php';

/**
* 로그인 처리
**/
$is_member = 0;
$member['mb_level'] = 1;

//세션이 있는 경우
if ($_SESSION['ss_mb_id']) {
	//$member와 $is_member변수만 세팅하면 끝
	$q = "SELECT * FROM yh_member WHERE mb_id='".$sql->real_escape_string($_SESSION['ss_mb_id'])."'";
	$member = $sql->query($q)->fetch_assoc();
	$member['mb_group'] = explode(',',$member['mb_group']);
	if ($member) $is_member = 1;

//세션이 없는 경우
} else {
    //자동로그인 설정되어있는지 체크
    if ($tmp_mb_id = $sql->real_escape_string(get_cookie("ck_mb_id"))) {
		//인증 키값 생성
		$q = "SELECT * FROM yh_member WHERE mb_id='".$tmp_mb_id."'";
		$mb = $sql->query($q)->fetch_assoc();
		$key = md5($_SERVER[HTTP_USER_AGENT].$mb['mb_pw']);
		
		// 쿠키에 저장된 키와 같다면
		$tmp_key = get_cookie("ck_auto");
		if ($tmp_key == $key && $tmp_key) {
			// 세션에 회원아이디를 저장하여 로그인으로 간주
			$_SESSION['ss_mb_id'] = $tmp_mb_id;

			//$member와 $is_member 세팅
			$member = $mb;
			$member['mb_group'] = explode(',',$member['mb_group']);
			$is_member = 1;
		}
		// $mb 배열변수 해제
		unset($mb, $tmp_key, $key, $tmp_mb_id);
    }
}

//변수들 초기화
unset($q, $r, $yh_path, $devFlag);
?>