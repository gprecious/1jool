<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/_admin/css/memberList.css';
$_JS[] = $yh['path'].'/_admin/js/memberList.js';
include_once $yh['path'].'/_admin/head.php';

//GET변수 정리
$page = intval($_GET['page']);
$lpp = intval($_GET['lpp']);
$block = intval($_GET['block']);
$keyword = trim($_GET['keyword']);
$keyword2 = $sql->real_escape_string(str_replace('%','\%',$keyword));

if ($page < 1) $page = 1;
if ($lpp < 1) $lpp = 20;
if ($_GET['block'] == '') $block = 2;

$lppArray = array(20, 30, 50, 100, 200);
$blockArray = array(0 => '정상', 1=>'차단', 2=>'전체');

//전체리스트 갯수 구하기
$q = "SELECT COUNT(*) FROM yh_member";

//조건이 있을 경우 조건문 만들기
if ($block != 2) $q_cond[] = "mb_block='".$block."'";
if ($keyword != '') $q_cond[] = "no='".$keyword2."' OR mb_id LIKE '%".$keyword2."%' OR  mb_name LIKE '%".$keyword2."%' OR mb_nick LIKE '%".$keyword2."%'";

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
<input type='hidden' id='page' value='<?=$page?>' />
<div id="memberList">
	<div class='listTop'>
		<div class="left">
			<select id="lpp" onchange='doReload();'>
				<?php for($i=0;$i<count($lppArray);$i++) echo "<option value='".$lppArray[$i]."'".($lpp==$lppArray[$i]?' selected':'').">".$lppArray[$i]."명씩 보기</option>"; ?>
			</select>
			<select id="block" onchange='doReload();'>
				<?php foreach ($blockArray as $k=>$v) echo "<option value='".$k."'".($block==$k?' selected':'').">".$v."</option>";?>
			</select>
		</div>
		<div class="right"><button type="button" onclick="location.href='./memberDetail.php'">회원추가</button></div>
	</div>
	<table class='listTable' id='listTable'>
		<tr class='head'>
			<td class='chk'><input type='checkbox' id='chkAll' onclick='chkAllToggle();' /></td>
			<td class='no'>No</td>
			<td class='mb_id'>ID</td>
			<td class='mb_name'>이름</td>
			<td class='mb_nick'>닉네임</td>
			<td class='mb_block'>상태</td>
			<td class='wr_time'>가입일</td>
		</tr>
		<?php while($row = $r->fetch_assoc()) { ?>
			<tr>
				<td class='chk'><input type='checkbox' name='chk[]' onclick='chk();' value='<?=$row['no']?>' /></td>
				<td class='no'><?=$row['no']?></td>
				<td class='mb_id' onclick="goToMemberDetail(<?=$row['no']?>)"><?=escapeHtml($row['mb_id'])?></td>
				<td class='mb_name'><?=escapeHtml($row['mb_name'])?></td>
				<td class='mb_nick'><?=escapeHtml($row['mb_nick'])?></td>
				<td class='mb_block b<?=$row['mb_block']?>'><?=$row['mb_block']?'차단':'정상'?></td>
				<td class='wr_time'><?=$row['mb_joinTime']?></td>
			</tr>
		<?php } ?>
		<?php if ($totalCnt == 0) { ?>
			<tr class='noResult'><td colspan='7'>결과가 없습니다.</td></tr>
		<?php } ?>
	</table>
	<div class='searchWrap'>
		<div class="left"><input type='text' id='keyword' placeholder='아이디, 이름, 닉네임으로 검색해주세요.' value='<?=escapeHtml($keyword)?>' onkeydown='if (event.keyCode==13) doSearch();' /></div>
		<div class="right">총 <span class='num'><?=number_format($totalCnt)?></span>개의 결과</div>
	</div>
	<div class="pageWrap">
		<?php
		if ($pageStart != 1) echo "<a href='javascript:goToPage(1);'>&lt;&lt;</a><a href='javascript:goToPage(".($pageStart-1).");'>&lt;</a>";
		for ($i=$pageStart;$i<=$pageEnd;$i++) {
			echo "<a href='javascript:goToPage(".$i.");'".($i==$page?" class='current'":'').">".$i."</a>";
		}
		if ($pageEnd < $pageTotal) echo "<a href='javascript:goToPage(".($pageEnd+1).");'>&gt;</a><a href='javascript:goToPage(".$pageTotal.");'>&gt;&gt;</a>";
		?>
	</div>
</div>
<?php
include_once $yh['path'].'/_admin/tail.php';
?>