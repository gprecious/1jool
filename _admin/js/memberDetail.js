/**
* 전역변수선언
**/
//jQuery Element
var $memberDetail;
/**
* 초기구동
**/
$(function () {
	//jQuery Element Caching
	$memberDetail = $('#memberDetail');

});

/**
* 함수선언
**/
//랜덤하게 패스워드를 생성하는 함수
function generatePw() {
	var pw = '';
	for (var i=0;i<6;i++) {
		var rand = Math.floor(Math.random()*36);

		if (rand < 26) {
			pw += String.fromCharCode(rand+97);
		} else {
			pw += String.fromCharCode(rand+22);
		}
	}
	$memberDetail.find('input[name=mb_pw]').val(pw).show();
}

function doSubmit() {
	if (!confirm('저장하시겠습니까?')) return;

	//필요한 것만
	$.ajax({
		type:'POST',
		url:yh_path+'/_admin/ajax/memberDetail_ok.php',
		data:$memberDetail.find('input, select').serialize(),
		dataType:'json',
		success:function(data) {
			if (data['status'] == 'fail') {
				alert(data['msg']);
				return;
			}

			var qs = parseQS();
			delete qs['no'];
			location.href=yh_path+'/_admin/memberList.php?'+$.param(qs);
		},
		error:function(xhr, status, error) {
			console.log(status);
			console.log(xhr.responseText);
		}
	});
}