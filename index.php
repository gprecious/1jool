<?php
include_once './_common.php';

$_CSS[] = $yh['path'].'/css/index.css';
$_CSS[] = $yh['path'].'/css/footer.css';

$_JS[] = $yh['path'].'/js/index.js';
$showBottomNav = 1;
include_once './head.php';
//print_r($yh);
//echo "<br />";
//print_r($member);
//echo "<br />";
/*
if ($is_member) echo "<a href='".$yh['path']."/logout.php'>Logout</a>";
else echo "<a href='".$yh['path']."/login.php'>Login</a>";
*/

?>




<div id="myCarousel" class="carousel slide">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="item active">
      <img src="<?=$yh['path']?>/img/main_introduce.jpeg" style="width:100%" class="img-responsive main-bg">
      <div class="container">
        <div class="carousel-caption">
          <h1>Bootstrap 3 Carousel Layout</h1>
          <p></p>
          <p><a class="btn btn-lg btn-primary" href="http://getbootstrap.com">Learn More</a>
        </p>
        </div>
      </div>
    </div>
    <div class="item">
      <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive main-bg">
      <div class="container">
        <div class="carousel-caption">
          <h1>Changes to the Grid</h1>
          <p>Bootstrap 3 still features a 12-column grid, but many of the CSS class names have completely changed.</p>
          <p><a class="btn btn-large btn-primary" href="#">Learn more</a></p>
        </div>
      </div>
    </div>
    <div class="item">
      <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive main-bg">
      <div class="container">
        <div class="carousel-caption">
          <h1>Percentage-based sizing</h1>
          <p>With "mobile-first" there is now only one percentage-based grid.</p>
          <p><a class="btn btn-large btn-primary" href="#">Browse gallery</a></p>
        </div>
      </div>
    </div>
  </div>
  <!-- Controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="icon-next"></span>
  </a>  
</div>
<!-- /.carousel -->

<div class="container">
  <div class="contents-title">
    <a class="hot-prescription btn btn-default">인기 처방전</a>
  </div>

  <div class="row contents">
    

    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          <span class="glyphicon glyphicons-calendar"></span>2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>


    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>


    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>



    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>



    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>



    <div class="col-xs-6 col-md-3 contents-outer">
      <div class="contents-wrap">
        <div class="contents-img">
          <img src="<?=$yh['path']?>/img/main_introduce.jpeg" class="img-responsive img-rounded">
        </div>
        <div class="contents-markup">
          <span class="label label-default">Best</span>
        </div>
        <div class="contents-name">
          [파인아트] 파인아트란 이런 것
        </div>
        <div class="contents-desc">
          이러저러한 설명 간단 설명
        </div>
        <div class="contents-date">
          2015-09-07
        </div>
        <div class="contents-price">
          39,900
        </div>
      </div>
    </div>



   
  </div>

</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>


<?php
include_once './tail.php';
?>