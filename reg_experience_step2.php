<?php
	include_once './_common.php';

	//$_CSS[] = $yh['path'].'/css/reg_product_step2.css';
	$_CSS[] = $yh['path'].'/css/footer.css';

	//$_JS[] = $yh['path'].'/js/reg_product_step2.js';
	$showBottomNav = 0;
	include_once './head.php';
?>

<?php
	
	function customError($errno, $errstr) {
		echo "<b>Error:</b> [$errno] $errstr";
	}
	set_error_handler("customError");

	
	try {
		$insertOk = 0;

		$pd_name = $sql->real_escape_string(trim($_POST['pd_name']));
		$pd_shortDesc = $sql->real_escape_string(trim($_POST['pd_shortDesc']));
		$pd_price = $sql->real_escape_string(trim($_POST['pd_price']));
		

		$opt_date = $_POST['opt_date'];
		$opt_time = $_POST['opt_time'];

		$optCount = 0;	//option 에 몇개가 들어왔는지 판단
		foreach ($opt_date as $value) {
			if($value != "") {
				$optCount++;
			}
		}
		
		for ($i=0; $i < $optCount; $i++) { 
			$opt_full_date[$i] = $opt_date[$i] . " " . $opt_time[$i];
		}

		$content = $_POST['content'];

		$attachImage = $_REQUEST['attach_image'];
		$content = str_replace($attachImage, str_replace("../../..", "", $attachImage), $content);	//상대경로 절대경로로 수정
		//$attachImage = str_replace("../../..", "", $attachImage);	//에디터에 삽입된 그림파일 경로 절대경로로 수정
		//느릴 경우 json_encode 후 decode 하는방식으로 변경하면 훨씬 빨라짐
		//print_r($attachImage);
		//echo $content;
		$nowDate = date('Y-m-d H:i:s');

		//product number 가져오기 위한 쿼리
		$getLastPdNoQuery = "SELECT `pd_no` FROM `yh_product` ORDER BY `pd_no` DESC LIMIT 1";
		$result = $sql->query($getLastPdNoQuery)->fetch_assoc();

		$lastPdNo = $result["pd_no"];

		$newPdNo = $lastPdNo + 1;

		//product_sub 에 쓰일 코드 채번
		$getLastSubNoQuery = "SELECT `no` FROM `yh_product_sub` ORDER BY `no` DESC LIMIT 1";
		$result = $sql->query($getLastSubNoQuery)->fetch_assoc();

		$lastSubNo = $result["no"];

		$newSubNo = $lastSubNo + 1;

		//yh_product_option 채번
		$getLastOptNoQuery = "SELECT `no` FROM `yh_product_date_option` ORDER BY `no` DESC LIMIT 1";
		$result = $sql->query($getLastOptNoQuery)->fetch_assoc();

		$lastOptNo = $result["no"];

		$newOptNo = $lastOptNo + 1;

		//yh_product_photo 에 쓰일 코드 채번
		// $getLastPhotoNoQuery = "SELECT `no` FROM `yh_product_photo` ORDER BY `no` DESC LIMIT 1";
		// $result = $sql->query($getLastPhotoNoQuery)->fetch_assoc();

		// $lastPhotoNo = $result["no"];

		// $newPhotoNo = $lastPhotoNo + 1;

	} catch (Exception $e) {
		echo $e->getMessage() . ' code : ' .$e->getCode();
	}

	
	//현재 채번 알고리즘은 동시에 두명이 가입 할 경우 문제가 발생할 여지가 있음


	try {

		$sql->autocommit(FALSE);	//Transaction 구현 시작

		//product 의 기본정보 저장
		$insertProductQuery = "INSERT INTO `bigtrue`.`yh_product` 
		(`pd_no`, `pd_name`, `pd_shortDesc`, `pd_code`, `pd_price`, `pd_viewCount`, `pd_regDate`, `pd_thumbnail`, `pd_isProduct`) 
		VALUES ('$newPdNo', '$pd_name', '$pd_shortDesc', 'temp_code', '$pd_price', NULL, '$nowDate', NULL, '2')";

		if ($sql->query($insertProductQuery) === TRUE) { //삽입 성공시
			//echo "success1";
			$insertOk++;
		} else {
			throw new Exception('InsertProductQuery error');
		}

		//에디터로 작성한 콘텐츠 저장, pd_no 로 조인 후 출력가능
		$insertContentQuery = "INSERT INTO  `bigtrue`.`yh_product_sub` (
			`no` ,
			`pd_no` ,
			`pds_contents`
		)
		VALUES (
			'$newSubNo', '$newPdNo', '$content'
		)";

		if ($sql->query($insertContentQuery) === TRUE) { //삽입 성공시
			//echo "success2";
			$insertOk++;			
		} else {
			throw new Exception('insertContentQuery error');
		}

		$insertOptOk = 0;

		for ($i=0; $i < $optCount; $i++) { 
			$insertOptionQuery = "INSERT INTO  `bigtrue`.`yh_product_date_option` (
				`no` ,
				`pd_no` ,
				`pddo_date`
			)
			VALUES (
				'" . ($newOptNo + $i) . "', '$newPdNo', '$opt_full_date[$i]'
			)";

			if ($sql->query($insertOptionQuery) === TRUE) { //삽입 성공시
				//echo "success2";
				$insertOptOk++;
				echo $insertOk;
			} else {
				throw new Exception('insertOptionQuery error');
			}
		}

		if ($insertOk == 2 && $insertOptOk == $optCount) {
			//성공했을 때 보여줄 화면
			if (!$sql->commit()) {
				echo "Transaction commit failed <br>";
				exit;
			} else {

				include_once "reg_product_step3.php";
			}
		} else {	//DB삽입 실패시
			
			 echo "Insert failed<br>";
			// $deleteProductQuery = "DELETE FROM `yh_product` WHERE `yh_product`.`pd_no` = '$newPdNo'";
			// $deleteContentQuery =  "DELETE FROM `yh_product_sub` WHERE `yh_product_sub`.`pd_no` = '$newPdNo'";
			// $deleteOptionQuery = "DELETE FROM `yh_product_option` WHERE `yh_product_option`.`pd_no` = '$newPdNo'";
			// $sql->query($deleteProductQuery);
			// $sql->query($deleteContentQuery);
			// $sql->query($deleteOptionQuery);
			// echo "deleted all";

		}

	} catch (Exception $e) {
		$sql->rollback();
		echo $e->getMessage() . ' code : ' .$e->getCode();
	}
	


	

	


	
	//yh_product_photo 를 없애는 방향으로 가야할듯
	//사진 파일 경로 yh_product_photo 에 삽입
	// $insertPhotoQuery = "INSERT INTO  `bigtrue`.`yh_product_photo` (
	// 	`no` ,
	// 	`pd_no` ,
	// 	`pdp_path`
	// )
	// VALUES (
	// '$newSubNo', '$newPhotoNo', '$content'
	// )";

	// if ($sql->query($insertContentQuery) === TRUE) { //삽입 성공시
	// 	echo "success2";
	// }

/*
	echo "pd_name : " . $pd_name . "<br>";
	echo "pd_shortDesc : " . $pd_shortDesc . "<br>";
	echo "pd_opt_1 : " . $pd_opt_1 . "<br>";
	echo "pd_opt_1_price :" . $pd_opt_1_price . "<br>";
	echo "pd_opt_2 :" . $pd_opt_2 . "<br>";
	echo "pd_opt_2_price :" . $pd_opt_2_price . "<br>";
	echo "pd_opt_3 :" . $pd_opt_3 . "<br>";
	echo "pd_opt_3_price :" . $pd_opt_3_price . "<br>";
	echo "pd_opt_4 :" . $pd_opt_4 . "<br>";
	echo "pd_opt_4_price :" . $pd_opt_4_price . "<br>";
	echo "pd_opt_5 :" . $pd_opt_5 . "<br>";
	echo "pd_opt_5_price :" . $pd_opt_5_price . "<br>";
	echo "content <br>" . $content . "<br>"; */

?>






<?php
include_once './tail.php';
?>