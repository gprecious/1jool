<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/register_product.css';
$_CSS[] = $yh['path'].'/css/footer.css';

$showBottomNav = 0;
include_once './head.php';

?>
<script>
	var is_member = <?= intval($is_member)?>;
	var yh_path = "<?=$yh['path']?>";

	if(is_member == 0) {
		alert("로그인이 필요한 메뉴입니다. 로그인해주세요.");
		location.href = yh_path;
	}
</script>

<?php
	if ($is_member != 0) {
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
	}
?>




















<?php
include_once './tail.php';
?>