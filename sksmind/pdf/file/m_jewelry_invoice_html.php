
<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
-->
</style>

<page style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
   <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px;border-bottom: 1px solid black;">
        <tr>
            
            <td style="width: 20%; color: #444444;text-align: left;">
                <img style="width: 100%;" src="sksm.jpg" alt="Logo"><br>
                
            </td>
			
			<td style="width: 50%;text-align:left;padding-left:10px;">
				<h2 style="margin-top:0px;padding-top:0px;color:#ce2334">SKSM Diamonds Impex Ltd.</h2>
				<p style="  font-size: 13px;margin: 0 0 2px;">9, Ground Floor, Princess Plaza,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Sardar Chowk, Minibazar,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Varachha Road, Surat - 395006</P><br>
				<p style="  font-size: 13px;margin: 0 0 2px;"><b>Contact:</b> 0261 2545551 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> infosksmimpex@gmail.com	</P>
				
            </td>
			<td style="width:30%;text-align:right;">
				<span style="color:#4991b1;font-size:16px;width:100%;" >Sale Invoice</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(ORIGINAL COPY)</span>
            </td>
        </tr>
		
    </table>
	
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 60%;text-align:left;;">
			<p style="margin: 0 0 2px;padding-bottom:4px;color:#ce2334"><b>To.</b>  &nbsp;&nbsp;<b  style="color:#4991b1;font-size:14px;"><?php echo $data['p_name'];?></b></p>
				
				<?php if($data['p_address']!=''): ?><p style="margin: 0 0 2px;padding-bottom:4px;font-size:12px;">&nbsp;<?php echo $data['p_address'];?>,</p><?php endif; ?>
				<p style="font-size:12px;margin: 0 0 2px;padding-bottom:4px;">&nbsp;<?php echo ($data['p_pincode']!='') ?$data['p_pincode'].',':''; ?> <?php echo $data['p_country'];?></p>
				<?php if($data['contact_person']!=''): ?><p style="font-size:12px;margin: 0 0 2px;padding-bottom:4px;">&nbsp;<?php echo $data['contact_person'];?></p><?php endif; ?>
				<p style="margin: 0 0 2px;font-size:12px;"><?php if( $data['p_contact'] != ""):?><b style="color:#ce2334">&nbsp;Tel:</b> <?php echo $data['p_contact'];?>  &nbsp;&nbsp;<?php endif;?> <?php if( $data['p_fax'] != ""):?><b style="color:#ce2334">Fax:</b> <?php echo $data['p_fax'];?><?php endif;?></p>				
           </td>
		   <td style="width: 10%;">
				 			
           </td>
		   <td style="width: 30%;text-align:left;">				
				<p style="padding-bottom:10px;font-size: 16px;margin: 0 0 2px;color:#ce2334">Invoice No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
					
				<p style="font-size: 16px;margin: 0 0 2px;height:30px;">Date. &nbsp;&nbsp;&nbsp;<?php //echo $data['invoicedate'];?> 
				<?php $phpdate = strtotime( $data['date'] );
				echo  date( 'd-m-Y', $phpdate );?>
				</p>
				
			
				
			</td>
        </tr>		
    </table>
    <br><br>
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #ce2334; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 45%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 15%;text-align: center;padding:4px;">Detail</th>
            <th style="width: 15%;text-align: center;padding:4px;">G.GM</th>
			<th style="width: 20%;text-align: center;padding:4px;">Amount INR</th>
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
<?php
    $nb = 18;
	$pcs = $carat = $gram = $gross = $amount = $tsp = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
	    $sku = $record[$i]['sku'];	
	?>
		<tr>
            <td style="border-bottom:0px;width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="border-bottom:0px;width: 45%; text-align: center;padding:4px;"></td>
            <td style="border-bottom:0px;width: 15%; text-align: center;padding:4px;"></td>       
			<td style="border-bottom:0px;width: 15%; text-align: center;padding:4px;"></td>
            <td style="border-bottom:0px;width: 20%; text-align: center;padding:4px;">&nbsp;</td>
        </tr>
		<?php 
			$pcs += $record[$i]['total_pcs'];
		$carat += $record[$i]['total_carat'];
		$gram += $record[$i]['gold_gram'];
		$gross += $record[$i]['gross_weight'];
		$amount += $record[$i]['sell_price'];
		
		else: ?>
		
		<?php if($i==15 && $data['shipping_charge'] !=0 ): ?>
			<tr>
				<td style="border-bottom:0px;width: 5%; text-align: center;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 45%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: right;padding:4px;">Shipping</td>
				<td style="border-bottom:0px;width: 20%; text-align: right;padding:4px;"><?php echo $data['shipping_charge'] ?></td>
			</tr>
		<?php else:?>
				<tr>
				<td style="border-bottom:0px;width: 5%; text-align: center;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 45%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: right;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 20%; text-align: right;padding:4px;">&nbsp;</td>
			</tr>

		<?php endif;?>
		<?php endif;?>
