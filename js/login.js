/**
* 전역변수선언
**/
var $login;

/**
* 초기구동
**/
$(function () {
	$login = $('#loginForm');
});

/**
* 함수선언
**/
function doLogin() {
	//필요한 것만
	$.ajax({
		type:'POST',
		url:yh_path+'/ajax/login_ok.php',
		data:$login.find('input').serialize(),
		dataType:'json',
		success:function(data) {
			if (data['status'] == 'fail') {
				alert(data['msg']);
				return;
			}

			location.href=yh_path;
		},
		error:function(xhr, status, error) {
			console.log(status);
			console.log(xhr.responseText);
		}
	});
}