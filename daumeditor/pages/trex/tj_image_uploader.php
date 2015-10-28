<?php

include_once './_common.php';

if (!$is_member) {
	echo "로그인 되지 않음.";
	exit;
}

$target_dir = $yh['path'] . "/productImage/" . $_SESSION['ss_mb_no'] . "/";

if(!is_dir($target_dir)) {	//회원번호 디렉토리 없으면 생성
	mkdir($target_dir, 0777);
}
$file_name = $_FILES["fileToUpload"]["name"];
$target_file = $target_dir . date("YmdHis") . "_" . basename($file_name);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

$file_size = $_FILES["fileToUpload"]["size"];
echo $target_file;

if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if($check !== false) {
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
}

if(file_exists($target_file)) {
	echo "File already exists.";
	$uploadOk = 0;
}

if ($_FILES["fileToUpload"]["size"] > 15000000) {		//사진 업로드 용량 제한
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"	//허용 파일 포멧
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>image_uploader.php</title> 
<script src="../../js/popup.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../../css/popup.css" type="text/css"  charset="utf-8"/>
<script type="text/javascript">
// <![CDATA[
    
    function initUploader(){
            
        var _opener = PopupUtil.getOpener();
        if (!_opener) {
            alert('잘못된 경로로 접근하셨습니다.');
            return;
        }
        
        var _attacher = getAttacher('image', _opener);
        registerAction(_attacher);
            
            if (typeof(execAttach) == 'undefined') { //Virtual Function
            return;
        }
        
        var _mockdata = {
            'imageurl': '<?php echo $target_file; ?>',
            'filename': '<?php echo $file_name; ?>',
            'filesize': <?php echo $file_size; ?>,
            'imagealign': 'C',
            'originalurl': '<?php echo $target_file; ?>',
            'thumburl': '<?php echo $target_file; ?>'
        };
                
        execAttach(_mockdata);
        closeWindow();
                
    }
// ]]>
</script>
</head>
<body onload="initUploader();">

</body>
</html> 