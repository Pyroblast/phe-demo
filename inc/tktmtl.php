<?php
//计算TK
if ($W_1 >= $W_2) {
	echo "
		<div>
		<p class='text-center bg-info lead'>暂时只能计算W_1小于W_2的情况，更多功能敬请期待。</p>
		</div>
		";
	die();
} else {
	$TK_V_2_c=exp((1/($plate[$o]['TK_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate[$o]['TK_C_Eu']/(exp($plate[$o]['TK_m_Eu']*
			log($plate[$o]['De']/$miu_d2)))/(exp(($plate[$o]['TK_m_Eu']+1)*log($roll2)))));
	$TK_V_1_c=$TK_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TK_Re_2=$TK_V_2_c*$roll2*$plate[$o]['De']/$miu_d2;
	$TK_Re_1=$TK_V_1_c*$roll1*$plate[$o]['De']/$miu_d1;
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

		$TK_Nu_1=$plate[$o]['TK_C_Nu']*exp($plate[$o]['TK_m_Nu']*log($TK_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TK_miu_d_T_1_w)));

		
		$TK_h_1=$TK_Nu_1*$k1/$plate[$o]['De'];
		$TK_Q_1 = $TK_h_1 * ($T_1_b - $TK_T_1_w);
		$TK_T_2_w = $TK_T_1_w - $TK_Q_1 * $plate[$o]['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TK_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TK_miu_d_T_2_w = $row4[6];

		$TK_Nu_2=$plate[$o]['TK_C_Nu']*exp($plate[$o]['TK_m_Nu']*log($TK_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TK_miu_d_T_2_w)));
		$TK_h_2=$TK_Nu_2*$k2/$plate[$o]['De'];
		$TK_Q_2 = $TK_h_2 * ($TK_T_2_w - $T_2_b);


		if ($TK_Q_1 >= $TK_Q_2) {
			$start = $TK_T_1_w;
		} else {
			$end = $TK_T_1_w;
		}
		$TK_T_1_w = 0.5*($start + $end);

		$i++;
	} while (abs(($TK_Q_1-$TK_Q_2)/$TK_Q_1) > 0.001 && $i < 50);


	$TK_U=1/((1/$TK_h_2)+(1/$TK_h_1)+($plate[$o]['t_p']/$k_p));
	$TK_NTU_1=2*$TK_U*$plate[$o]['A_r']/($TK_V_1_c*$roll1*$plate[$o]['A_c']*$C_p1*1000);
	$TK_sigma_1=($TK_V_1_c*$roll1*$C_p1)/($TK_V_2_c*$roll2*$C_p2);
	$TK_R_1=((1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))))/
	(($TK_sigma_1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))));
	$TK_t_1=$TK_R_1*$Delta_ti;
	$TK_W_1=$TK_V_1_c*$plate[$o]['A_c']*$roll1;
}	

//计算TM
if ($W_1 >= $W_2) {
	
} else {
	$TM_V_2_c=exp((1/($plate[$o]['TM_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate[$o]['TM_C_Eu']/(exp($plate[$o]['TM_m_Eu']*
			log($plate[$o]['De']/$miu_d2)))/(exp(($plate[$o]['TM_m_Eu']+1)*log($roll2)))));
	$TM_V_1_c=$TM_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TM_Re_2=$TM_V_2_c*$roll2*$plate[$o]['De']/$miu_d2;
	$TM_Re_1=$TM_V_1_c*$roll1*$plate[$o]['De']/$miu_d1;
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

		$TM_Nu_1=$plate[$o]['TM_C_Nu']*exp($plate[$o]['TM_m_Nu']*log($TM_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TM_miu_d_T_1_w)));

		
		$TM_h_1=$TM_Nu_1*$k1/$plate[$o]['De'];
		$TM_Q_1 = $TM_h_1 * ($T_1_b - $TM_T_1_w);
		$TM_T_2_w = $TM_T_1_w - $TM_Q_1 * $plate[$o]['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TM_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TM_miu_d_T_2_w = $row4[6];

		$TM_Nu_2=$plate[$o]['TM_C_Nu']*exp($plate[$o]['TM_m_Nu']*log($TM_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TM_miu_d_T_2_w)));
		$TM_h_2=$TM_Nu_2*$k2/$plate[$o]['De'];
		$TM_Q_2 = $TM_h_2 * ($TM_T_2_w - $T_2_b);


		if ($TM_Q_1 >= $TM_Q_2) {
			$start = $TM_T_1_w;
		} else {
			$end = $TM_T_1_w;
		}
		$TM_T_1_w = 0.5*($start + $end);

		$i++;
	} while (abs(($TM_Q_1-$TM_Q_2)/$TM_Q_1) > 0.001 && $i < 50);


	$TM_U=1/((1/$TM_h_2)+(1/$TM_h_1)+($plate[$o]['t_p']/$k_p));
	$TM_NTU_1=2*$TM_U*$plate[$o]['A_r']/($TM_V_1_c*$roll1*$plate[$o]['A_c']*$C_p1*1000);
	$TM_sigma_1=($TM_V_1_c*$roll1*$C_p1)/($TM_V_2_c*$roll2*$C_p2);
	$TM_R_1=((1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))))/
	(($TM_sigma_1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))));
	$TM_t_1=$TM_R_1*$Delta_ti;
	$TM_W_1=$TM_V_1_c*$plate[$o]['A_c']*$roll1;
}	


