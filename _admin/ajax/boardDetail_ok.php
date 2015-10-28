<?php
include_once './_common.php';
include_once $yh['path'].'/lib/yhBoard/yhBoard.php';

/**
* POST변수 정리
**/
$no = intval($_POST['no']);
$bo_name = $sql->real_escape_string(trim($_POST['bo_name']));
$bo_tableName = $sql->real_escape_string(trim($_POST['bo_tableName']));
$bo_basicPerm = $_POST['bo_basicPerm'];
$bo_groupPerm = $_POST['bo_groupPerm'];
$bo_fileUploadFlag = intval($_POST['bo_fileUploadFlag']);
$bo_fileUploadSize = intval($_POST['bo_fileUploadSize']);
$bo_htmlFlag = intval($_POST['bo_htmlFlag']);

/**
* 기본 권한 정리
**/
$bo_basicPerm2 = array();
$bo_basicPerm2['r'] = intval($bo_basicPerm['r']);
$bo_basicPerm2['w'] = intval($bo_basicPerm['w']);
$bo_basicPerm2['m'] = intval($bo_basicPerm['m']);

/**
* 그룹권한 정리
**/
//그룹 목록 뽑고
$q = "SELECT gp_id FROM yh_group ORDER BY no ASC";
$r = $sql->query($q);
$bo_groupPerm2 = array();
while ($row = $r->fetch_assoc()) {
	//기본권한을 따르지 않는 경우만 저장
	if ($bo_groupPerm[$row['gp_id']]['defaultFlag'] != 1) {
		$bo_groupPerm2[$row['gp_id']]['r'] = intval($bo_groupPerm[$row['gp_id']]['r']);
		$bo_groupPerm2[$row['gp_id']]['w'] = intval($bo_groupPerm[$row['gp_id']]['w']);
		$bo_groupPerm2[$row['gp_id']]['m'] = intval($bo_groupPerm[$row['gp_id']]['m']);
	}
}

/**
* DB입력
**/
//게시판 수정인 경우
if ($no) {
	$result = YB_modifyBoard($bo_tableName, $bo_name, $bo_desc, json_encode($bo_basicPerm2), json_encode($bo_groupPerm2), $bo_fileUploadFlag, $bo_fileUploadSize, $bo_htmlFlag);
	echo json_encode($result);
	exit;
//게시판 추가인 경우
} else {
	$result = YB_createBoard($bo_tableName, $bo_name);
	if ($result['status'] == 'fail') {
		echo json_encode($result);
		exit;
	}
	$result = YB_modifyBoard($bo_tableName, $bo_name, $bo_desc, json_encode($bo_basicPerm2), json_encode($bo_groupPerm2), $bo_fileUploadFlag, $bo_fileUploadSize, $bo_htmlFlag);
	echo json_encode($result);
	exit;
}
?>