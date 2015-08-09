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
$sql1 = "SELECT * FROM `water_properties` WHERE `T`= '" . $T_1_b . "'";
$rs1 = $db->query($sql1);
$row1 = $rs1->fetch();
$roll1 = $row1[2];
$C_p1 = $row1[3];
$k1 = $row1[4];
$miu_d1 = $row1[6];
$Pr1 = $row1[7];
//冷侧
$sql2 = "SELECT * FROM `water_properties` WHERE `T`= '" . $T_2_b . "'";
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
//判断参数是否有效
//input($T_1_i,$T_1_o,$W_1,$delta_p_1,$T_2_i,$T_2_o,$W_2,$delta_p_2,$Q);


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

//print_r($C_D);
//platety(65);

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

//计算TK
if ($W_1 >= $W_2) {
	
} else {
	$TK_V_2_c=exp((1/($plate['TK_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TK_C_Eu']/(exp($plate['TK_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TK_m_Eu']+1)*log($roll2)))));
	$TK_V_1_c=$TK_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TK_Re_2=$TK_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TK_Re_1=$TK_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TK_T_1_w = 0.5*($T_2_b + $T_1_b);
	$i = 0;


	$start=$T_2_b;
	$end = $T_1_b;
	do {
		//查询粘度
		$sql3 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TK_T_1_w) . "'";
		$rs3 = $db->query($sql3);
		$row3 = $rs3->fetch();
		$TK_miu_d_T_1_w = $row3[6];

		$TK_Nu_1=$plate['TK_C_Nu']*exp($plate['TK_m_Nu']*log($TK_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TK_miu_d_T_1_w)));

		
		$TK_h_1=$TK_Nu_1*$k1/$plate['De'];
		$TK_Q_1 = $TK_h_1 * ($T_1_b - $TK_T_1_w);
		$TK_T_2_w = $TK_T_1_w - $TK_Q_1 * $plate['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TK_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TK_miu_d_T_2_w = $row4[6];

		$TK_Nu_2=$plate['TK_C_Nu']*exp($plate['TK_m_Nu']*log($TK_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TK_miu_d_T_2_w)));
		$TK_h_2=$TK_Nu_2*$k2/$plate['De'];
		$TK_Q_2 = $TK_h_2 * ($TK_T_2_w - $T_2_b);


		if ($TK_Q_1 >= $TK_Q_2) {
			$start = $TK_T_1_w;
		} else {
			$end = $TK_T_1_w;
		}
		$TK_T_1_w = 0.5*($start + $end);

		$i++;
	} while (abs(($TK_Q_1-$TK_Q_2)/$TK_Q_1) > 0.001);


	$TK_U=1/((1/$TK_h_2)+(1/$TK_h_1)+($plate['t_p']/$k_p));
	$TK_NTU_1=2*$TK_U*$plate['A_r']/($TK_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TK_sigma_1=($TK_V_1_c*$roll1*$C_p1)/($TK_V_2_c*$roll2*$C_p2);
	$TK_R_1=((1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))))/
	(($TK_sigma_1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))));
	$TK_t_1=$TK_R_1*$Delta_ti;
	$TK_W_1=$TK_V_1_c*$plate['A_c']*$roll1;
}	

//计算TM
if ($W_1 >= $W_2) {
	
} else {
	$TM_V_2_c=exp((1/($plate['TM_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TM_C_Eu']/(exp($plate['TM_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TM_m_Eu']+1)*log($roll2)))));
	$TM_V_1_c=$TM_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TM_Re_2=$TM_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TM_Re_1=$TM_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TM_T_1_w = 0.5*($T_2_b + $T_1_b);
	$i = 0;


	$start=$T_2_b;
	$end = $T_1_b;
	do {
		//查询粘度
		$sql3 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TM_T_1_w) . "'";
		$rs3 = $db->query($sql3);
		$row3 = $rs3->fetch();
		$TM_miu_d_T_1_w = $row3[6];

		$TM_Nu_1=$plate['TM_C_Nu']*exp($plate['TM_m_Nu']*log($TM_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TM_miu_d_T_1_w)));

		
		$TM_h_1=$TM_Nu_1*$k1/$plate['De'];
		$TM_Q_1 = $TM_h_1 * ($T_1_b - $TM_T_1_w);
		$TM_T_2_w = $TM_T_1_w - $TM_Q_1 * $plate['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TM_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TM_miu_d_T_2_w = $row4[6];

		$TM_Nu_2=$plate['TM_C_Nu']*exp($plate['TM_m_Nu']*log($TM_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TM_miu_d_T_2_w)));
		$TM_h_2=$TM_Nu_2*$k2/$plate['De'];
		$TM_Q_2 = $TM_h_2 * ($TM_T_2_w - $T_2_b);


		if ($TM_Q_1 >= $TM_Q_2) {
			$start = $TM_T_1_w;
		} else {
			$end = $TM_T_1_w;
		}
		$TM_T_1_w = 0.5*($start + $end);

		$i++;
	} while (abs(($TM_Q_1-$TM_Q_2)/$TM_Q_1) > 0.001);


	$TM_U=1/((1/$TM_h_2)+(1/$TM_h_1)+($plate['t_p']/$k_p));
	$TM_NTU_1=2*$TM_U*$plate['A_r']/($TM_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TM_sigma_1=($TM_V_1_c*$roll1*$C_p1)/($TM_V_2_c*$roll2*$C_p2);
	$TM_R_1=((1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))))/
	(($TM_sigma_1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))));
	$TM_t_1=$TM_R_1*$Delta_ti;
	$TM_W_1=$TM_V_1_c*$plate['A_c']*$roll1;
}	

