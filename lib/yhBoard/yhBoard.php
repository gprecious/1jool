<?php
if (!defined("_YH_")) exit; // 개별 페이지 접근 불가
/*******************************************
yhBoard
	YB_setup()
	YB_createBoard($bo_tableName, $bo_name)
	YB_modifyBoard($bo_tableName, $bo_name, $bo_desc, $bo_basicPerm, $bo_groupPerm, $bo_fileUploadFlag, $bo_fileUploadSize, $bo_htmlFlag)
	YB_deleteBoard($bo_tableName)
	YB_chkPerm($bo_tableName, $mb_group, $rwm)
	YB_writePost($bo_tableName, $wr_subject, $wr_content, $mb_no, $mb_nick, $mb_group)
	YB_writeComment ($bo_tableName, $wr_parent, $wr_content, $mb_no, $mb_nick, $mb_group)
	YB_modPost ($bo_tableName, $no, $wr_subject, $wr_content, $mb_no, $mb_nick, $mb_group)
	YB_modComment ($bo_tableName, $no, $wr_content, $mb_no, $mb_nick, $mb_group)
	YB_listPost($bo_tableName, $page, $lpp, $mb_group)
	YB_listComment($bo_tableName, $wr_parent, $page, $lpp, $mb_group)
	YB_viewPost($bo_tableName, $no, $mb_no, $mb_group)
	YB_delPost ($bo_tableName, $no, $mb_no, $mb_group)
	YB_delComment ($bo_tableName, $no, $mb_no, $mb_group)
********************************************/
/**
* To Do List
*	modify->mod, delete->del 로 변경
*	YB_clearBoard 추가
**/
/**
* 기본설정
**/
//설치 이후 바꾸지 마세요!
$yhBoard['prefix'] = 'yh_board';

