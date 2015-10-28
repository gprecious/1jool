<?php
include_once './_common.php';
include_once $yh['path'].'/lib/yhBoard/yhBoard.php';

$_CSS[] = $yh['path'].'/_admin/css/boardList.css';
$_JS[] = $yh['path'].'/_admin/js/boardList.js';
include_once $yh['path'].'/_admin/head.php';

//GET변수 정리
$page = intval($_GET['page']);
$keyword = trim($_GET['keyword']);
$keyword2 = $sql->real_escape_string(str_replace('%','\%',$keyword));
$lpp = 20;

if ($page < 1) $page = 1;

//전체리스트 갯수 구하기
$q = "SELECT COUNT(*) FROM `".$yhBoard['prefix']."`";

//조건이 있을 경우 조건문 만들기
if ($keyword != '') $q_cond[] = "no='".$keyword2."' OR bo_tableName='".$keyword2."' OR bo_name LIKE '%".$keyword2."%'";

//WHERE 설정
for($i=0;$i<count($q_cond);$i++) {
	if ($i==0) $q .= " WHERE (".$q_cond[$i].")";
	else $q .= " AND (".$q_cond[$i].")";
}

$row = $sql->query($q)->fetch_row();
$totalCnt = $row[0];

//전체페이지 수 구하기
$pageTotal = ceil($totalCnt/$lpp);

//page가 totalPage을 초과했을 경우
if ($pageTotal < 1) $pageTotal = 1;
if ($page > $pageTotal) $page = $pageTotal;

//pageStart, pageEnd설정
$pageStart = floor(($page-1)/10)*10+1;
$pageEnd = ceil(($page)/10)*10;
if ($pageTotal < $pageEnd) {
	$pageEnd = $pageTotal;
}

//쿼리만들기
$q = str_replace('COUNT(*)', '*',$q);

//ORDER BY 설정
$q .= " ORDER BY no DESC";

//LIMIT 설정
$q .= " LIMIT ".(($page-1)*$lpp).", ".$lpp;

//쿼리 실행
$r = $sql->query($q);
?>
<div id="boardList">
	<div class='listTop'>
		<div class="left"></div>
		<div class="right"><button type='button' onclick='goToBoardDetail();'>게시판 추가</button></div>
	</div>
	<table class='listTable' id='listTable'>
		<tr class='head'>
			<td class='chk'><input type='checkbox' id='chkAll' onclick='chkAllToggle();' /></td>
			<td class='no'>No</td>
			<td class='bo_name'>게시판명</td>
			<td class='bo_tableName'>게시판ID</td>
			<td class='bo_postCnt'>글 / 댓글 수</td>
			<td class='bo_wrTime'>생성일</td>
		</tr>
		<?php while($row = $r->fetch_assoc()) { ?>
			<tr>
				<td class='chk'><input type='checkbox' name='chk[]' onclick='chk();' value='<?=$row['no']?>' /></td>
				<td class='no'><?=$row['no']?></td>
				<td class='bo_name' onclick='goToBoardDetail(<?=$row['no']?>)'><?=escapeHtml($row['bo_name'])?></td>
				<td class='bo_tableName'><?=escapeHtml($row['bo_tableName'])?></td>
				<td class='bo_postCnt'><?=$row['bo_postCnt']?> / <?=$row['bo_commentCnt']?></td>
				<td class='bo_wrTime'><?=$row['bo_wrTime']?></td>
			</tr>
		<?php } ?>
		<?php if ($totalCnt == 0) { ?>
			<tr class='noResult'><td colspan='6'>결과가 없습니다.</td></tr>
		<?php } ?>
	</table>
	<div class='searchWrap'>
		<div class="left"><input type='text' id='keyword' placeholder='게시판ID, 게시판명으로 검색해주세요.' value='<?=escapeHtml($keyword)?>' onkeydown='if (event.keyCode==13) doSearch();' /></div>
		<div class="right">총 <span class='num'><?=number_format($totalCnt)?></span>개의 결과</div>
	</div>
	<div class="pageWrap">
		<?php
		if ($pageStart != 1) echo "<a href='javascript:goToPage(1);'>&lt;&lt;</a><a href='javascript:goToPage(".($pageStart-1).");'>&lt;</a>";
		for ($i=$pageStart;$i<=$pageEnd;$i++) {
			echo "<a href='javascript:goToPage(".$i.");'".($i==$page?" class='current'":'').">".$i."</a>";
		}
		if ($pageEnd < $pageTotal) echo "<a href='goToPage(".($pageEnd+1).");'>&gt;</a><a href='goToPage(".$pageTotal.");'>&gt;&gt;</a>";
		?>
	</div>
</div>
<?php
include_once $yh['path'].'/_admin/tail.php';
?>