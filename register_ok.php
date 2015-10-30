<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/register_ok.css';
$_CSS[] = $yh['path'].'/css/footer.css';

//$_JS[] = $yh['path'].'/js/register_ok.js';
$showBottomNav = 0;
include_once './head.php';
//print_r($yh);
//echo "<br />";
//print_r($member);
//echo "<br />";
/*
if ($is_member) echo "<a href='".$yh['path']."/logout.php'>Logout</a>";
else echo "<a href='".$yh['path']."/login.php'>Login</a>";
*/

function customError($errno, $errstr) {
	echo "<b>Error:</b> [$errno] $errstr";
}
set_error_handler("customError");


try {
	$inputEmail = $sql->real_escape_string(trim($_POST['inputEmail']));
	$escapedInputPassword = $sql->real_escape_string(trim($_POST['inputPassword']));
	$inputPassword = md5($yh['loginSeed'].$escapedInputPassword);

	$inputConfirmPassword = $sql->real_escape_string(trim($_POST['inputConfirmPassword']));
	$inputPasswordQuestion = $sql->real_escape_string(trim($_POST['inputPasswordQuestion']));
	$inputConfirmPasswordAnswer = $sql->real_escape_string(trim($_POST['inputConfirmPasswordAnswer']));
	$inputName = $sql->real_escape_string(trim($_POST['inputName']));
	$inputPostcode = $_POST['inputPostcode'];
	$inputAddress1 = $sql->real_escape_string(trim($_POST['inputAddress1']));
	$inputAddress2 = $sql->real_escape_string(trim($_POST['inputAddress2']));
	$inputPhone1 = $_POST['inputPhone1'];
	$inputPhone2 = $_POST['inputPhone2'];
	$inputPhone3 = $_POST['inputPhone3'];
	$inputTelephone1 = $_POST['inputTelephone1'];
	$inputTelephone2 = $_POST['inputTelephone2'];
	$inputTelephone3 = $_POST['inputTelephone3'];
	$inputEmailAccept = $_POST['inputEmailAccept'];

	$emailCheck = $_POST['emailCheck'];

} catch (Exception $e) {
	echo $e->getMessage() . ' code : ' .$e->getCode();
}
//echo md5($yh['loginSeed'].$escapedInputPassword);

//validate an integer

if (!is_numeric($inputPostcode)) {
	echo "숫자입력 필드에 문자 입력됨1";
	exit;
}
if (!is_numeric($inputPhone1) && $inputPhone1 != "") {
	echo "숫자입력 필드에 문자 입력됨2";
	exit;
}
if (!is_numeric($inputPhone2) && $inputPhone2 != "") {
	echo "숫자입력 필드에 문자 입력됨3";
	exit;
}
if (!is_numeric($inputPhone3) && $inputPhone3 != "") {
	echo "숫자입력 필드에 문자 입력됨4";
	exit;
}
if (!is_numeric($inputTelephone1)) {
	echo "숫자입력 필드에 문자 입력됨5";
	exit;
}
if (!is_numeric($inputTelephone2)) {
	echo "숫자입력 필드에 문자 입력됨6";
	exit;
}
if (!is_numeric($inputTelephone3)) {
	echo "숫자입력 필드에 문자 입력됨7";
	exit;
}
if (!is_numeric($inputEmailAccept)) {
	echo "숫자입력 필드에 문자 입력됨8";
	exit;
}
if ($emailCheck != "0") {
	echo "중복된 이메일 이볅됨";
	exit;
}
//end validate an integer


$nowDate = date('Y-m-d H:i:s');






$getLastMbNoQuery = "SELECT `no` FROM `yh_member` ORDER BY `no` DESC LIMIT 1";
$result = $sql->query($getLastMbNoQuery)->fetch_assoc();

$lastMbNo = $result["no"];

$newMbNo = $lastMbNo + 1;
//현재 채번 알고리즘은 동시에 두명이 가입 할 경우 문제가 발생할 여지가 있음


$insertQuery = "INSERT INTO  `bigtrue`.`yh_member` (
	`no` ,
	`mb_id` ,
	`mb_pw` ,
	`mb_name` ,
	`mb_block` ,
	`mb_group` ,
	`mb_loginTime` ,
	`mb_joinTime` ,
	`mb_quiz_question` ,
	`mb_quiz_answer` ,
	`mb_postcode` ,
	`mb_address1` ,
	`mb_address2` ,
	`mb_phone1` ,
	`mb_phone2` ,
	`mb_phone3` ,
	`mb_telephone1` ,
	`mb_telephone2` ,
	`mb_telephone3` ,
	`mb_emailAccept`
)
VALUES (
'$newMbNo',  '$inputEmail',  '$inputPassword',  '$inputName',  '0',  'grade1',  '$nowDate',  '$nowDate',  
'$inputPasswordQuestion',  '$inputConfirmPasswordAnswer',  '$inputPostcode',  '$inputAddress1',  '$inputAddress2',
'$inputPhone1',  '$inputPhone2',  '$inputPhone3',  '$inputPhone1',  '$inputPhone2',  '$inputPhone3',  '$inputEmailAccept'
)";


try {
	if ($sql->query($insertQuery) === TRUE) { //삽입 성공시
	//echo("<script>alert("test");</script>");


?>
	<div class="container contents-outer">
		<div class="contents-wrap">	
			<h1>한줄약국에 오신 것을 환영합니다!</h1>
			<div class="row member-info">
				<div class="col-xs-6 col-md-2 col-md-offset-4">
					회원 아이디 : 
				</div>
				<div class="col-xs-6 col-md-2">
					<?=$inputEmail ?>
				</div>
			</div>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">로그인</button>
		</div>
	</div>

<?php	
	//echo("<script>location.replace('$yh['path']/register.php');</script>"); //화면 이동
	} else { //삽입 실패시
		echo "<script>alert('회원가입 도중 오류 발생 다시 진행해주세요.');</script>";
		//echo("<script>location.replace('$yh['path']/register.php');</script>");
	}
} catch (Exception $e) {
	echo $e->getMessage() . ' code : ' .$e->getCode();
}
//$result->free();

$sql->close();
?>




<?php
include_once './tail.php';
?>