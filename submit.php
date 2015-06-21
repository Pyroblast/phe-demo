<?php
include("inc/dbc.php");
include("inc/function.php");
?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
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
$T_1_i=$_POST['T_1_i'];
$T_1_o=$_POST['T_1_o'];
$W_1=$_POST['W_1'];
$delta_p_1=$_POST['delta_p_1'];
$T_2_i=$_POST['T_2_i'];
$T_2_o=$_POST['T_2_o'];
$W_2=$_POST['W_2'];
$delta_p_2=$_POST['delta_p_2'];
$Q=$_POST['Q'];
//【计算定性温度T_1_b=(T_1_i+T_1_o)/2，T_2_b=(T_2_i+T_2_o)/2，
$T_1_b = ($T_1_i+$T_1_o)*0.5;
$T_2_b = ($T_2_i+$T_2_o)*0.5;
echo "定性温度T1b $T_1_b" . "<br >";
echo "定性温度T2b $T_2_b" . "<br >";
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
//echo "ti=$Delta_ti"."<br >"."HI-CO=$HICO"."<br >"."HO-CI=$HOCI"."<br >"."HI-HO=$HIHO"."<br >".
//	"CO-CI=$COCI"."<br >"."LMTD=$LMTD"."<br >"."NTU=$NTU"."<br >";

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
input($T_1_i,$T_1_o,$W_1,$delta_p_1,$T_2_i,$T_2_o,$W_2,$delta_p_2,$Q);

//echo "$W_1" . "<br >" . "$W_2" . "<br >";
//根据输入的温度和厚度查库找到合适的材质的导热率k_p，
//【GB/151中规定，100°C下的不锈钢导热率为16.3，可以暂定这个值】
$k_p = 16.3;
/*
Default：流程Np=1【目前我们只计算单流程】，通过流量计算管口流速Gp
【这里加入一个约束，初步设定Gp属于1-5m/s区间内的值为合适口径，返回对应的口径，
取值，同时搜索板型数据库中符合该口径下型号，提取该型号数据（数组）】，选择合适口径，
提取全部在合适口径下的各板型数据：传热准则式Nu，压降准则式Eu，
【同时用迭代法计算壁温，假设T_1_w为某一值，U_1*(T_1_b-T_1_w)=Q_1，
T_2_w=T_1_w-Q_1/(k/t_p),Q_2=U_2*(T_2_w-T_2_b)，直到找出使得Q_1=Q_2的假设温度，行72-74放到这块】
【每个版型均有自己的C，m值，Pr的指数规则为热侧0.33，冷侧0.4，Nu里面的粘度项指数取值为0.14】
Nu_1=C_Nu*(Re^m_Nu)*(Pr^0.33)*(miu_b_1/miu_w_1)^0.14，
Nu_2=C_Nu*(Re^m_Nu)*(Pr^0.33)*(miu_b_2/miu_w_2)^0.14
Eu_1=C_Eu*Re^m_Eu【m_Eu值为负】
Eu_2=C_Eu*Re^m_Eu【m_Eu值为负】
单板有效面积Ar，流道截面积Ac，波纹深度bp，板片厚度tp
$G_p_1 = ;
$G_p_2 = ;【冷热侧计算后取较大值，看这个值是否在1-5m/s里面，见43行】

【初版为了优先设计好框架和核心算法，暂定板型数据，取S19A的数据，后续再补充】
【改成SI单位统一计算】
 */
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
print_r($plate);
//计算T_1_w、T_2_w，算法待补充。
$T_1_w = round(62.24);
$T_2_w = round(59.76079105);
//根据T_1_w和T_2_w的值查询粘度。
//T_1_w
$sql3 = "SELECT * FROM `water_properties` WHERE `T`= '" . $T_1_w . "'";
$rs3 = $db->query($sql3);
$row3 = $rs3->fetch();
$miu_d_T_1_w = $row3[6];
//T_2_w
$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . $T_2_w . "'";
$rs4 = $db->query($sql4);
$row4 = $rs4->fetch();
$miu_d_T_2_w = $row4[6];
//计算TK
if ($W_1 >= $W_2) {
	
} else {
	$TK_V_2_c=exp((1/($plate['TK_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TK_C_Eu']/(exp($plate['TK_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TK_m_Eu']+1)*log($roll2)))));
	$TK_V_1_c=$TK_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TK_Re_2=$TK_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TK_Re_1=$TK_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TK_Nu_2=$plate['TK_C_Nu']*exp($plate['TK_m_Nu']*log($TK_Re_2))
	*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$miu_d_T_2_w)));
	$TK_Nu_1=$plate['TK_C_Nu']*exp($plate['TK_m_Nu']*log($TK_Re_1))
	*exp(0.4*log($Pr1))*exp(0.14*log(($miu_d1/$miu_d_T_1_w)));
	$TK_h_2=$TK_Nu_2*$k2/$plate['De'];
	$TK_h_1=$TK_Nu_1*$k1/$plate['De'];
	$TK_U=1/((1/$TK_h_2)+(1/$TK_h_1)+($plate['t_p']/$k_p));
	$TK_NTU_1=2*$TK_U*$plate['A_r']/($TK_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TK_sigma_1=($TK_V_1_c*$roll1*$C_p1)/($TK_V_2_c*$roll2*$C_p2);
	$TK_R_1=((1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))))/
	(($TK_sigma_1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))));
	$TK_t_1=$TK_R_1*$Delta_ti;
	$TK_W_1=$TK_V_1_c*$plate['A_c']*$roll1;
}	