/**
* 함수 선언
**/
//게시판 초기 셋업 (최초 1회 실행)
function YB_setup() {
	global $yhBoard, $sql, $yh;

	//게시판목록 테이블이 없다면 생성
	$q = "CREATE TABLE IF NOT EXISTS `".$yhBoard['prefix']."` (
		`no` INT(11) NOT NULL AUTO_INCREMENT,
		`bo_name` VARCHAR(255) NOT NULL,
		`bo_tableName` VARCHAR(255) NOT NULL ,
		`bo_desc` VARCHAR(255) NOT NULL,
		`bo_basicPerm` TEXT NOT NULL ,
		`bo_groupPerm` TEXT NOT NULL ,
		`bo_fileUploadFlag` INT(11) NOT NULL,
		`bo_fileUploadSize` INT(11) NOT NULL,
		`bo_htmlFlag` INT(11) NOT NULL,
		`bo_postCnt` INT(11) NOT NULL,
		`bo_commentCnt` INT(11) NOT NULL,
		`bo_wrTime` DATETIME NOT NULL ,
		PRIMARY KEY ( `no`), 
		KEY  `bo_tableName` ( `bo_tableName`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8;";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//게시판 파일목록 테이블이 없다면 생성
	$q = "CREATE TABLE IF NOT EXISTS `".$yhBoard['prefix']."_file` (
		`no` INT(11) NOT NULL AUTO_INCREMENT,
		`bo_no` INT(11) NOT NULL ,
		`wr_no` INT(11) NOT NULL,
		`file_name` VARCHAR(255) NOT NULL ,
		`file_realName` VARCHAR(255) NOT NULL ,
		`file_size` INT(11) NOT NULL ,
		`file_wrTime` DATETIME NOT NULL ,
		PRIMARY KEY ( `no`), 
		KEY  `bo_no` ( `bo_no`),
		KEY  `wr_no` ( `wr_no`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8;";

	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//게시판 파일 저장하는 폴더가 없다면 생성
	@mkdir($yh['path']."/data/".$yhBoard['prefix']);
	
	//게시판 좋아요 테이블이 없다면 생성
	$q = "CREATE TABLE IF NOT EXISTS `".$yhBoard['prefix']."_like` (
		`no` INT(11) NOT NULL AUTO_INCREMENT,
		`bo_no` INT(11) NOT NULL ,
		`wr_no` INT(11) NOT NULL,
		`mb_no` INT(11) NOT NULL ,
		`like_wrTime` DATETIME NOT NULL ,
		PRIMARY KEY ( `no`), 
		KEY  `bo_no` ( `bo_no`),
		KEY  `wr_no` ( `wr_no`),
		KEY  `mb_no` ( `mb_no`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8;";

	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}
}

//게시판을 만드는 함수
function YB_createBoard($bo_tableName, $bo_name) {
	global $yhBoard, $sql, $yh;
	$bo_name = $sql->real_escape_string($bo_name);
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$nowDate = date('Y-m-d H:i:s');

	//게시판이 세팅되지 않았다면 종료
	$q = "SHOW TABLES LIKE '".$yhBoard['prefix']."'";
	$row = $sql->query($q)->fetch_row();
	if (!$row) {
		$result['status'] = 'fail';
		$result['msg'] = '게시판이 셋업되지 않았습니다.';
		return $result;	
	}

	//게시판명이 없다면 종료
	if ($bo_name == '') {
		$result['status'] = 'fail';
		$result['msg'] = '게시판 명을 입력해주세요.';
		return $result;
	}

	//테이블명으로 지정할 수 없는 예약어 거르기
	$reservedList = array('file','like');
	if (in_array($bo_tableName, $reservedList)) {
		$result['status'] = 'fail';
		$result['msg'] = '테이블명으로 지정된 "'.$bo_tableName.'"은 예약어로 사용하실 수 없습니다.';
		return $result;
	}

	//게시판ID 검증
	if (!preg_match('/[0-9a-zA-Z\-_]+/Uu',$bo_tableName)) {
		$result['status'] = 'fail';
		$result['msg'] = '게시판 테이블명은 영어대소문자, 숫자, 하이픈(-), 언더바(_)로만 작성해야합니다.';
		return $result;
	}

	//게시판 테이블이 없다면 생성
	$q = "CREATE TABLE `".$yhBoard['prefix'].'_'.$bo_tableName."` (
		`no` INT(11) NOT NULL AUTO_INCREMENT ,
		`wr_subject` VARCHAR(255) NOT NULL ,
		`wr_content` MEDIUMTEXT NOT NULL ,
		`wr_parent` INT(11) NOT NULL ,
		`wr_commentCnt` INT(11) NOT NULL ,
		`wr_likeCnt` INT(11) NOT NULL ,
		`mb_no` INT(11) NOT NULL ,
		`mb_nick` VARCHAR(255) NOT NULL ,
		`wr_del` INT(11) NOT NULL ,
		`wr_hit` INT(11) NOT NULL ,
		`wr_time` DATETIME NOT NULL ,
		`wr_modTime` DATETIME NOT NULL ,
		PRIMARY KEY ( `no`),
		KEY  `wr_parent` ( `wr_parent`),
		KEY  `mb_no` ( `mb_no`),
		KEY  `mb_nick` ( `mb_nick`),
		KEY  `wr_del` ( `wr_del`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8;";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	
	//기본퍼미션 설정
	$basicPerm = array();
	$basicPerm['r'] = 1;
	$basicPerm['w'] = 1;
	$basicPerm['m'] = 0;

	//게시판 목록 테이블에 저장
	$q = "INSERT INTO `".$yhBoard['prefix']."` (bo_name, bo_tableName, bo_basicPerm, bo_groupPerm, bo_fileUploadFlag, bo_fileUploadSize, bo_htmlFlag, bo_postCnt, bo_commentCnt, bo_wrTime) VALUES('".$bo_name."','".$bo_tableName."','".$sql->real_escape_string(json_encode($basicPerm))."','[]','0','0','0','0','0','".$nowDate."')";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	} else {
		$result['status'] = 'success';
		$result['insert_id'] = $sql->insert_id;
		return $result;
	}
}

//게시판을 수정하는 함수
function YB_modifyBoard($bo_tableName, $bo_name, $bo_desc, $bo_basicPerm, $bo_groupPerm, $bo_fileUploadFlag, $bo_fileUploadSize, $bo_htmlFlag) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$bo_name = $sql->real_escape_string($bo_name);
	$bo_basicPerm = $sql->real_escape_string($bo_basicPerm);
	$bo_groupPerm = $sql->real_escape_string($bo_groupPerm);
	$bo_fileUploadFlag = intval($bo_fileUploadFlag);
	$bo_fileUploadSize = intval($bo_fileUploadSize);
	$bo_htmlFlag = intval($bo_htmlFlag);

	//해당 게시판의 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = "해당 게시판이 존재하지 않습니다.";
		return $result;
	}

	//해당 게시판 정보 수정
	$q = "UPDATE `".$yhBoard['prefix']."` SET
		bo_name='".$bo_name."',
		bo_desc='".$bo_desc."',
		bo_basicPerm='".$bo_basicPerm."',
		bo_groupPerm='".$bo_groupPerm."',
		bo_fileUploadFlag='".$bo_fileUploadFlag."',
		bo_fileUploadSize='".$bo_fileUploadSize."',
		bo_htmlFlag='".$bo_htmlFlag."'
	WHERE bo_tableName='".$bo_tableName."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}

//게시판을 제거하는 함수
function YB_deleteBoard($bo_tableName) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);

	//해당 게시판의 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = "해당 게시판이 존재하지 않습니다.";
		return $result;
	}

	//해당 게시판에 업로드된 파일, like 모두 제거
	$q = "SELECT * FROM `".$yhBoard['prefix']."_file` WHERE bo_no='".$boardInfo['no']."'";
	$r = $sql->query($q);
	while($row = $r->fetch_assoc()) {
		@unlink($yh['path']."/data/".$yhBoard['prefix']."/".$row['file_name']);
	}

	$q = "DELETE FROM `".$yhBoard['prefix']."_file` WHERE bo_no='".$boardInfo['no']."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$q = "DELETE FROM `".$yhBoard['prefix']."_like` WHERE bo_no='".$boardInfo['no']."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//해당 게시판의 DB DROP
	$q = "DROP TABLE `".$yhBoard['prefix']."_".$boardInfo['bo_tableName']."`";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//게시판 목록 테이블에서 제거
	$q = "DELETE FROM `".$yhBoard['prefix']."` WHERE no='".$boardInfo['no']."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}

