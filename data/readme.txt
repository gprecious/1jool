기본세팅 DB는 yh_member, yh_group가 있음 이는 init.sql를 import하면 생김
만약 기본세팅 DB명을 바꾸고싶다면 먼저 mysql에서 바꾼 뒤 터미널에서

find ./ -name \*.php -exec sed -i "s/yh_member/qp_member/g" {} \;
find ./ -name \*.php -exec sed -i "s/yh_group/qp_group/g" {} \;

같은 명령어로 원하는 DB명으로 바꾼다

게시판을 사용하지 않을 경우 관리자페이지에서 관련 페이지를 모두 지우면 된다
게시판을 사용하고싶을 경우 /lib/yhBoard/yhBoard.php에 선언된 prefix를 원하는대로 바꾸고 YB_setup()을 한번 실행시키면 관리자모드에서 정상적으로 작동한다.

