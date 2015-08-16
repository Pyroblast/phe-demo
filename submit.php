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
  <link href="data:text/css;charset=utf-8," 
  data-href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" 
  rel="stylesheet" id="bs-theme-stylesheet">

<link href="css/patch.css" rel="stylesheet">

<!-- Documentation extras -->

<link href="css/docs.min.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>

  <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <script src="js/docs.min.js"></script>
<script>
  var _hmt = _hmt || [];
</script>
  </head>
  <body>

<?php
/*
	用户输入：
	热侧进口温度 -> T_1_i
	热侧出口温度 -> T_1_o
	热侧流量     -> W_1
	热侧压降     -> delta_p_1
	冷侧进口温度 -> T_2_i
	冷侧出口温度 -> T_2_o
	冷侧流量	 -> W_2
	冷侧压降	 -> delta_p_2
	热负荷       -> Q

	计算：
	接口面积     -> A_p = pie * (D/2) * (D/2)
 */
//获取用户输入变量
if (empty($_POST['T_1_i'])||empty($_POST['T_1_o'])||empty($_POST['delta_p_1'])
	||empty($_POST['T_2_i'])||empty($_POST['T_2_o'])
	||empty($_POST['delta_p_2'])||empty($_POST['Q'])) {
	echo "请确认参数输入完整。";	
	die();
}

$T_1_i=$_POST['T_1_i'];
$T_1_o=$_POST['T_1_o'];
//$W_1=$_POST['W_1'];
$delta_p_1=$_POST['delta_p_1'];
$T_2_i=$_POST['T_2_i'];
$T_2_o=$_POST['T_2_o'];
//$W_2=$_POST['W_2'];
$delta_p_2=$_POST['delta_p_2'];
$Q=$_POST['Q'];	

//【计算定性温度T_1_b=(T_1_i+T_1_o)/2，T_2_b=(T_2_i+T_2_o)/2，
$T_1_b = ($T_1_i+$T_1_o)*0.5;
$T_2_b = ($T_2_i+$T_2_o)*0.5;
//计算   Δti	      HI-CO	     HO-CI	  HI-HO	    CO-CI	  LMTD	    NTU
//    $Delta_ti       $HICO      $HOCI    $HIHO     $COCI     $LMTD     $NTU
$Delta_ti = $T_1_i - $T_2_i;
$HICO = $T_1_i - $T_2_o;
$HOCI = $T_1_o - $T_2_i;
$HIHO = $T_1_i - $T_1_o;
$COCI = $T_2_o - $T_2_i;
if ($HICO !== $HOCI) {
	if ($HICO > $HOCI) {
		$LMTD = ($HICO - $HOCI)/log($HICO/$HOCI);
	} else {
		$LMTD = ($HOCI - $HICO)/log($HOCI/$HICO);
	}
} else {
	$LMTD = $HICO;
}
if ($HIHO < $COCI) {
	$NTU = $COCI/$LMTD;
} else {
	$NTU = $HIHO/$LMTD;
}

//提取冷热侧定性温度下的各介质物性参数，密度，导热率，比热，粘度
//热侧
$sql1 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($T_1_b) . "'";
$rs1 = $db->query($sql1);
$row1 = $rs1->fetch();
$roll1 = $row1[2];
$C_p1 = $row1[3];
$k1 = $row1[4];
$miu_d1 = $row1[6];
$Pr1 = $row1[7];
//冷侧
$sql2 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($T_2_b) . "'";
$rs2 = $db->query($sql2);
$row2 = $rs2->fetch();
$roll2 = $row2[2];
$C_p2 = $row2[3];
$k2 = $row2[4];
$miu_d2 = $row2[6];
$Pr2 = $row2[7];

//计算流速W_1和W_2
$W_1 = $Q/$C_p1/$HIHO;
$W_2 = $Q/$C_p2/$COCI;

//根据输入的温度和厚度查库找到合适的材质的导热率k_p，
//【GB/151中规定，100°C下的不锈钢导热率为16.3，可以暂定这个值】
$k_p = 16;
/*
选择合适的版型
通过接口流速的大小来进行筛选，提取接口流速在1到5范围内的版型数据
 */
$C_D = filter($W_1,$W_2,$roll1,$roll2);

if ($C_D == 14) {
	echo "无适用版型，请确认输入数据无误";
	die();
}

// print_r($C_D);
$plateid = plateid($C_D);
// echo "<br>";

// print_r($plateid);
// echo "<br>";
$plate = plate($plateid);
$count = count($plate);
// print_r($plate);

