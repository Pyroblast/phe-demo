<?php
include("inc/dbc.php");
include("inc/function.php");
?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>PHE Tools</title>

<!-- Bootstrap core CSS -->
<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

  <!-- Optional Bootstrap Theme -->
  <link href="data:text/css;charset=utf-8," data-href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">

<link href="css/patch.css" rel="stylesheet">

<!-- Documentation extras -->

<link href="css/docs.min.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
  var _hmt = _hmt || [];
</script>
  </head>
  <body>
    <a id="skippy" class="sr-only sr-only-focusable" href="#content"><div class="container"><span class="skiplink-text">Skip to main content</span></div></a>

    <!-- Docs master nav -->
    <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.html" class="navbar-brand">PHE Tools</a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="index.html" >板式换热器选型工具
          </a>
          <span style="color:#5bc0de;font-size: 8px;top: 2px;position: absolute;right: 2px;">Alpha</span>

        </li>
        <li>
          <a href="#">更多</a>
        </li>
    </nav>
  </div>
</header>


    <!-- Docs page layout -->
    <div class="bs-docs-header" id="content" tabindex="-1">
      <div class="container">
        <h1>板式换热器选型</h1>
        <p>输入温度、压降、热负荷；得到最专业最权威的选型指导。</p>
        <p>现在就开始吧！</p>
      </div>
    </div>

<div class="container bs-docs-container">

  <div class="row">
    <div class="col-md-9" role="main">
      <div class="bs-docs-section">
        <h1 id="allinput" class="page-header">输入变量 <small>请注意单位</small></h1>
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-6">
              <p class="text-center bg-danger lead">热侧</h3>
            </div>
            <div class="col-md-6">
              <p class="text-center bg-info lead">冷侧</h3>
            </div>

            <form class="form-horizontal" id='input' action="submit.php" method="post" role="form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="T_1_i" class="col-sm-4 control-label">热侧进口温度</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：°C" name="T_1_i" id="T_1_i" required autofocus>
                </div>
              </div>
              <div class="form-group">
                <label for="T_1_o" class="col-sm-4 control-label">热侧出口温度</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：°C" name="T_1_o" id="T_1_o" required>
                </div>
              </div>
              <div class="form-group">
                <label for="delta_p_1" class="col-sm-4 control-label">热侧压降</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：kPa" name="delta_p_1" id="delta_p_1" required>
                </div>
              </div>
            </div>
              
            <div class="col-md-6">
              <div class="form-group">
                <label for="T_2_i" class="col-sm-4 control-label">冷侧进口温度</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：°C" name="T_2_i" id="T_2_i" required>
                </div>
              </div>
              <div class="form-group">
                <label for="T_2_o" class="col-sm-4 control-label">冷侧出口温度</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：°C" name="T_2_o" id="T_2_o" required>
                </div>
              </div>
              <div class="form-group">
                <label for="delta_p_2" class="col-sm-4 control-label">冷侧压降</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="单位：kPa" name="delta_p_2" id="delta_p_2" required>
                </div>
              </div>
            </div>
             
          
              
              
              
              
              
            <div class="col-md-12">
              <div class="form-group">
                <label for="Q" class="col-sm-2 control-label">热负荷</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="单位：kW" name="Q" id="Q" required>
                </div>
              </div>
              
              <button class="btn btn-block btn-primary" type="submit">提交</button><br />
              
            </div>
            
            </form>
          </div>
        </div>
      </div>
      <br />
      <br />
      <div class="bs-docs-section">
        <h1 id="temperature" class="page-header">温度 <small>单位：°C</small></h1>
        <p class="lead">温度（temperature）是表示物体冷热程度的物理量，微观上来讲是物体分子热运动的剧烈程度。温度只能通过物体随温度变化的某些特性来间接测量，而用来量度物体温度数值的标尺叫温标。它规定了温度的读数起点（零点）和测量温度的基本单位。国际单位为热力学温标(K)。目前国际上用得较多的其他温标有华氏温标(°F)、摄氏温标(°C)和国际实用温标。从分子运动论观点看，温度是物体分子运动平均动能的标志。温度是大量分子热运动的集体表现，含有统计意义。对于个别分子来说，温度是没有意义的。根据某个可观察现象(如水银柱的膨胀)，按照几种任意标度之一所测得的冷热程度。</p>
        <div class="row">
        </div>
      </div>
      <br />
      <br />
