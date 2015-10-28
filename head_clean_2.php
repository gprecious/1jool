<?php
	if (!defined("_YH_")) exit; // 개별 페이지 접근 불가
?>
<!DOCTYPE HTML>
<html>
	<head>		
		<meta charset='UTF-8' />
		<meta name='format-detection' content='telephone=no' />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?=$yh['title']?></title>

		<link rel="stylesheet" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">
		<link rel='stylesheet' href='<?=$yh['path']?>/css/common.css?v=<?=$yh['ver']?>' />

		<script src='<?=$yh['path']?>/lib/jquery/jquery-2.1.3.min.js'></script>
		<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		<script src='<?=$yh['path']?>/js/common.js?v=<?=$yh['ver']?>'></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

		<script>
			// 자바스크립트에서 사용하는 전역변수 선언
			var yh_path = "<?=$yh['path']?>";
			var yh_url = "<?=$yh['url']?>";
			var is_member = <?=intval($is_member)?>;

			var time_offset = <?=$_SERVER['REQUEST_TIME']?> - Math.floor((new Date()).getTime()/1000); //클라이언트 시간과 서버 시간을 동기화하기 위한 변수, client time + offset = server time
		</script>
<?php
	for ($i=0;$i<count($_HEAD);$i++) {echo $_HEAD[$i];}
	for ($i=0;$i<count($_CSS);$i++) {echo "<link rel='stylesheet' href='".$_CSS[$i]."?v=".$yh['ver']."' />";}
	for ($i=0;$i<count($_JS);$i++) {echo "<script src='".$_JS[$i]."?v=".$yh['ver']."'></script>";}
?>
	</head>
	<body>

		<div class="navbar-wrapper">
		  <div class="container-fluid">
			

		    <div class="navbar navbar-fixed-top">
		      	<div class="row navbar-1jool">
		      		<div class="col-xs-6 col-md-2 col-md-offset-2 logo-area">
		      			<a href="<?=$yh['path']?>/index.php"><img class="img-responsive logo-image" src="<?=$yh['path']?>/img/header/logo.png"></a>
		      		</div>
					
					<div class="col-xs-6 col-md-7 col-md-offset-1 margin-t">
						
							<div class="register_program header_right">
								<a href="">
									<img class="img-responsive header_menu" src="<?=$yh['path']?>/img/header/program-register.png">
								</a>
							</div>

							<div class="facebook_link header_right">
								<a href="">
									<img class="img-responsive header_menu" src="<?=$yh['path']?>/img/header/facebook-link.png">
								</a>
							</div>

							<div class="login header_right">
								<a href="">
									<img class="img-responsive header_menu" src="<?=$yh['path']?>/img/header/login.png">
								</a>
							</div>

							<div class="register header_right">
								<a href="">
									<img class="img-responsive header_menu" src="<?=$yh['path']?>/img/header/register.png">
								</a>
							</div>

						
					</div>

		      	</div>
		      		<div class="bottom-nav">		      				      	
						
				        <ul class="nav nav-pills">
				        	<li><a href="#">처방전 전체보기</a></li>
						    <li><a href="#">체험</a></li>
						    <li><a href="#">여행</a></li> 
						    <li><a href="#">상품</a></li> 
						</ul>
						
					</div>				    						    								      
		    </div>
		  </div><!-- /container -->
		</div><!-- /navbar wrapper -->
