<?php 
foreach(explode(',',$jid) as $j):
$data = $jhelper->getDatajewelry($j);
$sku = $data['sku'];
?>
<page style="font-size: 12pt">
    <bookmark title="<?php echo $sku;?>" level="0" ></bookmark>

		<table style="width:28%;font-size:7px;font-weight:bold;text-align:left" cellpadding="0" cellspacing="" >
				
				<tr style="width:100%">
				<td style="width:100%;font-size:7px;text-align:left;vertical-align:top;line-height:9px">
					<?php
						$pcs = $carat = $spcs= $scarat = 0;
						foreach($data['main_stone'] as $k=>$mdata): 
						if(empty($mdata))
						continue;
						$pcs = $pcs + $mdata['pcs'];
						$carat = $carat + $mdata['carat'];
						
						endforeach; ?>
					
					<?php foreach($data['collet_stone'] as $k=>$mdata): 
						if(empty($mdata))
						continue;	
						$pcs = $pcs + $mdata['pcs'];
						$carat = $carat + $mdata['carat'];
						endforeach; ?>
					
					<?php foreach($data['side_stone'] as $k=>$sdata):
						if(empty($sdata))
						continue;
						$spcs = $spcs + $sdata['pcs'];
						$scarat = $scarat + $sdata['carat'];
						endforeach; ?>
					<br/>
					<?php 
						if($data['side_carat'] != 0.00)
						{
							$spcs = $spcs + $data['side_pcs'];
							$scarat = $scarat + $data['side_carat'];
						}
					?>
					<?php if($carat != 0): ?> 
					CS- <?php echo $pcs ?>/<?php echo $carat ?><br/>
					<?php endif; ?>
					<?php if($scarat != 0): ?> 
					SD- <?php echo $spcs ?>/<?php echo $scarat ?><br/>
					<?php endif; ?>
				<?php echo $data['gold'];?>K GLD- <?php echo $data['gross_weight'];?>GM
				<br/><br/>
				<?php echo $data['sku'];?><br>
				<?php echo ($data['selling_price'] == 0.00)?$data['total_amount']:$data['selling_price'];?>
				<br/>
				<?php if(!empty($data['igi_code']) && !empty($data['main_stone'])): ?>
				IGI- 
				<?php foreach($data['main_stone'] as $k=>$mdata): 
						if(empty($mdata))
						continue;	
					?>
					(<?php
					echo trim(substr($mdata['color'],0,strpos($mdata['color'],'(')));?>, <?php echo $mdata['clarity']?>)
					<?php endforeach; ?>
				<?php endif; ?>	
				</td>
				</tr>
		</table>
</page>
<?php endforeach; ?>