<!--      
      <div class="bs-docs-section">
        <h1 id="flow" class="page-header">流量 <small>单位：m³/s</small></h1>
        <p class="lead">所谓流量，是指单位时间内流经封闭管道或明渠有效截面的流体量，又称瞬时流量。当流体量以体积表示时称为体积流量；当流体量以质量表示时称为质量流量。单位时间内流过某一段管道的流体的体积，称为该横截面的体积流量。简称为流量，用Q来表示。</p>
        <div class="row">
        </div>
      </div>
      <br />
      <br />
-->
      <div class="bs-docs-section">
        <h1 id="pressure" class="page-header">压降 <small>单位：kPa</small></h1>
        <p class="lead">流体在管中流动时由于能量损失而引起的压力降低。这种能量损失是由流体流动时克服内摩擦力和克服湍流时流体质点间相互碰撞并交换动量而引起的，表现在流体流动的前后处产生压力差，即压降。
        压降的大小随着管内流速变化而变化。
        在空调系统运行时管内光滑程度，连接方式是否会缩孔截流也会影响压降。</p>
        <div class="row">
        </div>
      </div>
      <br />
      <br />

      <div class="bs-docs-section">
        <h1 id="heat" class="page-header">热负荷 <small>单位：kW</small></h1>
        <p class="lead">燃料在燃烧器中（如燃气具、燃气热水器、燃气取暖炉、火箭发动机燃烧室）燃烧时单位时间内所释放的热量。其计算式为：热负荷=燃料消耗量*燃料低热值。热负荷的大小是由主燃烧器燃料消耗量的大小等因素决定的。</p>
        <div class="row">
        </div>
      </div>
      <br />
      <br />
      <br />
      <br />
        </div>
        <br />
<div class="col-md-3" role="complementary">
  <nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm">
    <ul class="nav bs-docs-sidenav">
      
        <li>
          <a href="#allinput">输入变量</a>
        </li>
        <li>
          <a href="#temperature">温度</a>
        </li>
<!--
        <li>
          <a href="#flow">流量</a>
        </li>
-->
        <li>
          <a href="#pressure">压降</a>
        </li>
        <li>
          <a href="#heat">热负荷</a>
        </li>
        
    </ul>
    <a class="back-to-top" href="#top">
      返回顶部
    </a> 
          </nav>
</div>
        
      </div>
    </div>
<footer class="bs-docs-footer" role="contentinfo">
  <div class="container">

    
    <p>本站由 <a href="http://www.aliyun.com/" target="_blank">阿里云</a> 提供云计算服务，</p>
    <p>当前基于 <a href="http://www.bootcss.com/" target="_blank">Bootstrap</a> 框架技术构建。</p>
    <p>我们推荐使用 Chrome 进行浏览。</p>
    <p>©2011-2015 Phe Team</p>
    <ul class="bs-docs-footer-links text-muted">
      <li>当前版本： v1.0.0</li>
      <li>&middot;</li>
      <li><a href="#">GitHub 仓库</a></li>
      <li>&middot;</li>
      <li><a href="#">v1.0.0 中文文档</a></li>
      <li>&middot;</li>
      <li><a href="#">关于</a></li>
      <li>&middot;</li>
      <li><a href="http://blog.getbootstrap.com">官方博客</a></li>
      <li>&middot;</li>
      <li><a href="#">历史版本</a></li>
    </ul>
  </div>
</footer>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>

  <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <script src="js/docs.min.js"></script>

  </body>
</html>
