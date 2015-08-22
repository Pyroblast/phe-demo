<?php
session_start();
$str = "phe2015";
$token = md5($str);
$_SESSION['token'] = $token;
if (empty($_SESSION['token_now'])) {
  echo "非法请求。";
  die();
}
if ($_SESSION['token_now'] !== $_SESSION['token']) {
  echo $_SESSION['token_now'];
  die();
}
/*
	热侧进口温度 -> T_1_i
	热侧出口温度 -> T_1_o
	热侧流量     -> W_1
	热侧压降     -> delta_p_1
	冷侧进口温度 -> T_2_i
	冷侧出口温度 -> T_2_o
	冷侧流量	 -> W_2
	冷侧压降	 -> delta_p_2
	热负荷       -> Q
 */
//通过接口流速的大小来进行筛选，提取接口流速在1到5
function filter($W_1,$W_2,$roll1,$roll2)
{
	$A_st = array();
	$A_st[0] = pi()*(32/2)*(32/2)/1000000;
	$A_st[1] = pi()*(50/2)*(50/2)/1000000;
	$A_st[2] = pi()*(65/2)*(65/2)/1000000;
	$A_st[3] = pi()*(80/2)*(80/2)/1000000;
	$A_st[4] = pi()*(100/2)*(100/2)/1000000;
	$A_st[5] = pi()*(150/2)*(150/2)/1000000;
	$A_st[6] = pi()*(200/2)*(200/2)/1000000;
	$A_st[7] = pi()*(250/2)*(250/2)/1000000;
	$A_st[8] = pi()*(300/2)*(300/2)/1000000;
	$A_st[9] = pi()*(350/2)*(350/2)/1000000;
	$A_st[10] = pi()*(400/2)*(400/2)/1000000;
	$A_st[11] = pi()*(450/2)*(450/2)/1000000;
	$A_st[12] = pi()*(500/2)*(500/2)/1000000;
	$A_st[13] = pi()*(600/2)*(600/2)/1000000;

	$j = 0;
	$k = 0;
	if ($W_1 >= $W_2) {
		for ($i=0; $i < 14; $i++) { 
			$R = $W_1 / $roll1 / $A_st[$i];
			
			if ($R >= 1 && $R <= 5) {
				$Arr[$j] = $R;
				$C_D[$j] = sqrt($A_st[$i]*1000000/pi()*4);
				$j++; 
			} else {
				$error[$k] = $R;
				$k++;
			}
		}
	} else {
		for ($i=0; $i < 14; $i++) { 
			$R = $W_2 / $roll2 / $A_st[$i];
			
			if ($R >= 1 && $R <= 5) {
			$Arr[$j] = $R;
			$C_D[$j] = sqrt($A_st[$i]*1000000/pi()*4); 
			$j++;
			} else {
				$error[$k] = $R;
				$k++;
			}
		}
	}

if ($k == 14) {
	return $k;
} else {
	return $C_D;
}

}
//通过C_D法兰口径查询满足要求的版型id
function plateid($C_D)
{
	include("dbc.php");
	for ($i=0; $i < count($C_D); $i++) {
		$sql = "SELECT * FROM `plate` WHERE `C_D`= '" . $C_D[$i] . "'";
		$rs = $db->query($sql);
		$row = $rs->fetchAll();
		$count = count($row);
		if ($count !== 0) {

			for ($j=0; $j < $count; $j++) { 
				//查询plate_thermal_length表中有参数的版型型号，没有则不返回
				$sql2 = "SELECT * FROM `plate_thermal_length` WHERE `ty`= '" . $row[$j]['ty']. "'";
				$rs2 = $db->query($sql2);
				$row2 = $rs2->fetchAll();
				if (!empty($row2)) {
					$plateid[]['id'] = $row[$j]['id'];
				}
			}
		}
	}
	return $plateid;
}
//查询满足要求(流速在1到5之间)的版型数据
function plate($plateid)
{
	include("dbc.php");
	$count = count($plateid);
	for ($i=0; $i < $count; $i++) { 
		$sql = "SELECT * FROM `plate` WHERE `id`= '" . $plateid[$i]['id']. "'";
		$rs = $db->query($sql);
		$row = $rs->fetch();
		$plate_all[$i]['ty'] = $row['ty'];
		$plate_all[$i]['C_A_L'] = $row['C_A_L'];
		$plate_all[$i]['C_A_M'] = $row['C_A_M'];
		$plate_all[$i]['C_A_H'] = $row['C_A_H'];
		$plate_all[$i]['B_p'] = $row['B_p']/1000;
		$plate_all[$i]['De'] = $row['De']/1000;
		$plate_all[$i]['P_D'] = $row['P_D'];
		$plate_all[$i]['A_c'] = $row['A_c']/1000000;
		$plate_all[$i]['A_r'] = $row['A_r'];
		$plate_all[$i]['L_v'] = $row['L_v'];
		$plate_all[$i]['L_h'] = $row['L_h'];
		$plate_all[$i]['L'] = $row['L'];
		$plate_all[$i]['L_w'] = $row['L_w'];
		$plate_all[$i]['t_p'] = $row['t_p']/1000;

		$sql2 = "SELECT * FROM `plate_thermal_length` WHERE `ty`= '" . $row['ty']. "'";
		$rs2 = $db->query($sql2);
		$row2 = $rs2->fetch();
		$plate_all[$i]['TK_C_Nu'] = $row2['C_nu_tk'];
		$plate_all[$i]['TK_m_Nu'] = $row2['m_nu_tk'];
		$plate_all[$i]['TK_C_Eu'] = $row2['C_eu_tk'];
		$plate_all[$i]['TK_m_Eu'] = $row2['m_eu_tk'];
		$plate_all[$i]['TM_C_Nu'] = $row2['C_nu_tm'];
		$plate_all[$i]['TM_m_Nu'] = $row2['m_nu_tm'];
		$plate_all[$i]['TM_C_Eu'] = $row2['C_eu_tm'];
		$plate_all[$i]['TM_m_Eu'] = $row2['m_eu_tm'];
		$plate_all[$i]['TL_C_Nu'] = $row2['C_nu_tl'];
		$plate_all[$i]['TL_m_Nu'] = $row2['m_nu_tl'];
		$plate_all[$i]['TL_C_Eu'] = $row2['C_eu_tl'];
		$plate_all[$i]['TL_m_Eu'] = $row2['m_eu_tl'];

	}
	return $plate_all;
}
?>