echo "TK参数：<br >".
"$roll2<br />".
"$TK_V_2_c<br />".
"$TK_V_1_c<br >".
"$TK_Re_2<br >".
"$TK_Re_1<br >".
"$TK_Nu_2<br >".
"$TK_Nu_1<br >";
ec($TK_h_2);
ec($TK_h_1);
ec($TK_U);
ec($TK_NTU_1);
ec($TK_sigma_1);
ec($TK_R_1);
ec($TK_t_1);
ec($TK_W_1);

//计算TM
if ($W_1 >= $W_2) {
	
} else {
	$TM_V_2_c=exp((1/($plate['TM_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TM_C_Eu']/(exp($plate['TM_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TM_m_Eu']+1)*log($roll2)))));
	$TM_V_1_c=$TM_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TM_Re_2=$TM_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TM_Re_1=$TM_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TM_Nu_2=$plate['TM_C_Nu']*exp($plate['TM_m_Nu']*log($TM_Re_2))
	*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$miu_d_T_2_w)));
	$TM_Nu_1=$plate['TM_C_Nu']*exp($plate['TM_m_Nu']*log($TM_Re_1))
	*exp(0.4*log($Pr1))*exp(0.14*log(($miu_d1/$miu_d_T_1_w)));
	$TM_h_2=$TM_Nu_2*$k2/$plate['De'];
	$TM_h_1=$TM_Nu_1*$k1/$plate['De'];
	$TM_U=1/((1/$TM_h_2)+(1/$TM_h_1)+($plate['t_p']/$k_p));
	$TM_NTU_1=2*$TM_U*$plate['A_r']/($TM_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TM_sigma_1=($TM_V_1_c*$roll1*$C_p1)/($TM_V_2_c*$roll2*$C_p2);
	$TM_R_1=((1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))))/
	(($TM_sigma_1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))));
	$TM_t_1=$TM_R_1*$Delta_ti;
	$TM_W_1=$TM_V_1_c*$plate['A_c']*$roll1;
}	

echo "TM参数：<br >".
"$roll2<br />".
"$TM_V_2_c<br />".
"$TM_V_1_c<br >".
"$TM_Re_2<br >".
"$TM_Re_1<br >".
"$TM_Nu_2<br >".
"$TM_Nu_1<br >";
ec($TM_h_2);
ec($TM_h_1);
ec($TM_U);
ec($TM_NTU_1);
ec($TM_sigma_1);
ec($TM_R_1);
ec($TM_t_1);
ec($TM_W_1);

