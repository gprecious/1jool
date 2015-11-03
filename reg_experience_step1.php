<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/reg_experience_step1.css';
$_CSS[] = $yh['path'].'/css/footer.css';
$_CSS[] = $yh['path'].'/daumeditor/css/editor.css';


$_JS[] = $yh['path'].'/daumeditor/js/editor_loader.js';
$_JS[] = $yh['path'].'/js/reg_product.js';
//$_JS[] = $yh['path'].'/js/index.js';
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
<!--
다음오픈에디터로 상품 설명 구현
-->
<?php
	if ($is_member != 0) {

?>


<div class="contents-wrap container">
	<div class="contents-outer">
		<div class="contents-inner">
			<!-- editor 데이터 전송을 위한 form -->
			<form class="reg_product_form" role="form" name="tx_editor_form" id="tx_editor_form" 
			action="<?=$yh['path'] ?>/reg_experience_step2.php" method="post" accept-charset="utf-8">
				
				<div class="title row">
					<div class="col-xs-12">체험 등록하기</div>
				</div>
				<hr>
				<div class="form-group row">
					<div class="col-xs-12 col-md-1 col-md-offset-2">
						<label for="pd_name">제목</label>
					</div>
					<div class="col-xs-12 col-md-4">
						<input type="text" name="pd_name" class="form-control pd_name">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-xs-12 col-md-1 col-md-offset-2">
						<label for="pd_shortDesc">간단한 체험 설명</label>
					</div>
					<div class="col-xs-12 col-md-4">
						<input type="text" name="pd_shortDesc" class="form-control pd_shortDesc">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-xs-12 col-md-1 col-md-offset-2">
						<label for="pd_price">체험 가격</label>
					</div>
					<div class="col-xs-12 col-md-4">
						<input type="text" name="pd_price" class="form-control pd_price">
					</div>
				</div>

		<!-- 에디터 시작 -->
		<!--
			@decsription
			등록하기 위한 Form으로 상황에 맞게 수정하여 사용한다. Form 이름은 에디터를 생성할 때 설정값으로 설정한다.
		-->
			<?php
				include_once './daumeditor_form.php';
			?>
			<br>
			<br>
			<div class="open_button_wrap col-xs-12 col-md-10 col-md-offset-2">
				<a class="pd_opt_open">가격 상세 옵션 열기 / 닫기</a>
			</div>

			<div class="form-group row price_opt">

				<div class="col-xs-12">
					<label for="pd_opt">체험 날짜 1</label>
				</div>
				<div class="col-xs-6 col-md-1 col-md-offset-2">
					<label for="opt_date">날짜</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_date[]" class="form-control opt_date">
				</div>
				<div class="col-xs-6 col-md-1">
					<label for="opt_time">시간</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_time[]" class="form-control opt_time" placeholder="원">
				</div>
			</div>

			<div class="form-group row price_opt">

				<div class="col-xs-12">
					<label for="pd_opt">체험 날짜 2</label>
				</div>
				<div class="col-xs-6 col-md-1 col-md-offset-2">
					<label for="opt_date">날짜</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_date[]" class="form-control opt_date">
				</div>
				<div class="col-xs-6 col-md-1">
					<label for="opt_time">시간</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_time[]" class="form-control opt_time" placeholder="원">
				</div>
			</div>

			<div class="form-group row price_opt">

				<div class="col-xs-12">
					<label for="pd_opt">체험 날짜 3</label>
				</div>
				<div class="col-xs-6 col-md-1 col-md-offset-2">
					<label for="opt_date">날짜</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_date[]" class="form-control opt_date">
				</div>
				<div class="col-xs-6 col-md-1">
					<label for="opt_time">시간</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_time[]" class="form-control opt_time" placeholder="원">
				</div>
			</div>

			<div class="form-group row price_opt">

				<div class="col-xs-12">
					<label for="pd_opt">체험 날짜 4</label>
				</div>
				<div class="col-xs-6 col-md-1 col-md-offset-2">
					<label for="opt_date">날짜</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_date[]" class="form-control opt_date">
				</div>
				<div class="col-xs-6 col-md-1">
					<label for="opt_time">시간</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_time[]" class="form-control opt_time" placeholder="원">
				</div>
			</div>

			<div class="form-group row price_opt">

				<div class="col-xs-12">
					<label for="pd_opt">체험 날짜 5</label>
				</div>
				<div class="col-xs-6 col-md-1 col-md-offset-2">
					<label for="opt_date">날짜</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_date[]" class="form-control opt_date">
				</div>
				<div class="col-xs-6 col-md-1">
					<label for="opt_time">시간</label>
				</div>
				<div class="col-xs-6 col-md-3">
					<input type="text" name="opt_time[]" class="form-control opt_time" placeholder="원">
				</div>
			</div>

			<div><button onclick='setAllForm()'>채우기</button></div>
			<div><button type="button" class="btn btn-primary" onclick='saveContent()'>상품 등록</button></div>
		</form>

		</div>
	</div>
</div>

<?php
	}
include_once './tail.php';
?>