//计算TL
if ($W_1 >= $W_2) {
	
} else {
	$TL_V_2_c=exp((1/($plate['TL_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TL_C_Eu']/(exp($plate['TL_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TL_m_Eu']+1)*log($roll2)))));
	$TL_V_1_c=$TL_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TL_Re_2=$TL_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TL_Re_1=$TL_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TL_T_1_w = 0.5*($T_2_b + $T_1_b);
	$i = 0;


	$start=$T_2_b;
	$end = $T_1_b;
	do {
		//查询粘度
		$sql3 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TL_T_1_w) . "'";
		$rs3 = $db->query($sql3);
		$row3 = $rs3->fetch();
		$TL_miu_d_T_1_w = $row3[6];

		$TL_Nu_1=$plate['TL_C_Nu']*exp($plate['TL_m_Nu']*log($TL_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TL_miu_d_T_1_w)));

		
		$TL_h_1=$TL_Nu_1*$k1/$plate['De'];
		$TL_Q_1 = $TL_h_1 * ($T_1_b - $TL_T_1_w);
		$TL_T_2_w = $TL_T_1_w - $TL_Q_1 * $plate['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TL_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TL_miu_d_T_2_w = $row4[6];

		$TL_Nu_2=$plate['TL_C_Nu']*exp($plate['TL_m_Nu']*log($TL_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TL_miu_d_T_2_w)));
		$TL_h_2=$TL_Nu_2*$k2/$plate['De'];
		$TL_Q_2 = $TL_h_2 * ($TL_T_2_w - $T_2_b);


		if ($TL_Q_1 >= $TL_Q_2) {
			$start = $TL_T_1_w;
		} else {
			$end = $TL_T_1_w;
		}
		$TL_T_1_w = 0.5*($start + $end);

		$i++;
	} while (abs(($TL_Q_1-$TL_Q_2)/$TL_Q_1) > 0.001);


	$TL_U=1/((1/$TL_h_2)+(1/$TL_h_1)+($plate['t_p']/$k_p));
	$TL_NTU_1=2*$TL_U*$plate['A_r']/($TL_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TL_sigma_1=($TL_V_1_c*$roll1*$C_p1)/($TL_V_2_c*$roll2*$C_p2);
	$TL_R_1=((1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))))/
	(($TL_sigma_1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))));
	$TL_t_1=$TL_R_1*$Delta_ti;
	$TL_W_1=$TL_V_1_c*$plate['A_c']*$roll1;
}	




//计算整机R
$Total_sigma=($W_1*$C_p1)/($W_2*$C_p2);
$Total_R=((1-exp($NTU*(1-$Total_sigma))*log(exp(1))))/
	(($Total_sigma-exp($NTU*(1-$Total_sigma))*log(exp(1))));

//计算温度
$T_TK_1_b=$T_1_i-$TK_t_1/2;
$T_TM_1_b=$T_1_i-$TM_t_1/2;
$T_TL_1_b=$T_1_i-$TL_t_1/2;
$T_TK_2_b=$T_2_i+$TK_t_1/2*$TK_sigma_1;
$T_TM_2_b=$T_2_i+$TM_t_1/2*$TM_sigma_1;
$T_TL_2_b=$T_2_i+$TL_t_1/2*$TL_sigma_1;

