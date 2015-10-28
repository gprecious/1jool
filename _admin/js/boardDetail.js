/**
* 전역변수 선언
**/
//jQuery Element
var $boardDetail, $basicPerm, $groupPerm;
/**
* 초기구동
**/
$(function () {
	//jQuery Element Caching
	$boardDetail = $('#boardDetail');
	$basicPerm = $('#basicPerm');
	$groupPerm = $('#groupPerm');

});

/**
* 함수 선언
**/
function doSubmit() {
	if (!confirm('저장하시겠습니까?')) return;

	$.ajax({
		type:'POST',
		url:yh_path+'/_admin/ajax/boardDetail_ok.php',
		data:$boardDetail.find('input, textarea').serialize(),
		dataType:'json',
		success:function(data) {
			if (data['status'] == 'fail') {
				alert(data['msg']);
				return;
			}

			var qs = parseQS();
			delete qs['no'];
			location.href=yh_path+'/_admin/boardList.php?'+$.param(qs);
		},
		error:function(xhr, status, error) {
			console.log(status);
			console.log(xhr.responseText);
		}
	});
}

//기본권한 따르기 체크박스를 선택한 경우
function toggleBasicPermFlag (obj) {
	var $obj = $(obj).parent().parent();

	//기본권한을 따르도록 체크한 경우
	if ($(obj).prop('checked')) {
		console.log($basicPerm.find('input[type=checkbox]'));
		$basicPerm.find('input[type=checkbox]').each(function (i,e) {
			$($obj.find('input[type=checkbox]')[i]).prop('checked',$(e).prop('checked')).prop('disabled',true);
		});
	} else {

		for(var i=0;i<6;i++) $($obj.find('input[type=checkbox]')[i]).prop('disabled',false);
	}
}

//기본권한을 수정한 경우 그룹권한 관리에서 기본권한 따르기에 체크한 그룹의 권한을 같이 바꿈
function chgBasicPerm(obj) {
	$obj = $(obj);
	var checked = $obj.prop('checked');
	var idx = $obj.parent().index('label');

	$groupPerm.find('input.default').each(function (i,e) {
		//기본권한 따르기에 체크가 되어있는 경우
		if ($(e).prop('checked')) {
			$($(e).parent().parent().find('input')[idx]).prop('checked', checked);
		}
	});
}