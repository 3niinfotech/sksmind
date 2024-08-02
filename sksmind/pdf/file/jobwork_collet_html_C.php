
<page style="font-size:10px;">
    
    <table cellspacing="0" cellpadding="0"  border="1px"   style="width: 100%;font-size:10px; text-align: center; border-collapse:collapse;">
        <tr style="width:100%;padding:0px; margin:0px;">
            
            <td style="width:10%;text-align: center; padding:0px; margin:0px; border:0; vertical-align:top;">
				<table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;" cellspacing="0"  cellpadding="0" >
					<tr style=" padding:0px; margin:0px;">
						<td style="border-right:0;">
							<img style="width:98%" src="sksm.jpg" alt="Logo">
						</td>
					</tr>
					
                </table>
            </td>
			<td colspan="2" style="vertical-align:top;width:60%;text-align: center; vertical-align:top; border:0;padding:0px; margin:0px;">
				<table   border="1" style="width:100%; border-collapse:collapse;" cellspacing="0" cellpadding="0" >
					<tr>
						<th style="padding:1px; color:#000;padding:0px;padding-top:5px;padding-bottom:5px; width:100%;margin:0px;font-size:20px;">SKSM DIAMONDS IMPEX LIMITED<br>
							<p style="width:100%; padding:0px;padding-top:10px; font-size: 11px;margin:0px;">9, Ground Floor, Princess Plaza,Sardar Chowk, Minibazar,Varachha Road, Surat - 395006<br></p>
							<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 9px;"><b>Contact:</b> 0261 2545551 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> infosksmimpex@gmail.com</p>

						</th>
					</tr>
					<tr >
						<th style="padding:0px;padding:1px; text-align:left; width:100%;font-weight:normal;"><b> DATE : </b><?php echo $date; ?></th>
					</tr>
					<tr>
						<th style="padding:1px;  text-align:left; width:100%;font-weight:normal;"><b> NAME : </b> <?php echo $party['name']; ?></th>
					</tr>
					<tr>
						<th style="padding:1px; text-align:left;font-weight:normal;"> <b> ADDRESS : </b> <?php echo $party['address']; ?></th>
					</tr>
				</table>
			</td>
			<td style="vertical-align:top;width:30%;border-bottom:0;padding:0px;margin:0px;vertical-align:top; border-bottom:1px solid #000;" >
					
				<table cellspacing="0" cellpadding="0" style="width:100%;padding:0px;margin:0px;">
					<tr style="padding:0px;margin:0px;" >
						<td style="height:57px;vertical-align:middle;font-weight:bold;width:70%;text-align:center;font-size:10px; border-bottom:1px solid #000;padding:0px;margin:0px;">JEWELRY JOB-CARD NO. <?php echo $data['entryno']; ?></td>
						<td style="height:97px;width:30%;text-align:center;vertical-align:middle;padding:0px;margin:0px;border-left:1px solid #000;" rowspan="2" ><img  style="width:50px;text-align:center;padding:0px;margin:0px;" src="logo-new.png" alt="Logo"></td>	
					</tr >
					<tr style="padding:0px;margin:0px;">
						<td style="height:50px;font-weight:bold;width:70%;text-align:center;vertical-align:middle;font-size:10px;padding:0px;margin:0px;">
							VENDOR
						</td>
					</tr>
				</table>
			</td>
			
		</tr>
		  <?php 
			 $j = $c = 0;
			 $class = '';
			 foreach($data['record'] as $jData): 
			 $j++;
			 $c++;
			 $jd = (isset($jData['jew_design'])) ? $jData['jew_design'] : "N/A"; 
			$mainSku = "";
			 if(!empty($jData['main_stone']))
			{
				foreach($jData['main_stone'] as $main){
					$mainSku = $main['sku'];
					break;
				}
			}
			else
			{
				foreach($jData['collet_stone'] as $main){
				$mainSku = $main['sku'];
					break;
				}
			} 
			$phpdate = strtotime( $jData['date'] );
			$date =  date( 'd-m-Y', $phpdate );
			
			$phpdate = strtotime( $jData['duedate'] );
			$duedate =  date( 'd-m-Y', $phpdate );
			if($c%5 == 0)
			{
			  $class = 'border-top:1px solid';			
			}
	?>	 
        <tr style="width:100%;padding:0px; margin:0px;font-size:8px;">
            <td style="width:10%;text-align: center; padding:0px; margin:0px; border:0; vertical-align:top;<?php echo $class;?>">
					<table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;" cellspacing="0"  cellpadding="0" >
						<tr style=" padding:0px; margin:0px;">
							<td style="width:40%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">J/W<br> CODE</td>
							<td style="width:60%;height:34px;padding:0px;padding:1px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;border-right:0;">BARCODE</td>
							
						</tr>
						<tr>
							<td   style="width: 40%;height:74.3px;text-align: center;padding:1px;vertical-align:middle"><?php echo $j; ?></td>
							<td   style="width: 60%;text-align: center;padding:1px;border-right:0;"><img  style="width:30px;height:74.3px;" src="barcodej.png" alt="Logo"></td>
							
						</tr>
					</table>
			</td>
			<td style="width:15%;text-align: center; padding:0px; margin:0px;border:0;vertical-align:top;<?php echo $class;?>">
				<table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;" cellspacing="0"  cellpadding="0" >
						<tr  style=" padding:0px; margin:0px;">
							<td colspan="2" style="width:100%;height:14px;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;border-right:0;">JEWELRY CODE</td>
						</tr>	
						<tr style=" padding:0px; margin:0px;">
							<td style="width:30%;padding:0px;margin:0px;padding:1px;border-top:0;border-right:0;text-align:left;vertical-align:middle;">ID NO</td>
							<td style="width:70%;height:14px;padding:0px;margin:0px;padding:1px;border-top:0;text-align:right;vertical-align:middle;border-right:0;">DESIGN</td>
						</tr>
						<tr style=" padding:0px; margin:0px;">
							<td style="width:30%;padding:0px;margin:0px;padding:1px;border-top:0;border-right:0;text-align:left;"><?php echo $mainSku; ?></td>
							<td style="width:70%;border-right:0;padding:0px;margin:0px;padding:1px;border-top:0;text-align:right;vertical-align:middle;">
							<?php 
								$filename = "collets/".$mainSku.".jpg";
								if(file_exists($filename)):
							?>
							<img  style="width:72.3px;height:72.3px;margin:2px 3px 5px 2px;padding:0;" src="collets/<?php echo $mainSku.".jpg" ?>" alt="Logo">
							<?php else: ?>
							<img  style="width:72.3px;height:72.3px;margin:2px 3px 5px 2px;padding:0;" src="collets/logo-new.png" alt="Logo">
							<?php endif; ?>
							</td>
						</tr>
				</table>
				</td>
				<td style="width:45%;padding:0px; margin:0px;border:0;vertical-align:top;border-bottom:1px solid #000;<?php echo $class;?>">
					<table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;vertical-align:top;" cellspacing="0"  cellpadding="0">
						<tr style=" padding:0px; margin:0px;">
						<td colspan="7" style="width:50%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">DIAMOND</td>
						<td colspan="7" style="width:50%;height:14px;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">METAL</td>
						</tr>
						<tr style=" padding:0px; margin:0px;">
						<td style="width:8%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">SHAPE</td>
						<td style="width:5%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">SIZE<br>(mm)</td>
						<td style="width:7%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">CLARITY</td>
						<td style="width:6%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">COLOR</td>
						<td style="width:4%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">PCS</td>
						<td style="width:5%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">WTS<br>(cts)</td>
						<td style="width:6%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">RATE<br>(Rs.)</td>
						<td style="width:7%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">DETAIL</td>
						<td style="width:4%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">KT</td>
						<td style="width:7%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">COLOR</td>
						<td style="width:9%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">GROSS WT(gm)</td>
						<td style="width:8%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">NET WT<br>(gm)</td>
						<td style="width:7%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">PG WT<br>(gm)</td>
						<td style="width:7%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">RATE<br>(Rs.)</td>
					</tr>
					<?php $count = 1;
					foreach($jData['main_stone'] as $main):?>
					
					
					<tr style=" padding:0px; margin:0px;font-size:7px">
						<td style="width:8%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo trim($main['shape'])?></td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['size']?></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['clarity']?></td>
						<td style="width:6%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['color']?></td>
						<td style="width:4%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['pcs']?></td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['carat']?></td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:1;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo number_format($main['price'],0,'.','');?></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;">COLLET</td>
						<td style="width:4%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['collet_kt']?></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['collet_color']?></td>
						<td style="width:9%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['gross_weight']?></td>
						<td style="width:8%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['net_weight']?></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['pg_weight']?></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-bottom:0;text-align:center;vertical-align:middle;"><?php echo $main['collet_rate']?></td>
					</tr>
					<?php $count++;endforeach;?>	
									<?php for($i=$count;$i<8;$i++): ?>	
					
					<tr style=" padding:0px; margin:0px;font-size:7px">
						<td style="width:8%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;">&nbsp;</td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:6%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:4%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:5%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:1;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:4%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:9%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:8%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-right:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
						<td style="width:7%;padding:0px;margin:0px;padding:2px;border-top:0;border-bottom:0;text-align:center;vertical-align:middle;"></td>
					</tr>
					<?php endfor;?>	
					</table>
			</td>
			<td style="vertical-align:top;width:30%;padding:0px;margin:0px;vertical-align:top; border:0;border-bottom:1px solid #000;<?php echo $class;?>">
			   <table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;vertical-align:top;">
					<tr style="padding:0px; margin:0px;">
					  <th style="width:12%;padding:0px;border-left:0;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   OTHER
					  </th>
					   <th style="width:14%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   LABOUR
					  </th>
					   <th rowspan="2" style="width:18%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   ISSUE<br> DATE
					  </th>
					   <th rowspan="2" style="width:16%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   DUE<br> DATE
					  </th>
					   <th colspan="2" style="width:25%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   PAYMENT<br>DETAIL
					  </th>
					   <th rowspan="2" style="width:15%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   REMARK
					  </th>
					  </tr>
					  <tr style="padding:0px; margin:0px;">
					  <th style="width:12%;border-left:0;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   RATE<br>(Rs.)
					  </th>
					   <th style="width:14%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					   RATE<br>(Rs.)
					  </th>
					   <th style="width:10%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					  DATE
					  </th>
					   <th style="width:15%;padding:0px;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;">
					  CHQ. NO.
					  </th>
					  </tr>
					  <?php 
							foreach($jData['main_stone'] as $main):?>
					  <tr style="padding:0px; margin:0px;font-size:7px;">
						  <td style="padding:0px;border-left:0;border-bottom:0;margin:0px;padding:1px;border-top:0;text-align:center;vertical-align:middle;"><?php echo number_format($main['other_amount'],0,'.',''); ?></td>
						  <td style="padding:0px;margin:0px;padding:1px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"><?php echo number_format($main['labour_amount'],0,'.',''); ?></td>
						  <td style="padding:0px;margin:0px;padding:1px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"><?php echo $date; ?> </td>
						  <td style="padding:0px;margin:0px;padding:1px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"><?php echo $duedate; ?></td>
						  <td style="padding:0px;margin:0px;padding:1px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;padding:1px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;border-bottom:0;padding:1px;border-top:0;text-align:center;vertical-align:middle;"></td>
						</tr>
						
						<?php 
						endforeach;
						$count =2;
						for($i=$count;$i<8;$i++): ?>	
						<tr style="padding:0px; margin:0px;font-size:7px;">
						  <td style="padding:0px;border-left:0;border-bottom:0;margin:0px;padding:2px;border-top:0;text-align:center;vertical-align:middle;">&nbsp;</td>
						  <td style="padding:0px;margin:0px;padding:2px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;padding:2px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"> </td>
						  <td style="padding:0px;margin:0px;padding:2px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;padding:2px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;padding:2px;border-bottom:0;border-top:0;text-align:center;vertical-align:middle;"></td>
						  <td style="padding:0px;margin:0px;border-bottom:0;padding:2px;border-top:0;text-align:center;vertical-align:middle;"></td>
						</tr>
						<?php endfor; ?>
			   </table>
			</td>
		</tr>
		<?php if($c%4 == 0):?>
		<tr><td colspan="4">
		<br />
		<br />
		<br />
		<br />
		<br />
		<br /><br />
		<br />
		<br />
		<br />
		<br />
		<br />
		</td>
		</tr>
		<?php endif; ?>
		<?php endforeach; ?>
</table>
<table cellspacing="0" cellpadding="0" border="1" style="width:100%;border-collapse:collapse;padding:0px; margin:0px;">
		<tr>
			
					<th style="width:17%;height:50px;border-top:0;text-align:right;border-right:0px;vertical-align:bottom;padding-bottom:10px;">RECEIVER'S SIGNATURE</th>
					<th style="width:55%;height:50px;border-top:0;border-right:0px;vertical-align:bottom;padding-bottom:10px;text-align:center;">SUBJECT TO SURAT JURISDICTION ONLY</th>
					<th style="width:28.08%;height:50px;border-top:0;vertical-align:bottom;padding-bottom:10px;"><h5 style="vertical-align:top;border-top:0;margin-bottom:20px;text-align:left;">SKSM DIAMONDS IMPEX LIMITED</h5><br>AUTHORISED SIGNATURE</th>
					
			
		</tr>
</table>

</page>

