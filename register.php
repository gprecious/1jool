<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/register.css';
$_CSS[] = $yh['path'].'/css/footer.css';
$_JS[] = $yh['path'].'/js/register.js';
$showBottomNav = 0;
include_once './head.php';

?>

  <div class="container register-outer">
    <div class="register-inner">
      <div class="register-header">
        <h4 class="register-title" id="registerModalLabel">회원가입</h4>
      </div>
      <div class="register-body">
        <form action="register_ok.php" method="post" class="registerForm" role="form">
          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputEmail">이메일 주소</label>
            </div>
            <div class="col-xs-7 col-md-3">
              <input type="email" name="inputEmail" id="inputEmail" placeholder="이메일 주소" class="form-control inputEmail" required="required">  
              <input type="hidden" name="emailCheck" id="emailCheck">
            </div>
            <div class="col-xs-2 col-md-1">
              <button type="button" class="btn btn-primary" onclick="emailDupCheck()">중복확인</button>
            </div>        
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputPassword">비밀번호</label>
            </div>
            <div class="col-xs-9 col-md-3">
              <input type="password" name="inputPassword" id="inputPassword" placeholder="비밀번호" class="form-control inputPassword" required>  
            </div>           
          </div>
          
          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputConfirmPassword">비밀번호 확인</label>
            </div>
            <div class="col-xs-9 col-md-3">
              <input type="password" name="inputConfirmPassword" id="inputConfirmPassword" placeholder="비밀번호 확인" class="form-control inputConfirmPassword" required>  
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputPasswordQuestion">퀴즈</label>
            </div>
            <div class="col-xs-9 col-md-3">
              <select class="form-control" name="inputPasswordQuestion" id="inputPasswordQuestion">
                <option>기억에 남는 추억의 장소는?</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputConfirmPasswordAnswer">퀴즈정답</label>
            </div>
            <div class="col-xs-9 col-md-3">
              <input type="text" name="inputConfirmPasswordAnswer" id="inputConfirmPasswordAnswer" placeholder="비밀번호 확인용 퀴즈 정답" class="form-control inputConfirmPasswordAnswer" required>  
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputName">이름</label>
            </div>
            <div class="col-xs-9 col-md-3">
              <input type="text" name="inputName" id="inputName" placeholder="이름" class="form-control inputName" required>  
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputAddress">주소</label>
            </div>
            <div class="col-xs-9 col-md-6">
            	<div class="row">
            		<div class="col-xs-7">
		              <input type="text" name="inputPostcode" id="sample2_postcode" placeholder="우편번호" class="form-control" required="required" readonly>
		            </div>
		            <div class="col-xs-5">
		              <input type="button" onclick="sample2_execDaumPostcode()" value="찾기" class="form-control"><br>
		            </div>
		            <div class="col-xs-12 col-md-7">
		            	<input type="text" name="inputAddress1" id="sample2_address" placeholder="한글주소" class="form-control" readonly>
		            </div>
		            <div class="col-xs-12 col-md-5">
		            	<input type="text" name="inputAddress2" id="sample2_address2" placeholder="상세주소" class="form-control inputAddress2">
		            </div>
            	</div>
            </div>
	            
            
          </div>

		<!-- 주소 api 시작 -->
			<div id="findAddressLayer">
				<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" 
				style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
			</div>

		<!-- 주소 api 끝 -->

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputPhone1">일반전화</label>
            </div>
            <div class="col-xs-9 col-md-3 align-left">
              
				<input type="text" name="inputPhone1" id="inputPhone1" class="form-control telephone-num inputPhone1" placeholder="지역번호" maxlength="3">
               - 
             	<input type="text" name="inputPhone2" id="inputPhone2" placeholder="일반전화" class="form-control telephone-num inputPhone2" maxlength="4"> - 
             	<input type="text" name="inputPhone3" id="inputPhone3" placeholder="일반전화" class="form-control telephone-num inputPhone3" maxlength="4">  
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputTelephone1">휴대전화</label>
            </div>
            <div class="col-xs-9 col-md-3 align-left">
              <select class="form-control telephone-num inputTelephone1" name="inputTelephone1" id="inputTelephone1">
                <option>010</option>
                <option>011</option>
                <option>016</option>
                <option>018</option>
                <option>019</option>
              </select> - 
              <input type="text" name="inputTelephone2" id="inputTelephone2" placeholder="휴대전화" class="form-control telephone-num inputTelephone2" required maxlength="4"> - 
              <input type="text" name="inputTelephone3" id="inputTelephone3" placeholder="휴대전화" class="form-control telephone-num inputTelephone3" required maxlength="4">  
            </div>           
          </div>

          <div class="form-group row">
            <div class="col-xs-3 registerLabel">
              <label for="inputEmailAccept">메일수신동의</label>
            </div>
            <div class="col-xs-9 email-accept checkbox">
            	<label>
              		<input type="checkbox" class="inputEmailAccept">
              		<input type="hidden" name="inputEmailAccept" class="inputEmailAcceptValue">
            	</label>
            </div>           
          </div>
          <div class="register-footer">
	        <input type="button" class="btn btn-primary" value="회원가입" onclick="doRegister()">
	        <button type="button" class="btn btn-default" onclick="fillForm()">자동채우기</button>
	      </div>
        </form>
      </div>     
    </div>
  </div>



<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
	// 우편번호 찾기 화면을 넣을 element
    var element_layer = document.getElementById('findAddressLayer');

    function closeDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function sample2_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('sample2_postcode').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('sample2_address').value = fullAddr;
                //document.getElementById('sample2_addressEnglish').value = data.addressEnglish;

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var width = 300; //우편번호서비스가 들어갈 element의 width
        var height = 460; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 1; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }

</script>

<?php
include_once './tail.php';
?>