//권한체크하는 함수
function YB_chkPerm($bo_tableName, $mb_group, $rwm) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//권한여부 판단
	$permOk = 0;
	if ($boardInfo['bo_basicPerm'][$rwm]) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]][$rwm] == 1) {
				$permOk = 1;
				break;
			}
		}
	}
	
	$result['status'] = 'success';
	$result['permOk'] = $permOk;
	return $result;
}

//게시판에 글을 작성하는 함수
function YB_writePost($bo_tableName, $wr_subject, $wr_content, $mb_no, $mb_nick, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$wr_subject = $sql->real_escape_string($wr_subject);
	$wr_content = $sql->real_escape_string($wr_content);
	$mb_no = intval($mb_no);
	$mb_nick = $sql->real_escape_string($mb_nick);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$nowDate = date('Y-m-d H:i:s');

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 만약 기본권한으로 쓰기권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['w']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['w'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}
	if ($permOk == 0) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//제목과 내용이 없으면 종료
	if (trim($wr_subject) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '제목을 입력해주세요.';
		return $result;
	}

	if (trim($wr_content) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '내용을 입력해주세요.';
		return $result;
	}

	//DB에 입력
	$q = "INSERT INTO `".$yhBoard['prefix']."_".$bo_tableName."` (wr_subject, wr_content, mb_no, mb_nick, wr_time, wr_modTime) VALUES('".$wr_subject."','".$wr_content."','".$mb_no."','".$mb_nick."','".$nowDate."','".$nowDate."')";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}
	$insert_id = $sql->insert_id;

	//게시판 postCnt 증가
	$q = "UPDATE `".$yhBoard['prefix']."` SET bo_postCnt=bo_postCnt+1 WHERE bo_tableName='".$bo_tableName."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	$result['insert_id'] = $insert_id;
	return $result;
}

//게시판에 댓글을 작성하는 함수
function YB_writeComment ($bo_tableName, $wr_parent, $wr_content, $mb_no, $mb_nick, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$wr_parent = intval($wr_parent);
	$wr_content = $sql->real_escape_string($wr_content);
	$mb_no = intval($mb_no);
	$mb_nick = $sql->real_escape_string($mb_nick);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$nowDate = date('Y-m-d H:i:s');

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//wr_parent가 존재하는지 검사
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$wr_parent."'";
	$parentPost = $sql->query($q)->fetch_assoc();
	if (!$parentPost) {
		$result['status'] = 'fail';
		$result['msg'] = '원글이 존재하지 않습니다.';
		return $result;
	}

	//게시판 권한 검사, 기본권한으로 쓰기권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['w']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['w'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}
	if ($permOk == 0) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//내용이 없으면 종료
	if (trim($wr_content) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '내용을 입력해주세요.';
		return $result;
	}

	//DB에 입력
	$q = "INSERT INTO `".$yhBoard['prefix']."_".$bo_tableName."` (wr_parent, wr_content, mb_no, mb_nick, wr_time, wr_modTime) VALUES('".$wr_parent."','".$wr_content."','".$mb_no."','".$mb_nick."','".$nowDate."','".$nowDate."')";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}
	$insert_id = $sql->insert_id;

	//원글의 commentCnt 증가
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET wr_commentCnt = wr_commentCnt+1 WHERE no='".$wr_parent."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//게시판 postCnt 증가
	$q = "UPDATE `".$yhBoard['prefix']."` SET bo_commentCnt=bo_commentCnt+1 WHERE bo_tableName='".$bo_tableName."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	$result['insert_id'] = $insert_id;
	return $result;
}

