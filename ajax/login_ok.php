<?php
include_once './_common.php';

/**
* POST변수 정리
**/
$mb_id = $sql->real_escape_string(trim($_POST['inputEmail']));
$mb_pw = $_POST['inputPassword'];
$nowDate = date('Y-m-d H:i:s');
$encoded_mb_pw = md5($yh['loginSeed'].$mb_pw);
/**
* 일치하는 사용자가 있는지 검증
**/
//해당 id, pw와 일치하는 회원이 있는지 검사

$q = "SELECT * FROM yh_member WHERE mb_id='".$mb_id."' && mb_pw='".$encoded_mb_pw."'";

$mb = $sql->query($q)->fetch_assoc();

//없으면 오류뿜고 종료
if (!$mb) {
	$result['status'] = 'fail';
	$result['msg'] = "로그인에 실패하였습니다\n\n아이디 또는 비밀번호를 확인해주세요.";
	echo json_encode($result);
	exit;
}

/**
* 세션 만들어줌
**/
//있으면 mb_lastLogin 갱신하고 로그인 처리
$q = "UPDATE yh_member SET mb_loginTime='".$nowDate."' WHERE no='".$mb['no']."'";
$sql->query($q);

$_SESSION['ss_mb_id'] = $mb['mb_id'];
$_SESSION['ss_mb_no'] = $mb['no'];	//추후 멤버번호 사용을 위해

//자동로그인 설정
$key = md5($_SERVER['HTTP_USER_AGENT'].$mb['mb_pw']);
set_cookie('ck_mb_id', $mb['mb_id'], 86400*365*10);
set_cookie('ck_auto', $key, 86400*365*10);

//결과 출력
$result['status'] = 'success';
echo json_encode($result);
?>