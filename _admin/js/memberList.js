/**
* 전역변수선언
**/
var $page, $lpp, $block, $keyword, $listTable, $chkAll;
/**
* 초기구동
**/
$(function () {
	$page = $('#page');
	$lpp = $('#lpp');
	$block = $('#block');
	$keyword = $('#keyword');
	$listTable = $('#listTable');
	$chkAll = $('#chkAll');

});

/**
* 함수선언
**/
//전체선택 토글하는 함수
function chkAllToggle() {
	if ($chkAll.prop('checked')) $listTable.find("input[name='chk[]']").prop('checked',true);
	else $listTable.find("input[name='chk[]']").prop('checked',false);
}

//개별 체크박스 클릭시 실행하는 함수
function chk() {
	var totalCnt = $listTable.find("input[name='chk[]']").length;
	var chkedCnt = $listTable.find("input[name='chk[]']:checked").length;
	
	if (totalCnt == chkedCnt) $chkAll.prop('checked',true);
	else $chkAll.prop('checked',false);
}



//검색하는 함수
function doSearch() {
	var qs = parseQS();
	qs['page'] = 1;
	qs['keyword'] = $keyword.val();
	location.href=location.pathname+'?'+$.param(qs);
}

//페이지 이동하는 함수
function goToPage(no) {
	var qs = parseQS();
	qs['page'] = no;
	location.href=location.pathname+'?'+$.param(qs);
}

//멤버상세보기로 넘어가는 함수
function goToMemberDetail(no) {
	var qs = parseQS();
	qs['no'] = no;
	
	location.href=yh_path+'/_admin/memberDetail.php?'+$.param(qs);
}