//게시판의 글을 수정하는 함수
function YB_modPost ($bo_tableName, $no, $wr_subject, $wr_content, $mb_no, $mb_nick, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$no = intval($no);
	$wr_subject = $sql->real_escape_string($wr_subject);
	$wr_content = $sql->real_escape_string($wr_content);
	$mb_no = intval($mb_no);
	$mb_nick = $sql->real_escape_string($mb_nick);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$nowDate = date('Y-m-d H:i:s');

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 만약 기본권한으로 관리권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['m']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['m'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}

	//해당 게시글 정보 긁기
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$no."' AND wr_parent='0' AND wr_del='0'";
	$wrInfo = $sql->query($q)->fetch_assoc();
	if (!$wrInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시글이 존재하지 않습니다.';
		return $result;
	}

	//관리권한도 없으면서 자기글이 아니라면 수정못함
	if ($permOk == 0 && $mb_no != $wrInfo['mb_no']) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//제목과 내용이 없으면 종료
	if (trim($wr_subject) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '제목을 입력해주세요.';
		return $result;
	}

	if (trim($wr_content) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '내용을 입력해주세요.';
		return $result;
	}

	//DB 수정
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET
		wr_subject='".$wr_subject."',
		wr_content='".$wr_content."',
		mb_nick='".$mb_nick."',
		wr_modTime='".$nowDate."'
	WHERE no='".$no."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}

//게시판의 댓글을 수정하는 함수
function YB_modComment ($bo_tableName, $no, $wr_content, $mb_no, $mb_nick, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$no = intval($no);
	$wr_content = $sql->real_escape_string($wr_content);
	$mb_no = intval($mb_no);
	$mb_nick = $sql->real_escape_string($mb_nick);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$nowDate = date('Y-m-d H:i:s');

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 만약 기본권한으로 관리권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['m']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['m'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}

	//해당 게시글 정보 긁기
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$no."' AND wr_parent!='0' AND wr_del='0'";
	$wrInfo = $sql->query($q)->fetch_assoc();
	if (!$wrInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 댓글이 존재하지 않습니다.';
		return $result;
	}

	//원글이 삭제되어있는지 검사
	$q = "SELECT 1 FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$wrInfo['wr_parent']."' AND wr_del='0'";
	$row = $sql->query($q)->fetch_assoc();
	if (!$row) {
		$result['status'] = 'fail';
		$result['msg'] = '원글이 존재하지 않습니다.';
		return $result;
	}

	//관리권한도 없으면서 자기글이 아니라면 수정못함
	if ($permOk == 0 && $mb_no != $wrInfo['mb_no']) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	if (trim($wr_content) == '') {
		$result['status'] = 'fail';
		$result['msg'] = '내용을 입력해주세요.';
		return $result;
	}

	//DB 수정
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET
		wr_content='".$wr_content."',
		mb_nick='".$mb_nick."',
		wr_modTime='".$nowDate."'
	WHERE no='".$no."'";
	if (!$sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}

//게시판의 글을 리스팅하는 함수
function YB_listPost($bo_tableName, $page, $lpp, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$page = intval($page);
	$lpp = intval($lpp);
	if ($page < 1) $page = 1;
	if ($lpp < 1) $lpp = 20;

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 기본권한으로 읽기권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['r']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['r'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}
	if ($permOk == 0) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//전체리스트 갯수 구하기
	$totalCnt = $boardInfo['bo_postCnt'];

	//전체페이지 수 구하기
	$pageTotal = ceil($totalCnt/$lpp);

	//page가 totalPage을 초과했을 경우
	if ($pageTotal < 1) $pageTotal = 1;
	if ($page > $pageTotal) $page = $pageTotal;

	//내용긁어서 list로 만들어 보여주기
	$listArr = array();
	$q = "SELECT no, wr_subject, wr_commentCnt, wr_likeCnt, mb_nick, wr_hit, wr_time FROM `".$yhBoard['prefix']."_".$sql->real_escape_string($boardInfo['bo_tableName'])."` WHERE wr_parent = '0' AND wr_del = '0' ORDER BY no DESC LIMIT ".(($page-1)*$lpp).",".$lpp;
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}
	while($row = $r->fetch_assoc()) {
		$listArr[] =$row;
	}

	//result만들어 출력
	$result['status'] = 'success';
	$result['list'] = $listArr;
	$result['page'] = $page;
	$result['lpp'] = $lpp;
	$result['totalCnt'] = $totalCnt;
	return $result;
}

//게시판의 댓글을 리스팅하는 함수
function YB_listComment($bo_tableName, $wr_parent, $page, $lpp, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$wr_parent = intval($wr_parent);
	$page = intval($page);
	$lpp = intval($lpp);
	if ($page < 1) $page = 1;
	if ($lpp < 1) $lpp = 20;

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//wr_parent가 존재하는지 검사
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$wr_parent."' AND wr_del='0'";
	$parentPost = $sql->query($q)->fetch_assoc();
	if (!$parentPost) {
		$result['status'] = 'fail';
		$result['msg'] = '원글이 존재하지 않습니다.';
		return $result;
	}

	//게시판 권한 검사, 기본권한으로 읽기권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['r']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['r'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}
	if ($permOk == 0) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//전체리스트 갯수 구하기
	$totalCnt = $parentPost['wr_commentCnt'];

	//전체페이지 수 구하기
	$pageTotal = ceil($totalCnt/$lpp);

	//page가 totalPage을 초과했을 경우
	if ($pageTotal < 1) $pageTotal = 1;
	if ($page > $pageTotal) $page = $pageTotal;

	//내용긁어서 list로 만들어 보여주기
	$listArr = array();
	$q = "SELECT no, wr_content, wr_likeCnt, mb_nick, wr_time FROM `".$yhBoard['prefix']."_".$sql->real_escape_string($boardInfo['bo_tableName'])."` WHERE wr_parent = '".$wr_parent."' AND wr_del = '0' ORDER BY no ASC LIMIT ".(($page-1)*$lpp).",".$lpp;
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}
	while($row = $r->fetch_assoc()) {
		$listArr[] =$row;
	}

	//result만들어 출력
	$result['status'] = 'success';
	$result['list'] = $listArr;
	$result['page'] = $page;
	$result['lpp'] = $lpp;
	$result['totalCnt'] = $totalCnt;
	return $result;
}

