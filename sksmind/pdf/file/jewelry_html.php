<style>
table, td, th {
	border-color: #a7a7a7;
}
table th {
background: #FFFFCC;
color: #9C0006;
text-transform: uppercase;
border:1px solid #a7a7a7;
border-bottom:0px;
}
tbody tr td{
	border-top:0;
}
.gray {
	background: #999;
	color: #fff;
	font-weight: bold;
}
</style>
<page style="font-size:8px;">
	 <?php
	$i=1; 
	foreach($data as $k=>$value): 
	?>
	<table cellspacing="0" cellpadding="0" style="width: 100%;font-size:7px; text-align: center; border-collapse:collapse;">
			<tr style="width:100%;padding:0px; margin:0px;">
				<th style="width: 4%;padding: 0px;text-align: center;">Sr No.</th>
				<th style="width: 14%;padding: 5px;text-align: center;">Product Image</th>
				<th style="width: 15%;padding: 5px;text-align: center;">Name & Code</th>
				<th style="width: 7%;padding: 5px;text-align: center;">IGI No.</th>
				<th style="width: 60%;padding: 0px;text-align: center;vertical-align:top;border-bottom:0;padding-top:2px;line-height:9px">Diamonds
					<table cellspacing="0" cellpadding="0" style="width: 100%;font-size:7px; text-align: center; border-collapse:collapse;border-color:#ccc;vertical-align:middle">
						<tr style="width:100%;padding:0px; margin:0px;">
							<th style="width: 13%;padding: 3px;text-align: center;border-bottom:0;border-left:0">IGI No.</th>
							<th style="width: 15%;padding: 3px;text-align: center;border-bottom:0">Lot No.</th>
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0">Color</th>
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0">Clarity</th>
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0">PCS</th>
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0">CTS</th>			
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0;">Asking Price</th>
							<th style="width: 12%;padding: 3px;text-align: center;border-bottom:0;border-right:0"></th>				
						</tr>
					</table>
				</th>
									
			</tr>
	</table>
	<table cellspacing="0" cellpadding="0"  border="1px"   style="width: 100%;font-size:8px; text-align: center; border-collapse:collapse;">
			<tr style="width:100%;padding:0px; margin:0px;">
				<td style="width: 4%;padding: 2px;text-align: center;"><?php echo $i?></td>
				<td style="width: 14%;padding: 3px;text-align: center;">
					<?php 
					$filename = "jewels/".$value['sku'].".jpg";
					if(file_exists($filename)):
					?>
					<img src="jewels/<?php echo $value['sku'].".jpg"; ?>" alt="" style="width:90px;height:90px"/>
					<?php else: ?>
					<img src="jewels/logo-new.png" alt="" style="width:90px;height:90px"/>
					<?php endif; ?>
					
				</td>
				<td style="width: 15%;padding: 3px;text-align: center;">
				<?php echo $value['name']?><br/>
					<h6 style="padding: 0px;margin: 0px;font-weight: bold;font-size:8px;background: #092648;color: #fff;line-height:25px">&nbsp;&nbsp;<?php echo $value['sku']?>&nbsp;&nbsp;</h6>
				</td>
				<td style="width: 7%;padding: 3px;text-align: center;"><?php echo $value['igi_code'];?>
				 <h6 style="margin: 0px;font-weight: bold;font-size:8px;background: #092648;color: #fff;width:100%;padding:0px;height:30px">&nbsp;&nbsp;<?php echo $value['gross_weight']?>&nbsp;&nbsp;</h6>
				</td>
				<td style="width: 60%;padding: 0px;text-align: center;vertical-align:top" >
					<table cellspacing="0" cellpadding="0"  border="1px"   style="width: 100%;font-size:8px; text-align: center; border-collapse:collapse;">
						<tbody>
						<?php foreach($value['collet'] as $key=>$record):?>
						<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
							<td style="width: 13%;padding: 3px;text-align: center;border-left:0"><?php echo $record['report_no'] ?></td>
							<td style="width: 15%;padding: 3px;text-align: center;"><?php echo $record['sku'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['color'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['clarity'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['pcs'] ?></td>						
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['carat'] ?></td>	
							<td style="width: 12%;padding: 3px;text-align: center;"></td>			
							<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>			
						</tr>
						<?php endforeach; ?>
						<?php foreach($value['main'] as $key=>$record):?>
						<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
							<td style="width: 13%;padding: 3px;text-align: center;border-left:0"><?php echo $record['report_no'] ?></td>
							<td style="width: 15%;padding: 3px;text-align: center;" style="width:12%"><?php echo $record['sku'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['color'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['clarity'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['pcs'] ?></td>						
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['carat'] ?></td>	
							<td style="width: 12%;padding: 3px;text-align: center;"></td>			
							<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>			
						</tr>
						<?php endforeach; ?>
						<?php foreach($value['side'] as $key=>$record):
							if($record['outward_parent'] == 0)
							{
								$sku = $record['sku'];
							}
							else
							{
								$parentData = $jhelper->getSideProductDetail($record['outward_parent']);$record['color'] = $parentData['color'];
								$record['clarity'] = $parentData['clarity'];			
								$sku = $parentData['sku'];
							}
						?>
						<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
							<td style="width: 13%;padding: 3px;text-align: center;border-left:0"><?php echo $record['report_no'] ?></td>
							<td style="width: 15%;padding: 2px;text-align: center;" style="width:12%"><?php echo $sku; ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['color'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['clarity'] ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['pcs'] ?></td>	<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $record['carat'] ?></td>	
							<td style="width: 12%;padding: 3px;text-align: center;"></td>	
							<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>				
						</tr>
						<?php endforeach; ?>
						<?php if($value['side_carat'] != 0 || $value['side_carat'] != 0.00):?>
						<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
							<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
							<td style="width: 15%;padding: 3px;text-align: center;"><?php echo "Extra" ?></td>
							<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
							<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $value['side_pcs'] ?></td>						
							<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $value['side_carat'] ?></td>	
							<td style="width: 12%;padding: 3px;text-align: center;"></td>			
							<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>			
						</tr>
						<?php endif; ?>
							<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;"></td>					
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>	
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>			
								<td rowspan="2" style="width: 12%;padding: 3px;text-align: center;border-right:0"><?php echo $jewType[$value['jew_type']] ?></td>	
							</tr>
							<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>					
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>			
								<td style="width: 12%;padding: 3px;text-align: center;"></td>	
							</tr>
							<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>					
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>	
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>			
								<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>	
							</tr>
							<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;"><?php echo (isset($value['gold']))?$value['gold']."K Gold":'' ?></td>					
								<td style="width: 12%;padding: 3px;text-align: center;"><?php echo $value['net_weight'] ?></td>	
								<td style="width: 12%;padding: 3px;text-align: center;"></td>			
								<td style="width: 12%;padding: 3px;text-align: center;border-right:0"></td>			
							</tr>
							<tr style="width:100%;padding:0px; margin:0px;vertical-align:middle">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;">&nbsp;</td>			
								<td style="width: 24%;padding: 3px;text-align: center;border-bottom:0" class="gray" colspan="2" rowspan="2" >TOTAL INR</td>	
								<td class="gray" style="width: 12%;padding: 3px;border-bottom:0;text-align: center;" rowspan="2"><?php echo round($value['selling_price']); ?></td>
								<td style="width: 12%;padding: 3px;text-align: center;border-right:0">&nbsp;</td>
							</tr>
							<tr style="width:100%;padding:0px; margin:0px;">
								<td style="width: 13%;padding: 3px;text-align: center;border-left:0;border-bottom:0;">&nbsp;</td>
								<td style="width: 15%;padding: 3px;text-align: center;border-bottom:0;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;border-bottom:0;">&nbsp;</td>
								<td style="width: 12%;padding: 3px;text-align: center;border-bottom:0;">&nbsp;</td>		
								<td style="width: 12%;padding: 3px;text-align: center;border-bottom:0;border-right:0">&nbsp;</td>	
							</tr>
						</tbody>
					</table>
				</td>
									
			</tr>
			
		</table>
<?php $i++; endforeach; ?>
</page>