//计算TL
if ($W_1 >= $W_2) {
	
} else {
	$TL_V_2_c=exp((1/($plate['TL_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate['TL_C_Eu']/(exp($plate['TL_m_Eu']*
			log($plate['De']/$miu_d2)))/(exp(($plate['TL_m_Eu']+1)*log($roll2)))));
	$TL_V_1_c=$TL_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TL_Re_2=$TL_V_2_c*$roll2*$plate['De']/$miu_d2;
	$TL_Re_1=$TL_V_1_c*$roll1*$plate['De']/$miu_d1;
	$TL_Nu_2=$plate['TL_C_Nu']*exp($plate['TL_m_Nu']*log($TL_Re_2))
	*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$miu_d_T_2_w)));
	$TL_Nu_1=$plate['TL_C_Nu']*exp($plate['TL_m_Nu']*log($TL_Re_1))
	*exp(0.4*log($Pr1))*exp(0.14*log(($miu_d1/$miu_d_T_1_w)));
	$TL_h_2=$TL_Nu_2*$k2/$plate['De'];
	$TL_h_1=$TL_Nu_1*$k1/$plate['De'];
	$TL_U=1/((1/$TL_h_2)+(1/$TL_h_1)+($plate['t_p']/$k_p));
	$TL_NTU_1=2*$TL_U*$plate['A_r']/($TL_V_1_c*$roll1*$plate['A_c']*$C_p1*1000);
	$TL_sigma_1=($TL_V_1_c*$roll1*$C_p1)/($TL_V_2_c*$roll2*$C_p2);
	$TL_R_1=((1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))))/
	(($TL_sigma_1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))));
	$TL_t_1=$TL_R_1*$Delta_ti;
	$TL_W_1=$TL_V_1_c*$plate['A_c']*$roll1;
}	

echo "TL参数：<br >".
"$roll2<br />".
"$TL_V_2_c<br />".
"$TL_V_1_c<br >".
"$TL_Re_2<br >".
"$TL_Re_1<br >".
"$TL_Nu_2<br >".
"$TL_Nu_1<br >";
ec($TL_h_2);
ec($TL_h_1);
ec($TL_U);
ec($TL_NTU_1);
ec($TL_sigma_1);
ec($TL_R_1);
ec($TL_t_1);
ec($TL_W_1);

//计算整机R
$Total_sigma=($W_1*$C_p1)/($W_2*$C_p2);
$Total_R=((1-exp($NTU*(1-$Total_sigma))*log(exp(1))))/
	(($Total_sigma-exp($NTU*(1-$Total_sigma))*log(exp(1))));
echo "整机R：<br >";
ec($Total_R);
//计算温度
$T_TK_1_b=$T_1_i-$TK_t_1/2;
$T_TM_1_b=$T_1_i-$TM_t_1/2;
$T_TL_1_b=$T_1_i-$TL_t_1/2;
$T_TK_2_b=$T_2_i+$TK_t_1/2*$TK_sigma_1;
$T_TM_2_b=$T_2_i+$TM_t_1/2*$TM_sigma_1;
$T_TL_2_b=$T_2_i+$TL_t_1/2*$TL_sigma_1;
echo "实际各板定性温度：<br >";
ec($T_TK_1_b);
ec($T_TM_1_b);
ec($T_TL_1_b);
ec($T_TK_2_b);
ec($T_TM_2_b);
ec($T_TL_2_b);

//计算板片数,M_为质量守恒，E_为能量守恒
$M_TK_Ncp=ceil($W_1/$TK_W_1);
$M_TM_Ncp=ceil($W_1/$TM_W_1);
$M_TL_Ncp=ceil($W_1/$TL_W_1);
echo "质量守恒下的TK、TM、TL的板片数:<br >";
ec($M_TK_Ncp);
ec($M_TM_Ncp);
ec($M_TL_Ncp);
$E_TK_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TK_W_1*$Delta_ti*$TK_R_1));
$E_TM_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TM_W_1*$Delta_ti*$TM_R_1));
$E_TL_Ncp=ceil($W_1*$Delta_ti*$Total_R/($TL_W_1*$Delta_ti*$TL_R_1));
echo "能量守恒下的TK、TM、TL的板片数:<br >";
ec($E_TK_Ncp);
ec($E_TM_Ncp);
ec($E_TL_Ncp);

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
  </body>
</html>
