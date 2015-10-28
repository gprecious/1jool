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

		<link rel="stylesheet" href="<?=$yh['path']?>/bootstrap-3.3.5-dist/css/bootstrap.min.css">
		<link rel='stylesheet' href='<?=$yh['path']?>/css/common.css?v=<?=$yh['ver']?>' />

		<script src='<?=$yh['path']?>/lib/jquery/jquery-2.1.3.min.js'></script>
		<script src="<?=$yh['path']?>/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		<script src='<?=$yh['path']?>/js/common.js?v=<?=$yh['ver']?>'></script>
		<script src="<?=$yh['path']?>/js/login.js"></script>
		<script>
			$('.dropdown-toggle').dropdown();
		</script>

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
						<a href="<?=$yh['path']?>/register_product.php" class="register_program header-right">
							<div>								
								처방전 등록하기 <span class="glyphicon glyphicon-th-list"></span>								
							</div>
						</a>

						<a href="https://www.facebook.com/hanjulyakguk" target="_blank" class="facebook_link header-right">
							<div>								
								<span class="glyphicon glyphicon-th-list"></span> 한줄약국 페이스북
							</div>
						</a>
						<?php
							if(!$is_member) {
						?>
						<a href="" class="login header-right" data-toggle="modal" data-target="#loginModal">
							<div>								
								로그인
							</div>
						</a>
						
						<a href="register.php" class="register header-right">
							<div>								
								회원가입
							</div>
						</a>
						<?php
							} else {
						?>
						<div class="member-info-wrap">
							<div class="dropdown">
								
								<a class="member-info header-right" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
									<?=$member['mb_name'] ?> 회원님
									<span class="caret"></span>
								</a>
								
								<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
									<li><a href="#">내 정보</a></li>
									<li><a href="#">위시리스트</a></li>
									<li><a href="#">주문내역</a></li>
									<li><a href="#">도움말</a></li>
									<li><a href="<?=$yh['path']?>/logout.php">로그아웃</a></li>
								
								</ul>
								
							</div>
						</div>
						<?php
							}
						?>			
					</div>

		      	</div>
		      	<?php
		      		if($showBottomNav) {
		      	?>
		      		<div class="bottom-nav">		      				      	
						
				        <ul class="nav nav-pills">
				        	<li><a href="#">처방전 전체보기</a></li>
						    <li><a href="#">체험</a></li>
						    <li><a href="#">여행</a></li> 
						    <li><a href="#">상품</a></li> 
						</ul>
						
					</div>
				<?php
					}
				?>				    						    								      
		    </div>
		  </div><!-- /container -->
		</div><!-- /navbar wrapper -->

		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="loginModalLabel">Login</h4>
		      </div>
		      <div class="modal-body">
		        <form class="loginForm" role="form" id="loginForm">
		          <div class="form-group">
		            <label for="inputEmail" class="sr-only">Email Address</label>
		            <input type="email" id="inputEmail" name="inputEmail" onkeydown='if (event.keyCode==13) doLogin();'
		             placeholder="Email Adress" class="form-control" required autofocus>
		          </div>
		          <div class="form-group">
		            <label for="inputPassword" class="sr-only">Input Password</label>
		            <input type="password" id="inputPassword" name="inputPassword" onkeydown='if (event.keyCode==13) doLogin();'
		             placeholder="Password" class="form-control" required>
		          </div>
		          
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" onclick='doLogin();'>Login</button>
		      </div>
		    </div>
		  </div>
		</div>