//게시판의 글을 보는 함수
function YB_viewPost($bo_tableName, $no, $mb_no, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);
	$no = intval($no);

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 기본권한으로 읽기권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['r']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['r'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}

	//해당 게시글 정보 긁기
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$no."' AND wr_parent='0' AND wr_del='0'";
	$wrInfo = $sql->query($q)->fetch_assoc();
	if (!$wrInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시글이 존재하지 않습니다.';
		return $result;
	}

	//자기글이면 권한이 없어도 볼 수 있게 함
	if ($permOk == 0 && $mb_no != $wrInfo['mb_no']) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//만약 htmlFlag가 0이라면 escapeHtml처리
	if ($boardInfo['bo_htmlFlag'] == 0) {
		$wrInfo['wr_content'] = escapeHtml($wrInfo['wr_content']);
	}

	//file목록이 있다면 fileList만들기
	$fileList = array();
	$q = "SELECT * FROM `".$yhBoard['prefix']."_file` WHERE bo_no='".$boardInfo['no']."' AND wr_no='".$no."'";
	$r = $sql->query($q);
	while($row = $r->fetch_assoc()) {
		$fileList[] = $row;
	}

	//wr_hit하나 증가
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET wr_hit=wr_hit+1 WHERE no='".$no."'";
	$sql->query($q);
	$wrInfo['wr_hit']++;


	$result['status'] = 'success';
	$result['data'] = $wrInfo;
	$result['fileList'] = $fileList;
	return $result;
}

