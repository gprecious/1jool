<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/_admin/css/memberDetail.css';
$_JS[] = $yh['path'].'/_admin/js/memberDetail.js';
include_once $yh['path'].'/_admin/head.php';

/**
* GET변수 정리
**/
$no = intval($_GET['no']);

/**
* $no값이 있다면 회원정보 받기
**/
if ($no) {
	$q = "SELECT * FROM yh_member WHERE no='".$no."'";
	$mbInfo = $sql->query($q)->fetch_assoc();
	if (!$mbInfo) {
		echo "<script>alert('존재하지 않는 회원입니다.');</script>";
		exit;
	}
	$mbInfo['mb_group'] = explode(',',$mbInfo['mb_group']);
}

?>
<div id="memberDetail">
	<input type='hidden' name='no' value='<?=$no?>' />
	<div class="memberInfo">
		<div class="row">
			<div class="left">아이디</div>
			<div class="right"><input type='text' class='inputText01<?=$no?' readonly':''?>' name='mb_id' value='<?=escapeHtml($mbInfo['mb_id'])?>'<?=$no?' readonly':''?> /></div>
		</div>
		<div class="row">
			<div class="left">비밀번호</div>
			<div class="right"><input type='text' class='inputText01' name='mb_pw' /><button type='button' class='resetBtn' onclick="generatePw();">비밀번호생성</button></div>
		</div>
		<div class="row">
			<div class="left">이름</div>
			<div class="right"><input type='text' class='inputText01' name='mb_name' value='<?=escapeHtml($mbInfo['mb_name'])?>' /></div>
		</div>
		<div class="row">
			<div class="left">닉네임</div>
			<div class="right"><input type='text' class='inputText01' name='mb_nick' value='<?=escapeHtml($mbInfo['mb_nick'])?>' /></div>
		</div>
		<div class="row">
			<div class="left">그룹</div>
			<div class="right">
				<?php
				$q = "SELECT * FROM yh_group ORDER BY no ASC";
				$r = $sql->query($q);
				while($row = $r->fetch_assoc()) {
					if ($no) echo "<label><input type='checkbox' name='mb_group[]' value='".escapeHtml($row['gp_id'])."'".(in_array($row['gp_id'],$mbInfo['mb_group'])?' checked':'')." /> ".escapeHtml($row['gp_name'])."</label>";
					else echo "<label><input type='checkbox' name='mb_group[]' value='".escapeHtml($row['gp_id'])."'".($row['gp_id']=='default'?' checked':'')." /> ".escapeHtml($row['gp_name'])."</label>";
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="left">상태</div>
			<div class="right"><label><input type="radio" name='mb_block' value='0'<?=!$mbInfo['mb_block']?' checked':''?> /> 정상</label><label><input type="radio" name='mb_block' value='1'<?=$mbInfo['mb_block']?' checked':''?> /> 차단</label></div>
		</div>
		<?php if ($no) { ?>
		<div class="row">
			<div class="left">최종접속일</div>
			<div class="right"><input type='text' class='inputText01 readonly' value='<?=escapeHtml($mbInfo['mb_loginTime'])?>' readonly /></div>
		</div>
		<div class="row">
			<div class="left">가입일</div>
			<div class="right"><input type='text' class='inputText01 readonly' value='<?=escapeHtml($mbInfo['mb_joinTime'])?>' readonly /></div>
		</div>
		<?php } ?>
	</div>
	<div class="btnWrap">
		<button type='button' onclick='doSubmit();'>저장하기</button>
	</div>
</div>


<?php
include_once $yh['path'].'/_admin/tail.php';
?>