$N_p = 1;
$plate = array(
	 'ty' => 'S19A',
	 'B_p' => 0.0023,
	'Enlargement_factor' => 1.17,
	'De' => 0.003931624,
	'A_c' => 0.000618,
	'A_r' => 0.19,
	'C_A_L' => 56, 
	'C_A_M' => 0,
	'C_A_H' => 125,
	'P_D' => 66,
	'L_v' => 701,
	'L_h' => 192,
	'L' => 816,
	'L_w' => 306,
	't_p' => 0.0005,
	'TK_C_Nu' => 0.123954327,
	'TK_m_Nu' => 0.687541997,
	'TK_C_Eu' => 244.631963,
	'TK_m_Eu' => -0.196786967,
	'TM_C_Nu' => 0.195156128,
	'TM_m_Nu' => 0.691030594,
	'TM_C_Eu' => 513.6721481,
	'TM_m_Eu' => -0.20507197,
	'TL_C_Nu' => 0.255478521,
	'TL_m_Nu' => 0.691074019,
	'TL_C_Eu' => 1535.383217,
	'TL_m_Eu' => -0.210683492,
);
//print_r($plate);

?>
<div class="container">
<br >
	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<a data-toggle="collapse" href="#collapse1">
	  	用户输入值
	  	</a>
	  </div>
	  <div id="collapse1" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="5%">#</th>
	  			<th width="40%">名称</th>
	  			<th width="40%">数值</th>
	  			<th width="15%">单位</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>1</th>
	    		<th>热侧进口温度</th>
	    		<th><?=$T_1_i?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>热侧出口温度</th>
	    		<th><?=$T_1_o?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>	
	    		<th>3</th>
	    		<th>热侧流量</th>
	    		<th><?=$W_1?></th>
	    		<th>kg/s</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>热侧压降</th>
	    		<th><?=$delta_p_1?></th>
	    		<th>kPa</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>冷侧进口温度</th>
	    		<th><?=$T_2_i?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>	
	    		<th>6</th>
	    		<th>冷侧出口温度</th>
	    		<th><?=$T_2_o?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>	
	    		<th>7</th>
	    		<th>冷侧流量</th>
	    		<th><?=$W_2?></th>
	    		<th>kg/s</th>
	    	</tr>
	    	<tr>	
	    		<th>8</th>
	    		<th>冷侧压降</th>
	    		<th><?=$delta_p_2?></th>
	    		<th>kPa</th>
	    	</tr>
	    	<tr>	
	    		<th>9</th>
	    		<th>热负荷</th>
	    		<th><?=$Q?></th>
	    		<th>kW</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<a data-toggle="collapse" href="#collapse2">
	  	定性温度
	  	</a>
	  </div>
	  <div id="collapse2" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="5%">#</th>
	  			<th width="40%">名称</th>
	  			<th width="40%">数值</th>
	  			<th width="15%">单位</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>1</th>
	    		<th>定性温度T_1_b</th>
	    		<th><?=$T_1_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>定性温度T_2_b</th>
	    		<th><?=$T_2_b?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<a data-toggle="collapse" href="#collapse3">
	  	Δti|HI-CO|HO-CI|HI-HO|CO-CI|LMTD|NTU
	  	</a>
	  </div>
	  <div id="collapse3" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="5%">#</th>
	  			<th width="40%">名称</th>
	  			<th width="40%">数值</th>
	  			<th width="15%">单位</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>1</th>
	    		<th>Δti</th>
	    		<th><?=$Delta_ti?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>HI-CO</th>
	    		<th><?=$HICO?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>HO-CI</th>
	    		<th><?=$HOCI?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>HI-HO</th>
	    		<th><?=$HIHO?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>CO-CI</th>
	    		<th><?=$COCI?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>LMTD</th>
	    		<th><?=$LMTD?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>NTU</th>
	    		<th><?=$NTU?></th>
	    		<th></th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<a data-toggle="collapse" href="#collapse4">
	  		冷热侧定性温度下的各介质物性参数
	  	</a>
	  </div>
	  <div id="collapse4" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="5%">#</th>
	  			<th width="40%">名称</th>
	  			<th width="40%">数值</th>
	  			<th width="15%">单位</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>1</th>
	    		<th>ρ1</th>
	    		<th><?=$roll1?></th>
	    		<th>kg/m3</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>C_p1</th>
	    		<th><?=$C_p1?></th>
	    		<th>J/(kg*K)</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>k1</th>
	    		<th><?=$k1?></th>
	    		<th>W/(m*K)</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>μ_d1</th>
	    		<th><?=$miu_d1?></th>
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>Pr1</th>
	    		<th><?=$Pr1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>ρ2</th>
	    		<th><?=$roll2?></th>
	    		<th>kg/m3</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>C_p2</th>
	    		<th><?=$C_p2?></th>
	    		<th>J/(kg*K)</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>k2</th>
	    		<th><?=$k2?></th>
	    		<th>W/(m*K)</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>μ_d2</th>
	    		<th><?=$miu_d2?></th>
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>Pr2</th>
	    		<th><?=$Pr2?></th>
	    		<th></th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<a data-toggle="collapse" href="#collapse5">
	  		流速、导热率
	  	</a>
	  </div>
	  <div id="collapse5" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="5%">#</th>
	  			<th width="40%">名称</th>
	  			<th width="40%">数值</th>
	  			<th width="15%">单位</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>1</th>
	    		<th>W_1</th>
	    		<th><?=$W_1?></th>
	    		<th>kg/s</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>W_2</th>
	    		<th><?=$W_2?></th>
	    		<th>kg/s</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>k_p</th>
	    		<th><?=$k_p?></th>
	    		<th>W/(m*K)</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>N_p</th>
	    		<th><?=$N_p?></th>
	    		<th></th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
	<div>
		<h3 class="page-header">满足法兰口径的板型数：<?=$count?></h1>
	</div>
