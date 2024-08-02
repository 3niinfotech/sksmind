<?php 
/* foreach(explode(',',$jid) as $j):
$data = $jhelper->getDatajewelry($j);
$sku = $data['sku']; */
?>
<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
-->
</style>
<page style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #e0e0e0; text-align: left; font-size: 10pt; border-bottom:0px">
		<tr class="ttl">
			<td style="width:100%;text-align:center;border-bottom:0px"><b style="line-height:20px">TAX INVOICE</b></td>
		</tr> 
	</table>
		
	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #000; text-align: center; font-size: 10pt; border-bottom:0px">
		<tr style="border-bottom:0px">
			<td style="width:8%;border-bottom:0px"> &nbsp; </td>
				<td style="width:18%;border-bottom:0px"> &nbsp; </td>
				<td style="width:7%;border-bottom:0px">&nbsp; </td>
				<td style="width:7%;border-bottom:0px">&nbsp; </td>
				<td style="width:8%;border-bottom:0px">&nbsp; </td>
				<td style="width:8%;border-bottom:0px">&nbsp; </td>
				<td style="width:4%;border-bottom:0px">&nbsp; </td>
				<td style="width:8%;border-bottom:0px">&nbsp; </td>
				<td style="width:4%;border-bottom:0px">&nbsp; </td>
				<td style="width:7%;border-bottom:0px">&nbsp;</td>
				<td style="width:4%;border-bottom:0px">&nbsp; </td>
				<td style="width:8%;border-bottom:0px">&nbsp;</td>
				<td style="width:9%;border-bottom:0px">&nbsp;</td>
		</tr>
	</table>
	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #e0e0e0; text-align: left; font-size: 10pt;border-bottom:0px">
		<tr style="">
			<td style="width:8%;border-bottom:0px"><b>TO:</b></td>
			<td style="width:32%;border-bottom:0px"><b><?php echo $data['p_name'];?></b></td>
			<td style="width:60%;border-bottom:1px;text-transform:uppercase;text-align:center"><b>original&nbsp;&nbsp;&nbsp;&nbsp;Duplicate&nbsp;&nbsp;&nbsp;&nbsp;triplicate</b></td>
		</tr>
	</table>
	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #e0e0e0; text-align: left; font-size: 10pt; border-color:#e0e0e0" class="clr-bdr">
		<tr>
			<td style="width:8%"><b>ADD: </b></td>
			<td style="width:32%"><?php echo $data['p_address'];?></td>
			<td style="width:30%;text-align:left;"> invoice no. :- </td>
			<td style="width:30%;text-align:left;"> invoice Date : </td>
		</tr>
		<tr>
			<td style="width:8%"></td>
			<td style="width:30%"></td>
			<td style="width:30%;text-align:left;border-bottom:2px solid #000"><b><?php echo $data['invoiceno'];?></b></td>
			<td style="width:30%;text-align:left;border-bottom:2px"><b><?php echo $data['date'];?></b></td>
		</tr>
		<tr>
			<td style="width:8%"></td>
			<td style="width:30%"></td>
			<td style="width:30%;text-align:left;">mode :-</td>
			<td style="width:30%;text-align:left;">terms of payments :-</td>
		</tr>
		<tr>
			<td style="width:8%;border-bottom:0px">&nbsp;</td>
			<td style="width:30%;border-bottom:0px">&nbsp;</td>
			<td style="width:30%;text-align:left;"><?php echo $data['terms']; ?></td>
			<td style="width:30%;text-align:left;"><?php echo $data['terms']; ?></td>
		</tr>
	</table>
	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #ccc; text-align: left; font-size: 10pt; border-top:0px">
		<tr>
			<td style="width:8%"><b>GSTIN:</b></td>
			<td style="width:18%"></td>
			<td style="width:7%"></td>
			<td style="width:7%"></td>
			<td style="width:30%;">Company's PAN No.</td>
			<td style="width:30%;">Company's GST No.</td>
		</tr>
		<tr>
			<td><b>State:</b></td>
			<td><b><?php echo $data['p_country']; ?></b></td>
			<td><b>CODE:</b></td>
			<td><b><?php echo $data['p_pincode']; ?></b></td>
			<td><b><?php echo $data['pan']; ?></b></td>
			<td><b><?php echo $data['gst']; ?></b></td>
		</tr>
	</table>

	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px #000; text-align: center; font-size: 12px; border-top:0px">
		<tr>
			<td style="width:8%"> sr no. </td>
			<td style="width:18%"> Description of goods </td>
			<td style="width:7%"> HSN Code </td>
			<td style="width:7%"> SKU NO. </td>
			<td style="width:8%"> GROSS WEIGHT </td>
			<td style="width:8%"> NET WEIGHT </td>
			<td style="width:12%;"> CENTER 
				<table cellspacing="0" cellpadding="0" style="border-bottom:solid 1px #000; text-align:center; width:100%; border-collapse:collapse;" class="inner-bdr" >
					<tr style="padding:0px; margin:0px;width:100%">
						<td style="border-right:solid 1px;width:33%">PCS</td>
						<td style="width:67%">WEIGHT</td>
					</tr>
				</table>
			</td>
			<td style="width:11%">SIDE
				<table cellspacing="0" cellpadding="0" style="border-top:solid 1px #000; text-align:center; width:100%; border-collapse:collapse;" >
					<tr style="padding:0px; margin:0px;width:100%">
						<td style="border-right:solid 1px;width:33%">PCS</td>
						<td style="width:67%">WEIGHT</td>
					</tr>
				</table>
			</td>
			<td style="width:12%">GEMSTONE
				<table cellspacing="0" cellpadding="0" style="border-top:solid 1px #000; text-align:center; width:100%; border-collapse:collapse;" >
					<tr style="padding:0px; margin:0px;width:100%">
						<td style="border-right:1px solid #000; padding:0px; margin0px;width:33%">PCS</td>
						<td style="width:67%">WEIGHT</td>
					</tr>
				</table>
			</td>
			<td style="width:9%">AMOUNT (rs.)</td>
		</tr>
	</table>
	
	<table cellpadding="5" cellspacing="0" border="1" style="border:solid 1px #000; text-align:right; width:100%; border-collapse:collapse; font-size:12px; vertical-align:middle">
	
	<?php	
	$nb = 25;
	$pcs = $s_weight = $gram = $c_weight = $amount = $tsp = $net = $gold_weight = $gold_rate = $gold_amt = $m_weight = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
	    $sku = $record[$i]['sku'];	
		$gold_weight = $gold_weight + $record[$i]['total_carat'];
		$gold_rate = $gold_rate + $record[$i]['gold_price'];
		$gold_amt = $gold_amt + $record[$i]['gold_amount']; 
		//$pcs += $record[$i]['collet_pcs'] + $record[$i]['side_pcs'] + $record[$i]['main_pcs'];
		$c_weight += $record[$i]['collet_carat'];
		$s_weight += $record[$i]['side_carat']; 
		$m_weight += $record[$i]['main_carat'];
	?>
		<tr style="vertical-align:middle">
			<td style="width:8%"><?php echo $i; ?> </td>
			<td style="width:18%;text-align:center;font-size:10px;"><?php echo $record[$i]['name']; ?>  </td>
			<td style="width:7%">  </td>
			<td style="width:7%; font-size:10px;text-align:center"><?php echo $sku; ?>  </td>
			<td style="width:8%"><?php echo $record[$i]['gross_weight']; ?>  </td>
			<td style="width:8%"><?php echo $record[$i]['net_weight']; ?>  </td>
			<td style="width:4%"><?php echo $record[$i]['collet_pcs'] ?> </td>
			<td style="width:8%;"><?php echo $record[$i]['collet_carat'] ?> </td>
			<td style="width:4%;"><?php echo $record[$i]['side_pcs'] ?> </td>
			<td style="width:7%;"><?php echo $record[$i]['side_carat'] ?>  </td>
			<td style="width:4%;"><?php echo $record[$i]['main_pcs'] ?> </td>
			<td style="width:8%;"><?php echo $record[$i]['main_carat'] ?>  </td>
			<td style="width:9%;"><?php echo number_format($record[$i]['sell_price'], 2, '.', ','); ?> </td>
		</tr>
		<?php
			
			$amount += $record[$i]['sell_price'];
			$net += $record[$i]['net_weight'];
			$center_carat = $center_carat + $cdata['carat'];
			$side_carat = $side_carat + $sdata['carat'];
			
		else: ?>
		
			<tr>
				<td style="width:8%"> &nbsp; </td>
				<td style="width:18%"> &nbsp; </td>
				<td style="width:7%">&nbsp; </td>
				<td style="width:7%">&nbsp; </td>
				<td style="width:8%">&nbsp; </td>
				<td style="width:8%">&nbsp; </td>
				<td style="width:4%">&nbsp; </td>
				<td style="width:8%">&nbsp; </td>
				<td style="width:4%">&nbsp; </td>
				<td style="width:7%;">&nbsp;</td>
				<td style="width:4%;">&nbsp; </td>
				<td style="width:8%;">&nbsp;</td>
				<td style="width:9%;">&nbsp;</td>
			</tr>
			<?php endif;?>
