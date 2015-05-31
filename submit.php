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
//判断参数是否有效
input($T_1_i,$T_1_o,$W_1,$delta_p_1,$T_2_i,$T_2_o,$W_2,$delta_p_2,$Q);
//根据输入的温度和厚度查库找到合适的材质的导热率K
$k = 15.17;
/*
Default：流程Np=1，通过流量计算管口流速Gp，选择合适口径，
提取全部在合适口径下的各板型数据：传热准则式Nu，压降准则式Eu，

单板有效面积Ar，流道截面积Ac，波纹深度bp，板片厚度tp
$G_p_1 = ;
$G_p_2 = ;

暂定取S19A的数据，后续再补充

 */
$N_p = 1;
$plate = array(
	'ty' => 'S19A',
	'C_A_L' => 56, 
	'C_A_M' => 0,
	'C_A_H' => 125,
	'B_p' => 2.3,
	'De' => 4.6,
	'P_D' => 66,
	'A_c' => 618,
	'A_r' => 0.19,
	'L_v' => 701,
	'L_h' => 192,
	'L' => 816,
	'L_w' => 306,
	't_p' => 0.5,
);

/*
	待补充：
	$miu = ;
	$miu_w = ;
	计算：
	$Nu = $C_nu * exp($m_nu * log($Re)) * exp($n * log($Pr)) * exp(0.14 * log($miu/$miu_w));
	$Eu = $C_eu * exp($m_eu * log($Re)) * $N_cp;
 */
$Nu_tk = ;
$Eu_tk = ;
/*
	根据许用压降ΔP，分别计算所选型号的各自TK，TM，TL板型热侧和冷侧的流道内流速（板间流速）Vc=Gc/ρ，
	Gc是板间质量流速，公式如下：
	ΔP=Eu*Np*ρ*Vc2
	Re = ρ*Vc*De/μ
 */
$Vc = ;
$Re = ;
/*
	计算U
 */
$U = ;
/*
	计算NTU
	求解所选型号的各自TK，TM，TL板型的热侧单通道NTU值，NTU=2*U*Ar/Gc1*(Cp)1，但整机热侧NTU1=ΔT1/ΔTlm，
 */
$NTU = ;
/*
	求解所选型号的各自TK，TM，TL板型的热侧单通道温降，ΔT1=ΔTi*R，R的表达式R=[1-eNTU(1-γ)]/[γ-eNTU(1-γ)]
 */
$R = ;
/*
	计算N_cp
	忽略损失，通过热侧总质量守恒：
	W1=(Ncp)TK*(Gc1)TK+(Ncp)TM*(Gc1)TM
	W1=(Ncp)TM*(Gc1)TM+(Ncp)TL*(Gc1)TL

	忽略损失，Default：热侧定性温度下Cp为常数，通过热侧总能量守恒：
	ΔTi*Rtot1*W1=ΔTi*RTK*(Ncp)TK*(Gc1)TK+ΔTi*RTM*(Ncp)TM*(Gc1)TM
	ΔTi*Rtot1*W1=ΔTi*RTM*(Ncp)TM*(Gc1)TM+ΔTi*RTL*(Ncp)TL*(Gc1)TL
 */
$Ncp_tk = ;
$Ncp_tm = ;
$Ncp_tl = ;
/*
	后续迭代，因为开始时假设定性温度为输入值下的计算温度，但实际上定性温度是变化的，所以需要迭代计算，公式如下：
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