//计算板片数,M_为质量守恒，E_为能量守恒
$M_TK_Ncp_double=$W_1/$TK_W_1;
$M_TM_Ncp_double=$W_1/$TM_W_1;
$M_TL_Ncp_double=$W_1/$TL_W_1;

$M_TK_Ncp=ceil($W_1/$TK_W_1);
$M_TM_Ncp=ceil($W_1/$TM_W_1);
$M_TL_Ncp=ceil($W_1/$TL_W_1);


$E_TK_Ncp_double=$W_1*$Delta_ti*$Total_R/($TK_W_1*$Delta_ti*$TK_R_1);
$E_TM_Ncp_double=$W_1*$Delta_ti*$Total_R/($TM_W_1*$Delta_ti*$TM_R_1);
$E_TL_Ncp_double=$W_1*$Delta_ti*$Total_R/($TL_W_1*$Delta_ti*$TL_R_1);

$E_TK_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TK_W_1*$Delta_ti*$TK_R_1));
$E_TM_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TM_W_1*$Delta_ti*$TM_R_1));
$E_TL_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TL_W_1*$Delta_ti*$TL_R_1));

//混合TK、TM、TL计算板片数
$TM_mixTMTL_Ncp_double = (($E_TL_Ncp_double-$M_TL_Ncp_double)/
	(((-$M_TL_Ncp_double)/$M_TM_Ncp_double)-((-$E_TL_Ncp_double)/$E_TM_Ncp_double)));

$TL_mixTMTL_Ncp_double = $TM_mixTMTL_Ncp_double*((-$M_TL_Ncp_double)/$M_TM_Ncp_double)+$M_TL_Ncp_double;

$TM_mixTMTK_Ncp_double = ($E_TK_Ncp_double-$M_TK_Ncp_double)/
	((-$M_TK_Ncp_double/$M_TM_Ncp_double)-(-$E_TK_Ncp_double/$E_TM_Ncp_double));

$TK_mixTMTK_Ncp_double = $TM_mixTMTK_Ncp_double*(-$M_TK_Ncp_double/$M_TM_Ncp_double)+$M_TK_Ncp_double;

$TM_mixTMTL_Ncp = ceil($TM_mixTMTL_Ncp_double);
$TL_mixTMTL_Ncp = ceil($TL_mixTMTL_Ncp_double);
$TM_mixTMTK_Ncp = ceil($TM_mixTMTK_Ncp_double);
$TK_mixTMTK_Ncp = ceil($TK_mixTMTK_Ncp_double);
//总板片数
$sum_mixTMTL_Ncp = 2 * ($TM_mixTMTL_Ncp+$TL_mixTMTL_Ncp);
$sum_mixTMTK_Ncp = 2 * ($TM_mixTMTK_Ncp+$TK_mixTMTK_Ncp);

$sum_M_TK_Ncp = 2 * $M_TK_Ncp;
$sum_M_TM_Ncp = 2 * $M_TM_Ncp;
$sum_M_TL_Ncp = 2 * $M_TL_Ncp;

$sum_E_TK_Ncp = 2 * $E_TK_Ncp;
$sum_E_TM_Ncp = 2 * $E_TM_Ncp;
$sum_E_TL_Ncp = 2 * $E_TL_Ncp;
//换热面积
$A_total_mixTMTL = $plate['A_r'] * $sum_mixTMTL_Ncp;
$A_total_mixTMTK = $plate['A_r'] * $sum_mixTMTK_Ncp;

$A_total_M_TK = $plate['A_r'] * $sum_M_TK_Ncp;
$A_total_M_TM = $plate['A_r'] * $sum_M_TM_Ncp;
$A_total_M_TL = $plate['A_r'] * $sum_M_TL_Ncp;

$A_total_E_TK = $plate['A_r'] * $sum_E_TK_Ncp;
$A_total_E_TM = $plate['A_r'] * $sum_E_TM_Ncp;
$A_total_E_TL = $plate['A_r'] * $sum_E_TL_Ncp;
//K值
$U_mixTMTL = $Q/$LMTD/$A_total_mixTMTL*1000;
$U_mixTMTK = $Q/$LMTD/$A_total_mixTMTK*1000;

$U_M_TK = $Q/$LMTD/$A_total_M_TK*1000;
$U_M_TM = $Q/$LMTD/$A_total_M_TM*1000;
$U_M_TL = $Q/$LMTD/$A_total_M_TL*1000;

