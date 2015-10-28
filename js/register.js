


/**
* 전역변수선언
**/






/**
* 초기구동
**/
$(function () {
	$('.btn-toggle').click(function() {
	    $(this).find('.btn').toggleClass('active');  
	    
	    if ($(this).find('.btn-primary').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-primary');
	    }
	    if ($(this).find('.btn-danger').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-danger');
	    }
	    if ($(this).find('.btn-success').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-success');
	    }
	    if ($(this).find('.btn-info').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-info');
	    }
	    
	    $(this).find('.btn').toggleClass('btn-default');
       
	});
/*
$('form').submit(function(){
	alert($(this["options"]).val());
    return false;
});*/


});

/**
* 함수선언
**/

function doRegister() {
	var inputEmail = $(".inputEmail");
	var inputPassword = $(".inputPassword");
	var inputConfirmPassword = $(".inputConfirmPassword");
	var inputConfirmPasswordAnswer = $(".inputConfirmPasswordAnswer");
	var inputName = $(".inputName");
	var inputPhone1 = $('.inputPhone1');
	var inputPhone2 = $('.inputPhone2');
	var inputPhone3 = $('.inputPhone3');
	var inputTelephone1 = $(".inputTelephone1");
	var inputTelephone2 = $(".inputTelephone2");
	var inputTelephone3 = $(".inputTelephone3");

	var regEmail = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	var regPassword =  /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;	//영문 숫자 혼용 6-20자 사이
	var regName = /^[가-힣]{2,4}|[a-zA-Z]{2,10}\s[a-zA-Z]{2,10}$/;
	var regNumber = /^[0-9]*$/;
	var regPhone = /^[0-9]{3,4}$/;

	if(!inputEmail.val()){
        alert('이메일주소를 입력해 주세요');
        inputEmail.focus();
        return false;
    } else {
	    if(!regEmail.test(inputEmail.val())) {
	        alert('이메일 주소가 유효하지 않습니다');
	        inputEmail.focus();
	        return false;
	    }
    }

    if (!inputPassword.val()) {
    	alert('비밀번호를 입력해 주세요');
    	inputPassword.focus();
    	return false;
    } else {
    	if(!regPassword.test(inputPassword.val())) {
	        alert('비밀번호는 영문 숫자 혼용 6~20자 사이로 설정해야합니다.');
	        inputPassword.focus();
	        return false;
    	}
	}

	if(!inputConfirmPassword.val()) {
		alert('비밀번호 확인 값을 입력해 주세요.');
		inputConfirmPassword.focus();
		return false;
	} else {
		if(inputPassword.val() != inputConfirmPassword.val()) {
			alert('비밀번호와 비밀번호 확인값이 일치하지 않습니다.');
			inputConfirmPassword.focus();
			return false;
		}

	}

	if(!inputConfirmPasswordAnswer.val()) {
		alert('퀴즈 정답을 입력해 주세요.');
		inputConfirmPasswordAnswer.focus();
		return false;
	}

	if(!inputConfirmPasswordAnswer.val()) {
		alert('퀴즈 정답을 입력해 주세요.');
		inputConfirmPasswordAnswer.focus();
		return false;
	}

	if(!inputName.val()) {
		alert('이름을 입력해 주세요.');
		inputName.focus();
		return false;
	} else {
		if(!regName.test(inputName.val())) {
	        alert('이름에는 문자열만 입력할 수 있습니다.');
	        inputName.focus();
	        return false;
    	}
	}
	
	if(!regNumber.test(inputPhone1.val())) {
        alert('전화번호에는 숫자만 입력할 수 있습니다.');
        inputPhone1.focus();
        return false;
	}
	if(!regNumber.test(inputPhone2.val())) {
        alert('전화번호에는 숫자만 입력할 수 있습니다.');
        inputPhone2.focus();
        return false;
	}
	if(!regNumber.test(inputPhone3.val())) {
        alert('전화번호에는 숫자만 입력할 수 있습니다.');
        inputPhone3.focus();
        return false;
	}

	if(!inputTelephone1.val()) {
		alert('핸드폰 번호를 입력해 주세요.');
		inputTelephone1.focus();
		return false;
	} else {
		if(!regPhone.test(inputTelephone1.val())) {
	        alert('3~4 자리 숫자만 입력가능합니다.');
	        inputTelephone1.focus();
	        return false;
    	}
	}
	if(!inputTelephone2.val()) {
		alert('핸드폰 번호를 입력해 주세요.');
		inputTelephone2.focus();
		return false;
	} else {
		if(!regPhone.test(inputTelephone2.val())) {
	        alert('3~4 자리 숫자만 입력가능합니다.');
	        inputTelephone2.focus();
	        return false;
    	}
	}
	if(!inputTelephone3.val()) {
		alert('핸드폰 번호를 입력해 주세요.');
		inputTelephone3.focus();
		return false;
	} else {
		if(!regPhone.test(inputTelephone3.val())) {
	        alert('3~4 자리 숫자만 입력가능합니다.');
	        inputTelephone3.focus();
	        return false;
    	}
	}


	if($('.inputEmailAccept').is(":checked")) {
		$('.inputEmailAcceptValue').val("1");
	} else {
		$('.inputEmailAcceptValue').val("0");
	}
	

	$(".registerForm").submit();
}

function fillForm() {
	var inputEmail = $(".inputEmail");
	var inputPassword = $(".inputPassword");
	var inputConfirmPassword = $(".inputConfirmPassword");
	var inputConfirmPasswordAnswer = $(".inputConfirmPasswordAnswer");
	var inputName = $(".inputName");
	var inputPhone1 = $('.inputPhone1');
	var inputPhone2 = $('.inputPhone2');
	var inputPhone3 = $('.inputPhone3');
	var inputTelephone1 = $(".inputTelephone1");
	var inputTelephone2 = $(".inputTelephone2");
	var inputTelephone3 = $(".inputTelephone3");
	var inputAddress2 = $(".inputAddress2");

	inputEmail.val("yprecious@gmail.com");
	inputPassword.val("dkdlvhs6");
	inputConfirmPassword.val("dkdlvhs6");
	inputConfirmPasswordAnswer.val("정답");
	inputName.val("유태진");
	inputPhone1.val("123");
	inputPhone2.val("1234");
	inputPhone3.val("5678");
	inputTelephone2.val("6608");
	inputTelephone3.val("8606");
	inputAddress2.val("테스트 상세주소");
}