<?php
    }
?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #1b2c60; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 45%; text-align: left;padding:4px;"><b>Total</b></th>
            <th style="width: 15%; text-align: left;padding:4px;"></th>    
			<th style="width: 15%; text-align: right;padding:4px;"><?php //echo number_format($gross, 2, '.', ','); ?></th>
            <th style="width: 20%; text-align: right;padding:4px;"><?php //echo number_format($amount, 2, '.', ','); ?></th>
        </tr>
		
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 50%;text-align:left;;">
				 <p style="margin: 0 0 2px;padding-bottom:2px;color:#ce2334"><b>Payment Detail:</b></p>
			
				<table style="width: 100%;">
					<tr >
						<td style="width:25%;color:#4991b1;">A/c. Name</td>
						<td style="width:75%;">Shree International (HK) Ltd.</td>
					</tr>
					<tr>
						<td style="width:25%;color:#4991b1;">Bank Name</td>
						<td style="width:75%;">DBS BANK (HONG KONG) LTD
						</td>
					</tr>
					<tr>
						<td style="width:25%;color:#4991b1;">Account No</td>
						<td style="width:75%;">000237613 (USD)<br/>
						000237598 (HKD)</td>
					</tr>
					<tr>
						<td style="width:25%;color:#4991b1;">Bank Code</td>
						<td style="width:75%;">016</td>
					</tr>
					<tr>
						<td style="width:25%;color:#4991b1;">Swift Code</td>
						<td style="width:75%;">DHBKHKHH</td>
					</tr>
				</table>
           </td>
		  
		   <td style="width: 50%;">				
				<?php /* <table style="width: 100%;">
					<tr style="padding-bottom:5px;">
						<td style="width:40%;text-align:right;">Payment Terms</td>
						<td style="width:50%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['terms']; ?></td>
						<td style="width:10%;">Days</td>
					</tr>
					<tr >
						<td style="width:40%;text-align:right;"></td>
						<td style="width:50%;"></td>
						<td style="width:10%;"></td>
					</tr>
				</table>
				<table style="width: 100%;">
					<tr>
						<td style="width:40%;text-align:right;">Due Date</td>
						<td style="width:60%;border-bottom:1px dotted #000;text-align:center;">
						<?php  if($data['terms']!=0): 
						$duedate = $data['duedate'];
						else:
						$duedate = $data['invoicedate'];
						endif; 
						$phpdate = strtotime( $duedate );
						echo  date( 'd-m-Y', $phpdate );
						?></td>						
					</tr>
					<tr>
						<td style="width:40%;text-align:right;"></td>
						<td style="width:60%;"></td>						
					</tr>
				</table>	
				<table style="width: 100%;">	
					<tr style="padding-bottom:5px;">
						<td style="width:40%;text-align:right;">Received Amount</td>
						<td style="width:50%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['paid_amount']; ?></td>
						<td style="width:10%;">US$</td>
					</tr>	
					<tr >
						<td style="width:40%;text-align:right;"></td>
						<td style="width:50%;"></td>
						<td style="width:10%;"></td>
					</tr>					
				</table>
				<?php if($data['due_amount'] > 0 ):?>
				<table style="width: 100%;">	
					<tr style="padding-bottom:5px;">
						<td style="width:40%;text-align:right;">Remain Amount</td>
						<td style="width:50%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['due_amount']; ?></td>
						<td style="width:10%;">US$</td>
					</tr>	
						
				</table>
				<?php endif; ?>
				<?php if($data['shipping_name'] !="" ):?>
				<table style="width: 100%;">	
					<tr>
						<td style="width:40%;text-align:right;">Shipping </td>
						<td style="width:60%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['shipping_name'] ?></td>						
					</tr>					
				</table>
				<?php endif; ?>
				<?php if($data['origin_of'] !="" ):?>
				<table style="width: 100%;">	
					<tr>
						<td style="width:40%;text-align:right;">Origin Of </td>
						<td style="width:60%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['origin_of'] ?></td>						
					</tr>					
				</table>
				<?php endif; */?>
			</td>
        </tr>		
    </table>
    <br>
	<br>	
    <nobreak>
       <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#ce2334"><b>Confirmed By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Chop & Buyer's Signature)</p>
           </td>	
			<td style="width: 35%;text-align:left;"></td>
		   <td style="width: 35%;text-align: right;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#ce2334">For <b>SKSM Diamonds Impex Ltd.</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Authorised Signature)</p>			
			</td>
        </tr>		
    </table>
	<p style="font-size:10px;">The Diamond herein invoiced have been purchased  from legitimate sources not involved in funding conflict and in compliance with United Nation resolutions. The seller hereby guarantees that these diamonds are conflict free, based on personal knowledge and / or written guarantees provided by the supplier of these diamonds.</p>
    </nobreak>
