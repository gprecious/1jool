<?php
include_once './_common.php';
include_once $yh['path'].'/lib/yhBoard/yhBoard.php';

$_CSS[] = $yh['path'].'/_admin/css/boardDetail.css';
$_JS[] = $yh['path'].'/_admin/js/boardDetail.js';
include_once $yh['path'].'/_admin/head.php';

/**
* GET변수 정리
**/
$no = intval($_GET['no']);

/**
* 회원정보 받기
**/
$q = "SELECT * FROM `".$yhBoard['prefix']."` WHERE no='".$no."'";
$boardInfo = $sql->query($q)->fetch_assoc();
if ($boardInfo) {
	$boardInfo['bo_basicPerm'] = json_decode($boardInfo['bo_basicPerm'],1);
	$boardInfo['bo_groupPerm'] = json_decode($boardInfo['bo_groupPerm'],1);
}

?>
<div id="boardDetail">
	<input type='hidden' name='no' value='<?=$no?>' />
	<div class="boardInfo">
		<div class="row">
			<div class="left">게시판ID</div>
			<div class="right"><input type='text' class='inputText01<?=$boardInfo?' readonly':''?>' name='bo_tableName' value='<?=escapeHtml($boardInfo['bo_tableName'])?>'<?=$boardInfo?' readonly':''?> /></div>
		</div>
		<div class="row">
			<div class="left">게시판명</div>
			<div class="right"><input type='text' class='inputText01' name='bo_name' value='<?=escapeHtml($boardInfo['bo_name'])?>' /></div>
		</div>
		<div class="row">
			<div class="left">게시판설명</div>
			<div class="right"><textarea name='bo_desc'><?=escapeHtml($boardInfo['bo_desc'])?></textarea></div>
		</div>
		<div class="row">
			<div class="left">기본권한</div>
			<div class="right" id='basicPerm'>
				<label><input type='checkbox' name='bo_basicPerm[r]' value='1'<?=($boardInfo['bo_basicPerm']['r']==1?' checked':'')?> onclick='chgBasicPerm(this);' />본문읽기</label>
				<label><input type='checkbox' name='bo_basicPerm[w]' value='1'<?=($boardInfo['bo_basicPerm']['w']==1?' checked':'')?> onclick='chgBasicPerm(this);' />본문쓰기</label>
				<label><input type='checkbox' name='bo_basicPerm[m]' value='1'<?=($boardInfo['bo_basicPerm']['m']==1?' checked':'')?> onclick='chgBasicPerm(this);' />관리</label>
			</div>
		</div>
		<div class="row">
			<div class="left">그룹별권한</div>
			<div class="right" id='groupPerm'>
				<?php
				//모든 그룹에 대해 권한 출력
				$q = "SELECT * FROM `yh_group` ORDER BY no ASC";
				$r = $sql->query($q);
				while($row = $r->fetch_assoc()) {
					//groupPerm에 해당 그룹의 권한이 있을 경우 그 권한 표시
					if ($boardInfo['bo_groupPerm'] !== null && array_key_exists($row['gp_id'], $boardInfo['bo_groupPerm'])) {
						echo "<div class='row'>
							<div class='left'>".escapeHtml($row['gp_name']."(".$row['gp_id'].")")."</div>
							<div class='right'>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][r]' value='1'".($boardInfo['bo_groupPerm'][$row['gp_id']]['r']==1?' checked':'')." />본문읽기</label>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][w]' value='1'".($boardInfo['bo_groupPerm'][$row['gp_id']]['w']==1?' checked':'')." />본문쓰기</label>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][m]' value='1'".($boardInfo['bo_groupPerm'][$row['gp_id']]['m']==1?' checked':'')." />관리</label><br />
								<label><input type='checkbox' class='default' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][defaultFlag]' value='1' onclick='toggleBasicPermFlag(this);' />기본권한 따르기</label>
							</div>
						</div>";
					//그게 아닐 경우 기본권한으로 표시
					} else {
						echo "<div class='row'>
							<div class='left'>".escapeHtml($row['gp_name']."(".$row['gp_id'].")")."</div>
							<div class='right'>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][r]' value='1'".($boardInfo['bo_basicPerm']['r']==1?' checked':'')." disabled />본문읽기</label>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][w]' value='1'".($boardInfo['bo_basicPerm']['w']==1?' checked':'')." disabled />본문쓰기</label>
								<label><input type='checkbox' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][m]' value='1'".($boardInfo['bo_basicPerm']['m']==1?' checked':'')." disabled />관리</label><br />
								<label><input type='checkbox' class='default' name='bo_groupPerm[".escapeHtml($row['gp_id'])."][defaultFlag]' value='1' onclick='toggleBasicPermFlag(this);' checked />기본권한 따르기</label>
							</div>
						</div>";
					}
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="left">파일업로드</div>
			<div class="right"><label><input type='checkbox' name='bo_fileUploadFlag' value='1'<?=($boardInfo['bo_fileUploadFlag']==1?' checked':'')?> />파일업로드사용</label></div>
		</div>
		<div class="row">
			<div class="left">업로드제한</div>
			<div class="right"><input type='text' class='inputText01' name='bo_fileUploadSize' value='<?=escapeHtml($boardInfo['bo_fileUploadSize'])?>' /></div>
		</div>
		<div class="row">
			<div class="left">HTML사용</div>
			<div class="right"><label><input type='checkbox' name='bo_htmlFlag' value='1'<?=($boardInfo['bo_htmlFlag']==1?' checked':'')?> />HTML사용</label></div>
		</div>
		<?php if ($boardInfo) { ?>
		<div class="row">
			<div class="left">글 수</div>
			<div class="right"><input type='text' class='inputText02 readonly' value='<?=escapeHtml($boardInfo['bo_postCnt'])?>' readonly /></div>
		</div>
		<div class="row">
			<div class="left">댓글 수</div>
			<div class="right"><input type='text' class='inputText02 readonly' value='<?=escapeHtml($boardInfo['bo_commentCnt'])?>' readonly /></div>
		</div>
		<div class="row">
			<div class="left">생성일</div>
			<div class="right"><input type='text' class='inputText01 readonly' value='<?=escapeHtml($boardInfo['bo_wrTime'])?>' readonly /></div>
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