<?php
if (empty($_POST['T_1_i'])||empty($_POST['T_1_o'])||empty($_POST['delta_p_1'])
	||empty($_POST['T_2_i'])||empty($_POST['T_2_o'])
	||empty($_POST['delta_p_2'])||empty($_POST['Q'])) {
	echo "请确认参数输入完整。";	
	die();
}

$T_1_i=$_POST['T_1_i'];
$T_1_o=$_POST['T_1_o'];
//$W_1=$_POST['W_1'];
$delta_p_1=$_POST['delta_p_1'];
$T_2_i=$_POST['T_2_i'];
$T_2_o=$_POST['T_2_o'];
//$W_2=$_POST['W_2'];
$delta_p_2=$_POST['delta_p_2'];
$Q=$_POST['Q'];	

//【计算定性温度T_1_b=(T_1_i+T_1_o)/2，T_2_b=(T_2_i+T_2_o)/2，
$T_1_b = ($T_1_i+$T_1_o)*0.5;
$T_2_b = ($T_2_i+$T_2_o)*0.5;
//计算   Δti	      HI-CO	     HO-CI	  HI-HO	    CO-CI	  LMTD	    NTU
//    $Delta_ti       $HICO      $HOCI    $HIHO     $COCI     $LMTD     $NTU
$Delta_ti = $T_1_i - $T_2_i;
$HICO = $T_1_i - $T_2_o;
$HOCI = $T_1_o - $T_2_i;
$HIHO = $T_1_i - $T_1_o;
$COCI = $T_2_o - $T_2_i;
if ($HICO !== $HOCI) {
	if ($HICO > $HOCI) {
		$LMTD = ($HICO - $HOCI)/log($HICO/$HOCI);
	} else {
		$LMTD = ($HOCI - $HICO)/log($HOCI/$HICO);
	}
} else {
	$LMTD = $HICO;
}
if ($HIHO < $COCI) {
	$NTU = $COCI/$LMTD;
} else {
	$NTU = $HIHO/$LMTD;
}

//提取冷热侧定性温度下的各介质物性参数，密度，导热率，比热，粘度
//热侧
$sql1 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($T_1_b) . "'";
$rs1 = $db->query($sql1);
$row1 = $rs1->fetch();
$roll1 = $row1[2];
$C_p1 = $row1[3];
$k1 = $row1[4];
$miu_d1 = $row1[6];
$Pr1 = $row1[7];
//冷侧
$sql2 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($T_2_b) . "'";
$rs2 = $db->query($sql2);
$row2 = $rs2->fetch();
$roll2 = $row2[2];
$C_p2 = $row2[3];
$k2 = $row2[4];
$miu_d2 = $row2[6];
$Pr2 = $row2[7];

//计算流速W_1和W_2
$W_1 = $Q/$C_p1/$HIHO;
$W_2 = $Q/$C_p2/$COCI;

//根据输入的温度和厚度查库找到合适的材质的导热率k_p，
//【GB/151中规定，100°C下的不锈钢导热率为16.3，可以暂定这个值】
$k_p = 16;
/*
选择合适的版型
通过接口流速的大小来进行筛选，提取接口流速在1到5范围内的版型数据
 */
$C_D = filter($W_1,$W_2,$roll1,$roll2);

if ($C_D == 14) {
	echo "无适用版型，请确认输入数据无误";
	die();
}

$plateid = plateid($C_D);

$plate = plate($plateid);

$count = count($plate);

echo "<div><p class='bg-warning lead'>";	
		
			for ($i=0; $i < $count; $i++) { 
				echo $plate[$i]['ty'] . " , ";
			}

echo "</p></div><hr >";	



$count = count($plate);



	for ($o=0; $o < $count; $o++) { 
		echo "
		<div>
		<p class='text-center bg-info lead'>" . $plate[$o]['ty'] . "</p>
		</div>
		";
		$inc = include("inc/tktmtl2.php");

	}
?>
	</div>
  </body>
</html>