<?php } ?>		
		<tr>
			<td colspan="5">TOTAL</td>
			<td><?php echo $net; ?> </td>
			<td> </td>
			<td><?php echo $c_weight; ?> </td>
			<td> </td>
			<td><?php echo $s_weight; ?> </td>
			<td> </td>
			<td><?php echo $m_weight; ?> </td>
			<td style="font-size:10px"><b><?php echo number_format($amount, 2, '.', ','); ?> </b></td>
		</tr>
		<tr>
			<td colspan="7" style="text-align:left"> Declaration </td>
			<td colspan="5"> Discount </td>
			<td style="text-align:right;font-size:10px"> <?php echo $data['other_less_amount']; ?> </td>
		</tr>
		
		<?php $before_tax_amt = $amount - $data['other_less_amount']; ?>
		
		<tr>
			<td colspan="7" rowspan="3" style="text-align:left; width:20%; vertical-align:top">we declare that this invoice show the actual price of goods described and that all particulars are true and correct.</td>
			<td colspan="5"> Total Amount Before Tax </td>
			<td style="font-size:10px"><?php echo $before_tax_amt; ?></td>
		</tr>
	<?php	
		$record = $data['record'];	
		?>
	
		<tr>
			<td colspan="4" style="width:8%;border-left:0px"> CGST </td>
			<td>  </td>
			<td>  </td>
		</tr>
		<tr>
			<td colspan="4" style="width:8%;border-left:0px"> SGST </td>
			<td>  </td>
			<td>  </td>
		</tr>
		<tr style="text-align:center;vertical-align:middle">
			<td colspan="2" rowspan="2" style="trxt-transform:uppercase;width:10%;vertical-align:middle"><b>Diamond supply by customer</b></td>
			<td colspan="2"><b> PCS </b></td>
			<td colspan="3"><b> WEIGHT(CTS) </b></td>
			<td colspan="4" style="text-align:right"> IGST </td>
			<td> </td>
			<td style="width:7%"> </td>
		</tr> 
		<?php
			
		?>
		<tr style="text-align:center">
			<td colspan="2" style="border-left:0px"><?php echo $record[$i]['total_pcs']; ?></td>
			<td colspan="3"> <?php echo $data['gross_cts']; ?>  </td>
			<td colspan="5" style="text-align:right">Total amount after tax</td>
			<td style="width:7%"><b><?php echo $before_tax_amt ?></b></td>
		</tr> 
		<tr style="text-align:center;vertical-align:middle">
			<td colspan="2" rowspan="2" style="trxt-transform:uppercase;width:10%"><b>Gold supply by customer</b></td>
			<td colspan="2"style="width:7%"><b> NET WEIGHT(18KT) </b></td>
			<td><b> RATE </b></td>
			<td colspan="2"><b> AMT </b></td>
			<td colspan="5" style="text-align:right"> Gold supply by customer </td>
			<td style="width:7%"> </td>
		</tr>
		<tr style="text-align:center">
			<td colspan="2" style="width:7%;border-left:0px"><?php echo $gold_weight; ?></td>
			<td> <?php echo $gold_rate; ?> </td>
			<td colspan="2"> <?php echo $gold_amt; ?> </td>
			<td colspan="5" style="text-align:right"> Total amount </td>
			<td style="width:7%"><b><?php echo $before_tax_amt ?></b> </td>
		</tr> 
		<tr>
			<td colspan="13" style="text-transform:uppercase;text-align:left">amount in words</td>
		</tr>
		<tr>
			<td colspan="13" style="text-align:left;text-transform:capitalize">Rupees <?php echo $jhelper->num2words(number_format($before_tax_amt, 2, '.', '')); ?></td>
		</tr>
		<tr>
			<td colspan="5" style="text-transform:uppercase;text-align:left"><b>Bank Detail</b></td>
			<td colspan="8" style="font-size:8px;text-align:center;vertical-align:middle;">Certified that the perticular given above are true and correct </td>
		</tr>
		<tr>
			<td colspan="5" style="text-transform:uppercase;text-align:left">Bank Name:</td>
			<td colspan="8" style="text-align:center; text-transform:uppercase"><b> sksm diamonds impex limited</b></td>
		</tr>
		<tr>
			<td colspan="5" style="text-transform:uppercase;text-align:left">Bank A/C:</td>
			<td colspan="8" rowspan="3" style="text-align:center;"></td>
		</tr>
		<tr>
			<td colspan="5" style="text-transform:uppercase;text-align:left">Bank IFSC:</td>
		<!--	<td colspan="8" style="text-align:center;"></td> -->
		</tr>
		<tr height="100" class="sign-block">
			<td colspan="5" rowspan="1" style="text-align:left" class="bd"><b>Receiver's Stamp & Signature </b></td>
		</tr>
		<tr>
			<td colspan="5" style="border-top:0"> </td>
			<td colspan="8" style="text-align:center"><b> Authorised signatory </b> </td>
		</tr>
</table>

</page>	

<style>
.sign-block td {
	height:100px;
}
.bd{
	border-bottom:0px;
}
.clr-bdr{
	border-color:e0e0e0;
}
.ttl td{
	height:30px;
	text-align:center;
	vertical-align:middle;
}

</style>	



		
<!--		<tr>
			<td rowspan="3"> we declare that this invoice show the actual price of goods described and that all particulars are true and correct.</td>
			<td colspan="1"> Total Amount Before Tax </td>
			<td>  </td>
		</tr> -->
		