//게시판의 글을 지우는 함수
function YB_delPost ($bo_tableName, $no, $mb_no, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$no = intval($no);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 기본권한으로 관리권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['m']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['m'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}

	//해당 게시글 정보 긁기
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$no."' AND wr_parent='0' AND wr_del='0'";
	$wrInfo = $sql->query($q)->fetch_assoc();
	if (!$wrInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시글이 존재하지 않습니다.';
		return $result;
	}

	//관리권한도 없으면서 자기글이 아니라면 못지움
	if ($permOk == 0 && $mb_no != $wrInfo['mb_no']) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//해당 게시글에 연결된 좋아요 제거하지 않음(원글보존하기때문에 복구할수있도록)
	//해당 게시글에 연결된 댓글 제거하지 않음(원글보존하기때문에 복구할수있도록)
	//해당 게시글에 올라온 파일 제거하지 않음(원글보존하기때문에 복구할수있도록)

	//postCnt 및 commentCnt 조정
	$q = "UPDATE `".$yhBoard['prefix']."` SET bo_postCnt = bo_postCnt-1, bo_commentCnt = bo_commentCnt-".$wrInfo['wr_commentCnt']." WHERE bo_tableName='".$bo_tableName."'";
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//해당 글 지움
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET wr_del='1' WHERE no='".$no."'";
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}

//게시판의 댓글을 지우는 함수
function YB_delComment ($bo_tableName, $no, $mb_no, $mb_group) {
	//변수 정리
	global $yhBoard, $sql, $yh;
	$bo_tableName = $sql->real_escape_string($bo_tableName);
	$no = intval($no);
	if (gettype($mb_group) == 'string') $mb_group = explode(',',$mb_group);

	//게시판 정보 받아오기
	$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE bo_tableName='".$bo_tableName."'";
	$boardInfo = $sql->query($q)->fetch_assoc();
	if (!$boardInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 게시판이 존재하지 않습니다.';
		return $result;	
	}
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);

	//게시판 권한 검사, 기본권한으로 관리권한이 주어진다면 그냥 통과
	$permOk = 0;
	if ($boardInfo['bo_basicPerm']['m']) {
		$permOk = 1;
	} else {
		//해당 사용자가 속한 모든 그룹에 대해 반복
		for($i=0;$i<count($mb_group);$i++) {
			if ($boardInfo['bo_groupPerm'][$mb_group[$i]]['m'] == 1) {
				$permOk = 1;
				break;
			}
		}
	}

	//해당 게시글 정보 긁기
	$q = "SELECT * FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$no."' AND wr_parent!='0' AND wr_del='0'";
	$wrInfo = $sql->query($q)->fetch_assoc();
	if (!$wrInfo) {
		$result['status'] = 'fail';
		$result['msg'] = '해당 댓글이 존재하지 않습니다.';
		return $result;
	}

	//원글이 삭제되어있는지 검사
	$q = "SELECT 1 FROM `".$yhBoard['prefix']."_".$bo_tableName."` WHERE no='".$wrInfo['wr_parent']."' AND wr_del='0'";
	$row = $sql->query($q)->fetch_assoc();
	if (!$row) {
		$result['status'] = 'fail';
		$result['msg'] = '원글이 존재하지 않습니다.';
		return $result;
	}

	//관리권한도 없으면서 자기글이 아니라면 못지움
	if ($permOk == 0 && $mb_no != $wrInfo['mb_no']) {
		$result['status'] = 'fail';
		$result['msg'] = '권한이 없습니다.';
		return $result;
	}

	//해당 게시글에 연결된 좋아요 제거하지 않음(원글보존하기때문에 복구할수있도록)
	//해당 게시글에 연결된 댓글 제거하지 않음(원글보존하기때문에 복구할수있도록)
	//해당 게시글에 올라온 파일 제거하지 않음(원글보존하기때문에 복구할수있도록)

	//postCnt 및 commentCnt 조정
	$q = "UPDATE `".$yhBoard['prefix']."` SET bo_commentCnt = bo_commentCnt-1 WHERE bo_tableName='".$bo_tableName."'";
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	//해당 글 지움
	$q = "UPDATE `".$yhBoard['prefix']."_".$bo_tableName."` SET wr_del='1' WHERE no='".$no."'";
	if (!$r = $sql->query($q)) {
		$result['status'] = 'fail';
		$result['msg'] = $sql->error;
		return $result;
	}

	$result['status'] = 'success';
	return $result;
}
?>