$U_E_TK = $Q/$LMTD/$A_total_E_TK*1000;
$U_E_TM = $Q/$LMTD/$A_total_E_TM*1000;
$U_E_TL = $Q/$LMTD/$A_total_E_TL*1000;

/*
	待补充：
	$miu_b = ;
	$miu_w = ;
	计算：
	$Nu = $C_nu * exp($m_nu * log($Re)) * exp($n * log($Pr)) * exp(0.14 * log($miu/$miu_w));
	$Eu = $C_eu * exp($m_eu * log($Re)) * $N_cp;
 */
//$Nu_tk = ;
//$Eu_tk = ;
/*
	根据许用压降ΔP，分别计算所选型号的各自TK，TM，TL板型热侧和冷侧的流道内流速（板间流速）Vc=Gc/ρ_1，
	Gc是板间质量流速，公式如下：
	ΔP = Eu*Np*ρ_1*Vc^2
	Re = ρ_1*Vc*De/μ_b
 */
//$Vc = ;
//$Re = ;
/*
	计算U，U= h_1 + h_2 + k_p/t_p，h_1=Nu*k_1/De，同理h_2
 */
//$U = ;
/*
	计算NTU
	求解所选型号的各自TK，TM，TL板型的热【是较大流量的一侧】侧单通道NTU值，
	NTU=2*U*Ar/Gc1*(Cp)1，但整机热侧NTU1=ΔT1/ΔTlm，
 */
//$NTU = ;
/*
	求解所选型号的各自TK，TM，TL板型的热【是较大流量的一侧】侧单通道温降，
	ΔT1=ΔTi*R，R的表达式R=[1-eNTU(1-γ)]/[γ-eNTU(1-γ)]
 */
//$R = ;
/*
	计算N_cp
	忽略损失，通过热侧总质量守恒：
	W1=(Ncp)TK*(Gc1)TK+(Ncp)TM*(Gc1)TM
	W1=(Ncp)TM*(Gc1)TM+(Ncp)TL*(Gc1)TL

	忽略损失，Default：热侧定性温度下Cp为常数，通过热侧总能量守恒：
	ΔTi*Rtot1*W1=ΔTi*RTK*(Ncp)TK*(Gc1)TK+ΔTi*RTM*(Ncp)TM*(Gc1)TM
	ΔTi*Rtot1*W1=ΔTi*RTM*(Ncp)TM*(Gc1)TM+ΔTi*RTL*(Ncp)TL*(Gc1)TL
 */
//$Ncp_tk = ;
//$Ncp_tm = ;
//$Ncp_tl = ;
/*
	后续迭代，因为开始时假设定性温度为输入值下的计算温度，
	但实际上定性温度是变化的，所以需要迭代计算，公式如下：
	分别计算TK，TM，TL下的
	ΔT1'=ΔT1-(ΔTi*R)/2
	新的ΔT1'记为新的定性温度从新计算，直到足够精确。
 */

