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
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TK_V_1_c</th>
	    		<th><?=$TK_V_1_c?></th>
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TK_Re_2</th>
	    		<th><?=$TK_Re_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TK_Re_1</th>
	    		<th><?=$TK_Re_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TK_Nu_2</th>
	    		<th><?=$TK_Nu_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TK_Nu_1</th>
	    		<th><?=$TK_Nu_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TK_h_2</th>
	    		<th><?=$TK_h_2?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TK_h_1</th>
	    		<th><?=$TK_h_1?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TK_U</th>
	    		<th><?=$TK_U?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TK_NTU_1</th>
	    		<th><?=$TK_NTU_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TK_sigma_1</th>
	    		<th><?=$TK_sigma_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TK_R_1</th>
	    		<th><?=$TK_R_1?></th>
	    		<th></th>
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
	    		<th>kg/s</th>
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
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TK_miu_d_T_2_w</th>
	    		<th><?=$TK_miu_d_T_2_w?></th>
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>19</th>
	    		<th>TK_T_b_cp</th>
	    		<th><?=$TK_T_b_cp?></th>
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
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TM_V_1_c</th>
	    		<th><?=$TM_V_1_c?></th>
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TM_Re_2</th>
	    		<th><?=$TM_Re_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TM_Re_1</th>
	    		<th><?=$TM_Re_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TM_Nu_2</th>
	    		<th><?=$TM_Nu_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TM_Nu_1</th>
	    		<th><?=$TM_Nu_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TM_h_2</th>
	    		<th><?=$TM_h_2?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TM_h_1</th>
	    		<th><?=$TM_h_1?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TM_U</th>
	    		<th><?=$TM_U?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TM_NTU_1</th>
	    		<th><?=$TM_NTU_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TM_sigma_1</th>
	    		<th><?=$TM_sigma_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TM_R_1</th>
	    		<th><?=$TM_R_1?></th>
	    		<th></th>
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
	    		<th>kg/s</th>
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
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TM_miu_d_T_2_w</th>
	    		<th><?=$TM_miu_d_T_2_w?></th>
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>19</th>
	    		<th>TM_T_b_cp</th>
	    		<th><?=$TM_T_b_cp?></th>
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
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>TL_V_1_c</th>
	    		<th><?=$TL_V_1_c?></th>
	    		<th>m/s</th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>TL_Re_2</th>
	    		<th><?=$TL_Re_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>4</th>
	    		<th>TL_Re_1</th>
	    		<th><?=$TL_Re_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>5</th>
	    		<th>TL_Nu_2</th>
	    		<th><?=$TL_Nu_2?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>6</th>
	    		<th>TL_Nu_1</th>
	    		<th><?=$TL_Nu_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>7</th>
	    		<th>TL_h_2</th>
	    		<th><?=$TL_h_2?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>8</th>
	    		<th>TL_h_1</th>
	    		<th><?=$TL_h_1?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>9</th>
	    		<th>TL_U</th>
	    		<th><?=$TL_U?></th>
	    		<th>W/(m2*K)</th>
	    	</tr>
	    	<tr>
	    		<th>10</th>
	    		<th>TL_NTU_1</th>
	    		<th><?=$TL_NTU_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>11</th>
	    		<th>TL_sigma_1</th>
	    		<th><?=$TL_sigma_1?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>12</th>
	    		<th>TL_R_1</th>
	    		<th><?=$TL_R_1?></th>
	    		<th></th>
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
	    		<th>kg/s</th>
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
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>18</th>
	    		<th>TL_miu_d_T_2_w</th>
	    		<th><?=$TL_miu_d_T_2_w?></th>
	    		<th>N*s/m2</th>
	    	</tr>
	    	<tr>
	    		<th>19</th>
	    		<th>TL_T_b_cp</th>
	    		<th><?=$TL_T_b_cp?></th>
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
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>Total_R</th>
	    		<th><?=$Total_R?></th>
	    		<th></th>
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
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>M_TM_Ncp</th>
	    		<th><?=$M_TM_Ncp?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>M_TL_Ncp</th>
	    		<th><?=$M_TL_Ncp?></th>
	    		<th></th>
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
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>2</th>
	    		<th>E_TM_Ncp</th>
	    		<th><?=$E_TM_Ncp?></th>
	    		<th></th>
	    	</tr>
	    	<tr>
	    		<th>3</th>
	    		<th>E_TL_Ncp</th>
	    		<th><?=$E_TL_Ncp?></th>
	    		<th></th>
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
	    		<th>换热面积(m2)</th>
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
	    		<th>换热系数(W/(m2*K))</th>
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
