<?php
//计算TK
if ($W_1 >= $W_2) {
	$TK_V_1_c=exp((1/($plate[$o]['TK_m_Eu']+2)) * 
		log($delta_p_1*1000/$plate[$o]['TK_C_Eu']/(exp($plate[$o]['TK_m_Eu']*
			log($plate[$o]['De']/$miu_d1)))/(exp(($plate[$o]['TK_m_Eu']+1)*log($roll1)))));
	$TK_V_2_c=$TK_V_1_c * $roll1 * $W_2/$W_1/$roll2;
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
	if ($HIHO == $COCI) {
		$TK_R_1=$TK_NTU_1/(1+$TK_NTU_1);
	} else {
		$TK_R_1=((1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))))/
	(($TK_sigma_1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))));
	}
	$TK_t_1=$TK_R_1*$Delta_ti;
	$TK_W_1=$TK_V_1_c*$plate[$o]['A_c']*$roll1;
	$TK_T_b_cp=$T_1_i-($TK_t_1*0.5);

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
	if ($HIHO == $COCI) {
		$TK_R_1=$TK_NTU_1/(1+$TK_NTU_1);
	} else {
		$TK_R_1=((1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))))/
	(($TK_sigma_1-exp($TK_NTU_1*(1-$TK_sigma_1))*log(exp(1))));
	}	
	$TK_t_1=$TK_R_1*$Delta_ti;
	$TK_W_1=$TK_V_1_c*$plate[$o]['A_c']*$roll1;
	$TK_T_b_cp=$T_1_i-($TK_t_1*0.5);

}	

//计算TM
if ($W_1 >= $W_2) {
	$TM_V_1_c=exp((1/($plate[$o]['TM_m_Eu']+2)) * 
		log($delta_p_1*1000/$plate[$o]['TM_C_Eu']/(exp($plate[$o]['TM_m_Eu']*
			log($plate[$o]['De']/$miu_d1)))/(exp(($plate[$o]['TM_m_Eu']+1)*log($roll1)))));
	$TM_V_2_c=$TM_V_1_c * $roll1 * $W_2/$W_1/$roll2;
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
	if ($HIHO == $COCI) {
		$TM_R_1=$TM_NTU_1/(1+$TM_NTU_1);
	} else {
		$TM_R_1=((1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))))/
	(($TM_sigma_1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))));
	}	
	$TM_t_1=$TM_R_1*$Delta_ti;
	$TM_W_1=$TM_V_1_c*$plate[$o]['A_c']*$roll1;
	$TM_T_b_cp=$T_1_i-($TM_t_1*0.5);


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
	if ($HIHO == $COCI) {
		$TM_R_1=$TM_NTU_1/(1+$TM_NTU_1);
	} else {
		$TM_R_1=((1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))))/
	(($TM_sigma_1-exp($TM_NTU_1*(1-$TM_sigma_1))*log(exp(1))));
	}	
	$TM_t_1=$TM_R_1*$Delta_ti;
	$TM_W_1=$TM_V_1_c*$plate[$o]['A_c']*$roll1;
	$TM_T_b_cp=$T_1_i-($TM_t_1*0.5);

}	


//计算TL
if ($W_1 >= $W_2) {
	$TL_V_1_c=exp((1/($plate[$o]['TL_m_Eu']+2)) * 
		log($delta_p_1*1000/$plate[$o]['TL_C_Eu']/(exp($plate[$o]['TL_m_Eu']*
			log($plate[$o]['De']/$miu_d1)))/(exp(($plate[$o]['TL_m_Eu']+1)*log($roll1)))));
	$TL_V_2_c=$TL_V_1_c * $roll1 * $W_2/$W_1/$roll2;
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
	if ($HIHO == $COCI) {
		$TL_R_1=$TL_NTU_1/(1+$TL_NTU_1);
	} else {
		$TL_R_1=((1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))))/
	(($TL_sigma_1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))));
	}	
	$TL_t_1=$TL_R_1*$Delta_ti;
	$TL_W_1=$TL_V_1_c*$plate[$o]['A_c']*$roll1;
	$TL_T_b_cp=$T_1_i-($TL_t_1*0.5);


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
	if ($HIHO == $COCI) {
		$TL_R_1=$TL_NTU_1/(1+$TL_NTU_1);
	} else {
		$TL_R_1=((1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))))/
	(($TL_sigma_1-exp($TL_NTU_1*(1-$TL_sigma_1))*log(exp(1))));
	}	
	$TL_t_1=$TL_R_1*$Delta_ti;
	$TL_W_1=$TL_V_1_c*$plate[$o]['A_c']*$roll1;
	$TL_T_b_cp=$T_1_i-($TL_t_1*0.5);

}	



//计算整机R
$Total_sigma=($W_1*$C_p1)/($W_2*$C_p2);
if ($HIHO == $COCI) {
	$Total_R=$NTU/(1+$NTU);
} else {
	$Total_R=((1-exp($NTU*(1-$Total_sigma))*log(exp(1))))/
	(($Total_sigma-exp($NTU*(1-$Total_sigma))*log(exp(1))));
}


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

if ($TL_T_b_cp > $T_1_b) {

echo "<div><p class='bg-danger lead'>换热能力不符合要求，TL_T_b_cp=".$TL_T_b_cp."°C</p></div><hr >";	

} else {
include("tktmtl_template.php");
}
?>