/*
if ((ΔT1'-ΔT1) < 1) {
	print_r($plate);
}
 */

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
	    		<th>m³/s</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>热侧压降</th>
	    		<th><?=$delta_p_1?></th>
	    		<th>lbf/ft2</th>
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
	    		<th>m³/s</th>
	    	</tr>
	    	<tr>	
	    		<th>8</th>
	    		<th>冷侧压降</th>
	    		<th><?=$delta_p_2?></th>
	    		<th>lbf/ft2</th>
	    	</tr>
	    	<tr>	
	    		<th>9</th>
	    		<th>热负荷</th>
	    		<th><?=$Q?></th>
	    		<th></th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>
	<hr >
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
	<hr >
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
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>NTU</th>
	    		<th><?=$NTU?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
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
	    		<th>roll1</th>
	    		<th><?=$roll1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>C_p1</th>
	    		<th><?=$C_p1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>k1</th>
	    		<th><?=$k1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>miu_d1</th>
	    		<th><?=$miu_d1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>Pr1</th>
	    		<th><?=$Pr1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>roll2</th>
	    		<th><?=$roll2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>C_p2</th>
	    		<th><?=$C_p2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>k2</th>
	    		<th><?=$k2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>miu_d2</th>
	    		<th><?=$miu_d2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>Pr2</th>
	    		<th><?=$Pr2?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
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
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>W_2</th>
	    		<th><?=$W_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>k_p</th>
	    		<th><?=$k_p?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>N_p</th>
	    		<th><?=$N_p?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapseTK">
	  		TK参数
	  	</a>
	  </div>
	  <div id="collapseTK" class="panel-collapse collapse" >
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
	    		<th>TK_V_2_c</th>
	    		<th><?=$TK_V_2_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TK_V_1_c</th>
	    		<th><?=$TK_V_1_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TK_Re_2</th>
	    		<th><?=$TK_Re_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TK_Re_1</th>
	    		<th><?=$TK_Re_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TK_Nu_2</th>
	    		<th><?=$TK_Nu_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TK_Nu_1</th>
	    		<th><?=$TK_Nu_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TK_h_2</th>
	    		<th><?=$TK_h_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TK_h_1</th>
	    		<th><?=$TK_h_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TK_U</th>
	    		<th><?=$TK_U?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TK_NTU_1</th>
	    		<th><?=$TK_NTU_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TK_sigma_1</th>
	    		<th><?=$TK_sigma_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TK_R_1</th>
	    		<th><?=$TK_R_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>13</th>
	    		<th>TK_t_1</th>
	    		<th><?=$TK_t_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>14</th>
	    		<th>TK_W_1</th>
	    		<th><?=$TK_W_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>15</th>
	    		<th>TK_T_1_w</th>
	    		<th><?=$TK_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>16</th>
	    		<th>TK_T_2_w</th>
	    		<th><?=$TK_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>17</th>
	    		<th>TK_miu_d_T_1_w</th>
	    		<th><?=$TK_miu_d_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TK_miu_d_T_2_w</th>
	    		<th><?=$TK_miu_d_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >

	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapseTM">
	  		TM参数
	  	</a>
	  </div>
	  <div id="collapseTM" class="panel-collapse collapse" >
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
	    		<th>TM_V_2_c</th>
	    		<th><?=$TM_V_2_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TM_V_1_c</th>
	    		<th><?=$TM_V_1_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TM_Re_2</th>
	    		<th><?=$TM_Re_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TM_Re_1</th>
	    		<th><?=$TM_Re_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TM_Nu_2</th>
	    		<th><?=$TM_Nu_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TM_Nu_1</th>
	    		<th><?=$TM_Nu_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TM_h_2</th>
	    		<th><?=$TM_h_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TM_h_1</th>
	    		<th><?=$TM_h_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TM_U</th>
	    		<th><?=$TM_U?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TM_NTU_1</th>
	    		<th><?=$TM_NTU_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TM_sigma_1</th>
	    		<th><?=$TM_sigma_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TM_R_1</th>
	    		<th><?=$TM_R_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>13</th>
	    		<th>TM_t_1</th>
	    		<th><?=$TM_t_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>14</th>
	    		<th>TM_W_1</th>
	    		<th><?=$TM_W_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>15</th>
	    		<th>TM_T_1_w</th>
	    		<th><?=$TM_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>16</th>
	    		<th>TM_T_2_w</th>
	    		<th><?=$TM_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>17</th>
	    		<th>TM_miu_d_T_1_w</th>
	    		<th><?=$TM_miu_d_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TM_miu_d_T_2_w</th>
	    		<th><?=$TM_miu_d_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >

	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapseTL">
	  		TL参数
	  	</a>
	  </div>
	  <div id="collapseTL" class="panel-collapse collapse" >
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
	    		<th>TL_V_2_c</th>
	    		<th><?=$TL_V_2_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TL_V_1_c</th>
	    		<th><?=$TL_V_1_c?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TL_Re_2</th>
	    		<th><?=$TL_Re_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TL_Re_1</th>
	    		<th><?=$TL_Re_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TL_Nu_2</th>
	    		<th><?=$TL_Nu_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TL_Nu_1</th>
	    		<th><?=$TL_Nu_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TL_h_2</th>
	    		<th><?=$TL_h_2?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TL_h_1</th>
	    		<th><?=$TL_h_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TL_U</th>
	    		<th><?=$TL_U?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TL_NTU_1</th>
	    		<th><?=$TL_NTU_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TL_sigma_1</th>
	    		<th><?=$TL_sigma_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TL_R_1</th>
	    		<th><?=$TL_R_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>13</th>
	    		<th>TL_t_1</th>
	    		<th><?=$TL_t_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>14</th>
	    		<th>TL_W_1</th>
	    		<th><?=$TL_W_1?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>15</th>
	    		<th>TL_T_1_w</th>
	    		<th><?=$TL_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>16</th>
	    		<th>TL_T_2_w</th>
	    		<th><?=$TL_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>17</th>
	    		<th>TL_miu_d_T_1_w</th>
	    		<th><?=$TL_miu_d_T_1_w?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TL_miu_d_T_2_w</th>
	    		<th><?=$TL_miu_d_T_2_w?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapse00">
	  		整机R和实际各板定性温度
	  	</a>
	  </div>
	  <div id="collapse00" class="panel-collapse collapse" >
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
	    		<th>Total_sigma</th>
	    		<th><?=$Total_sigma?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>Total_R</th>
	    		<th><?=$Total_R?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>T_TK_1_b</th>
	    		<th><?=$T_TK_1_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>T_TM_1_b</th>
	    		<th><?=$T_TM_1_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>T_TL_1_b</th>
	    		<th><?=$T_TL_1_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>T_TK_2_b</th>
	    		<th><?=$T_TK_2_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>T_TM_2_b</th>
	    		<th><?=$T_TM_2_b?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>T_TL_2_b</th>
	    		<th><?=$T_TL_2_b?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapse01">
	  		质量守恒下的TK、TM、TL的板片数
	  	</a>
	  </div>
	  <div id="collapse01" class="panel-collapse collapse" >
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
	    		<th>M_TK_Ncp</th>
	    		<th><?=$M_TK_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>M_TM_Ncp</th>
	    		<th><?=$M_TM_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>M_TL_Ncp</th>
	    		<th><?=$M_TL_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapse02">
	  		能量守恒下的TK、TM、TL的板片数
	  	</a>
	  </div>
	  <div id="collapse02" class="panel-collapse collapse" >
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
	    		<th>E_TK_Ncp</th>
	    		<th><?=$E_TK_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>E_TM_Ncp</th>
	    		<th><?=$E_TM_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>E_TL_Ncp</th>
	    		<th><?=$E_TL_Ncp?></th>
	    		<th>°C</th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>
	<hr >	
	<div class="panel panel-default">
	  <div class="panel-heading">
		<a data-toggle="collapse" href="#collapse03">
	  		结果分析
	  	</a>
	  </div>
	  <div id="collapse03" class="panel-collapse collapse" >
	  <table class="table table-hover table-bordered">
	  	<thead>
	  		<tr>
	  			<th width="20%">#</th>
	  			<th width="10%">TMTL</th>
	  			<th width="10%">TMTK</th>
	  			<th width="10%">M_TK</th>
	  			<th width="10%">M_TM</th>
	  			<th width="10%">M_TL</th>
	  			<th width="10%">E_TK</th>
	  			<th width="10%">E_TM</th>
	  			<th width="10%">E_TL</th>
	  		</tr>
	  	</thead>
	    <tbody>
	    	<tr>
	    		<th>总板片数</th>
	    		<th><?=$sum_mixTMTL_Ncp?></th>
	    		<th><?=$sum_mixTMTK_Ncp?></th>
	    		<th><?=$sum_M_TK_Ncp?></th>
	    		<th><?=$sum_M_TM_Ncp?></th>
	    		<th><?=$sum_M_TL_Ncp?></th>
	    		<th><?=$sum_E_TK_Ncp?></th>
	    		<th><?=$sum_E_TM_Ncp?></th>
	    		<th><?=$sum_E_TL_Ncp?></th>
	    	</tr>
	    	<tr>
	    		<th>换热面积</th>
	    		<th><?=$A_total_mixTMTL?></th>
	    		<th><?=$A_total_mixTMTK?></th>
	    		<th><?=$A_total_M_TK?></th>
	    		<th><?=$A_total_M_TM?></th>
	    		<th><?=$A_total_M_TL?></th>
	    		<th><?=$A_total_E_TK?></th>
	    		<th><?=$A_total_E_TM?></th>
	    		<th><?=$A_total_E_TL?></th>
	    	</tr>
	    	<tr>
	    		<th>换热系数</th>
	    		<th><?=$U_mixTMTL?></th>
	    		<th><?=$U_mixTMTK?></th>
	    		<th><?=$U_M_TK?></th>
	    		<th><?=$U_M_TM?></th>
	    		<th><?=$U_M_TL?></th>
	    		<th><?=$U_E_TK?></th>
	    		<th><?=$U_E_TM?></th>
	    		<th><?=$U_E_TL?></th>
	    	</tr>
	    </tbody>
	  </table>
	  </div>
	</div>	
	<hr >
</div>

  </body>
</html>
