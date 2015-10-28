<?php
include_once './_common.php';

/**
* POST변수정리
**/
$no = intval($_POST['no']);
$mb_id = trim($_POST['mb_id']);
$mb_pw = $_POST['mb_pw'];
$mb_name = trim($_POST['mb_name']);
$mb_nick = trim($_POST['mb_nick']);
$mb_block = intval($_POST['mb_block']);
$nowDate = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);

/**
* POST변수 검증
**/
//새로운 회원 추가일 경우 아이디 검사
if (!$no) {
	if (!preg_match('/^[_0-9a-zA-Z\-]{4,20}$/Uu',$mb_id)) {
		$result['status'] = 'fail';
		$result['msg'] = '아이디가 형식에 맞지 않습니다.';
		echo json_encode($result);
		exit;
	}
}

//비밀번호가 형식에 맞는지 검사
if ($mb_pw != '' && !preg_match('/^[a-zA-Z0-9!@#\$%\^&\*\(\)\\\|\[\]\{\};:\'\",\.<>\/\?`~ ]{6,20}$/Uu',$mb_pw)) {
	$result['status'] = 'fail';
	$result['msg'] = '비밀번호가 형식에 맞지 않습니다.';
	echo json_encode($result);
	exit;
}

//이름이 형식에 맞는지 검사
if (!preg_match('/^[가-힣a-zA-Z ]{2,20}$/Uu',$mb_name) || strpos($mb_name,'  ') !== false) {
	$result['status'] = 'fail';
	$result['msg'] = '이름이 형식에 맞지 않습니다.';
	echo json_encode($result);
	exit;
}

//닉네임이 형식에 맞는지 검사
if (!preg_match('/^[가-힣a-zA-Z0-9 \-_]{2,20}$/Uu',$mb_nick) || strpos($mb_nick,'  ') !== false) {
	$result['status'] = 'fail';
	$result['msg'] = '닉네임이 형식에 맞지 않습니다.';
	echo json_encode($result);
	exit;
}

//닉네임이 중복되는지 검사
$q = "SELECT 1 FROM yh_member WHERE mb_nick='".$sql->real_escape_string($mb_nick)."' AND no != '".$no."'";
$row = $sql->query($q)->fetch_assoc();
if ($row) {
	$result['status'] = 'fail';
	$result['msg'] = '사용중인 닉네임입니다.';
	echo json_encode($result);
	exit;
}

/**
* DB반영
**/
$mb_name = $sql->real_escape_string($mb_name);
$mb_nick = $sql->real_escape_string($mb_nick);
$mb_stNum = $sql->real_escape_string($mb_stNum);
$mb_group = '';
for($i=0;$i<count($_POST['mb_group']);$i++) {
	$mb_group .= $_POST['mb_group'][$i].',';
}
$mb_group = $sql->real_escape_string(substr($mb_group,0,-1));

if ($no) {
	$q = "UPDATE yh_member SET
		".($mb_pw!=''?"mb_pw='".md5($yh['loginSeed'].$mb_pw)."',":"")."
		mb_name='".$mb_name."',
		mb_nick='".$mb_nick."',
		mb_group='".$mb_group."',
		mb_block='".$mb_block."'
	WHERE no='".$no."'";
	$sql->query($q);
} else {
	$q = "INSERT INTO yh_member (mb_id, mb_pw, mb_nick, mb_name, mb_group, mb_block, mb_loginTime, mb_joinTime) VALUES('".$mb_id."','".md5($yh['loginSeed'].$mb_pw)."','".$mb_nick."','".$mb_name."','".$mb_group."','".$mb_block."','0000-00-00 00:00:00','".$nowDate."')";
	$sql->query($q);
}

$result['status'] = 'success';
echo json_encode($result);
?>