//计算TL
if ($W_1 >= $W_2) {
	
} else {


	$TL_V_2_c=exp((1/($plate[$o]['TL_m_Eu']+2)) * 
		log($delta_p_2*1000/$plate[$o]['TL_C_Eu']/(exp($plate[$o]['TL_m_Eu']*
			log($plate[$o]['De']/$miu_d2)))/(exp(($plate[$o]['TL_m_Eu']+1)*log($roll2)))));
	$TL_V_1_c=$TL_V_2_c * $roll2 * $W_1/$W_2/$roll1;
	$TL_Re_2=$TL_V_2_c*$roll2*$plate[$o]['De']/$miu_d2;
	$TL_Re_1=$TL_V_1_c*$roll1*$plate[$o]['De']/$miu_d1;
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

		$TL_Nu_1=$plate[$o]['TL_C_Nu']*exp($plate[$o]['TL_m_Nu']*log($TL_Re_1))
			*exp(0.33*log($Pr1))*exp(0.14*log(($miu_d1/$TL_miu_d_T_1_w)));

		
		$TL_h_1=$TL_Nu_1*$k1/$plate[$o]['De'];
		$TL_Q_1 = $TL_h_1 * ($T_1_b - $TL_T_1_w);
		$TL_T_2_w = $TL_T_1_w - $TL_Q_1 * $plate[$o]['t_p']/$k_p;

		$sql4 = "SELECT * FROM `water_properties` WHERE `T`= '" . round($TL_T_2_w) . "'";
		$rs4 = $db->query($sql4);
		$row4 = $rs4->fetch();
		$TL_miu_d_T_2_w = $row4[6];

		$TL_Nu_2=$plate[$o]['TL_C_Nu']*exp($plate[$o]['TL_m_Nu']*log($TL_Re_2))
			*exp(0.4*log($Pr2))*exp(0.14*log(($miu_d2/$TL_miu_d_T_2_w)));
		$TL_h_2=$TL_Nu_2*$k2/$plate[$o]['De'];
		$TL_Q_2 = $TL_h_2 * ($TL_T_2_w - $T_2_b);


		if ($TL_Q_1 >= $TL_Q_2) {
			$start = $TL_T_1_w;
		} else {
			$end = $TL_T_1_w;
		}
		$TL_T_1_w = 0.5*($start + $end);

		$i++;

	} while (abs(($TL_Q_1-$TL_Q_2)/$TL_Q_1) > 0.001 && $i < 50);


	$TL_U=1/((1/$TL_h_2)+(1/$TL_h_1)+($plate[$o]['t_p']/$k_p));
	$TL_NTU_1=2*$TL_U*$plate[$o]['A_r']/($TL_V_1_c*$roll1*$plate[$o]['A_c']*$C_p1*1000);
	$TL_sigma_1=($TL_V_1_c*$roll1*$C_p1)/($TL_V_2_c*$roll2*$C_p2);
	$TL_R_1=((1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))))/
	(($TL_sigma_1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))));
	$TL_t_1=$TL_R_1*$Delta_ti;
	$TL_W_1=$TL_V_1_c*$plate[$o]['A_c']*$roll1;
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
$A_total_mixTMTL = $plate[$o]['A_r'] * $sum_mixTMTL_Ncp;
$A_total_mixTMTK = $plate[$o]['A_r'] * $sum_mixTMTK_Ncp;

$A_total_M_TK = $plate[$o]['A_r'] * $sum_M_TK_Ncp;
$A_total_M_TM = $plate[$o]['A_r'] * $sum_M_TM_Ncp;
$A_total_M_TL = $plate[$o]['A_r'] * $sum_M_TL_Ncp;

$A_total_E_TK = $plate[$o]['A_r'] * $sum_E_TK_Ncp;
$A_total_E_TM = $plate[$o]['A_r'] * $sum_E_TM_Ncp;
$A_total_E_TL = $plate[$o]['A_r'] * $sum_E_TL_Ncp;
//K值
$U_mixTMTL = $Q/$LMTD/$A_total_mixTMTL*1000;
$U_mixTMTK = $Q/$LMTD/$A_total_mixTMTK*1000;

$U_M_TK = $Q/$LMTD/$A_total_M_TK*1000;
$U_M_TM = $Q/$LMTD/$A_total_M_TM*1000;
$U_M_TL = $Q/$LMTD/$A_total_M_TL*1000;

$U_E_TK = $Q/$LMTD/$A_total_E_TK*1000;
$U_E_TM = $Q/$LMTD/$A_total_E_TM*1000;
$U_E_TL = $Q/$LMTD/$A_total_E_TL*1000;

?>
	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapseTK" . $o . "'>";
		?>
	  		TK参数
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapseTK" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
		<?php
	  	echo "<a data-toggle='collapse' href='#collapseTM" . $o . "'>";
		?>
	  		TM参数
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapseTM" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapseTL" . $o . "'>";
		?>
	  		TL参数
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapseTL" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapse00" . $o . "'>";
		?>
	  		整机R和实际各板定性温度
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapse00" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapse01" . $o . "'>";
		?>
	  		质量守恒下的TK、TM、TL的板片数
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapse01" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapse02" . $o . "'>";
		?>
	  		能量守恒下的TK、TM、TL的板片数
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapse02" . $o . "' class='panel-collapse collapse'>";
	  ?>
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

	<div class="panel panel-default">
	  <div class="panel-heading">
	  	<?php
	  	echo "<a data-toggle='collapse' href='#collapse03" . $o . "'>";
		?>
	  		结果分析
	  	</a>
	  </div>
	  <?php
	  echo "<div id='collapse03" . $o . "' class='panel-collapse collapse'>";
	  ?>
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