</page>

<page style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
   <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px;border-bottom: 1px solid black;">
        <tr>
            
            <td style="width: 20%; color: #444444;text-align: left;">
                <img style="width: 100%;" src="sksm.jpg" alt="Logo"><br>
                
            </td>
			
			<td style="width: 50%;text-align:left;padding-left:10px;">
				<h2 style="margin-top:0px;padding-top:0px;color:#ce2334">SKSM Diamonds Impex Ltd.</h2>
				<p style="  font-size: 13px;margin: 0 0 2px;">9, Ground Floor, Princess Plaza,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Sardar Chowk, Minibazar,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Varachha Road, Surat - 395006</P><br>
				<p style="  font-size: 13px;margin: 0 0 2px;"><b>Contact:</b> 0261 2545551 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> infosksmimpex@gmail.com	</P>
				
            </td>
			<td style="width:30%;text-align:right;">
				<span style="color:#4991b1;font-size:16px;width:100%;" >Sale Invoice</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(ORIGINAL COPY)</span>
            </td>
        </tr>
		
    </table>
	<hr style="width:100%;height:1px;color:#1b2c60">
	<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 11px;text-align:Center"><b style="color:#1b2c60;font-size: 13px;"> PACKING LIST </b></p>
	<hr style="width:100%;height:1px;color:#1b2c60">
    <br>
	
    
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #ce2334; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 45%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 15%;text-align: center;padding:4px;">Detail</th>
            <th style="width: 15%;text-align: center;padding:4px;">G.GM</th>
			<th style="width: 20%;text-align: center;padding:4px;">Amount INR</th>
        </tr>
    </table>
	 <table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
<?php
    $nb = count($data['record']);
	$pcs = $carat = $gram = $gross = $amount = $tsp = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
	    $sku = $record[$i]['sku'];
		
		
	?>
		<tr>
            <td style="border-bottom:0px;width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="border-bottom:0px;width: 45%; text-align: center;padding:4px;"><?php echo $sku; ?></td>
            <td style="border-bottom:0px;width: 15%; text-align: center;padding:4px;"><?php echo $record[$i]['jew_design'] ?></td>       
			<td style="border-bottom:0px;width: 15%; text-align: center;padding:4px;"><?php echo number_format($record[$i]['gross_weight'] , 2, '.', ','); ?></td>
            <td style="border-bottom:0px;width: 20%; text-align: center;padding:4px;"><?php echo number_format($record[$i]['sell_price'], 2, '.', ','); ?> </td>
        </tr>
		<?php 
		$pcs += $record[$i]['total_pcs'];
		$carat += $record[$i]['total_carat'];
		$gram += $record[$i]['gold_gram'];
		$gross += $record[$i]['gross_weight'];
		$amount += $record[$i]['sell_price'];
		else: ?>
		
			<tr>
				<td style="border-bottom:0px;width: 5%; text-align: center;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 45%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: left;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 15%; text-align: right;padding:4px;">&nbsp;</td>
				<td style="border-bottom:0px;width: 20%; text-align: right;padding:4px;">&nbsp;</td>
			</tr>		
		<?php endif;?>
<?php
    }
?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 45%; text-align: left;padding:4px;"><b>Total</b></th>
            <th style="width: 15%; text-align: left;padding:4px;"></th>    
			<th style="width: 15%; text-align: right;padding:4px;"><?php echo number_format($gross, 2, '.', ','); ?></th>
            <th style="width: 20%; text-align: right;padding:4px;"><?php echo number_format($amount, 2, '.', ','); ?></th>
        </tr>
		
    </table>
    
</page>
