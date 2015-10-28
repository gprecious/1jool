<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/register_product.css';
$_CSS[] = $yh['path'].'/css/footer.css';

$showBottomNav = 0;
include_once './head.php';

?>

<div class="contents-wrap">
	<div class="title">
		처방전 등록
	</div>
	<hr>
	<div class="contents-outer">
		<div class="contents-inner container">
			<div class="information">
				간단한 안내 및 배경사진 추가하면 좋을듯
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2 col-md-offset-3">
					<a class="btn btn-default" href="reg_experience_step1.php">체험</a>
				</div>
				<div class="col-xs-4 col-md-2">
					<a class="btn btn-default" href="reg_travel_step1.php">여행</a>
				</div>
				<div class="col-xs-4 col-md-2">
					<a class="btn btn-default" href="reg_product_step1.php">상품</a>
				</div>
			</div>
		</div>
	</div>
</div>




















<?php
include_once './tail.php';
?>