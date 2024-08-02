
<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
-->
</style>

<page style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
   <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
            
            <td style="width: 20%; color: #444444;">
                <img style="width: 100px;" src="sksm.jpg" alt="Logo">
                
            </td>
			<td style="width: 60%;text-align:center;">
				<h2 style="padding:1px; color:#000;padding:0px;padding-top:5px;padding-bottom:5px; color:#1b2c60;width:100%;margin:0px;font-size:26px;">SKSM DIA JEWELS LIMITED</h2>
				<p style="width:100%; padding:0px;padding-top:10px; font-size: 12px;margin:0px;">Unit-206, 2/F, Chevalier House,45-51 Chatham Road South,Tsim Sha Tsui, <br>Kowloon, Hong Kong.<br></p>
				<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 12px;"><b>Contact:</b> (+852) 2366-6047 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> sksmdiajewels@gmail.com</p>
				<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 12px;"><b>Website:</b> www.sksmdiamonds.com </p>
				
            </td>
			
			<td style="width: 20%;text-align:right;">
			  <img style="width: 60px;" src="logo-new.png" alt="Logo"><br>			
            </td>
        </tr>
		
    </table>
	<hr style="width:100%;height:1px;color:#1b2c60">
	<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 11px;text-align:Center"><b style="color:#1b2c60;font-size: 13px;"> INVOICE </b>[Original Copy]  </p>
	<hr style="width:100%;height:1px;color:#1b2c60">
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
          <td style="width: 60%;text-align:left;">
			<p style="margin: 0 0 2px;padding-bottom:2px;color:#1b2c60"><b>To.</b></p>
				<p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_name'];?></p>
				<?php if($data['p_address']!=''): ?><p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_address'];?>,</p><?php endif; ?>
				<p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo ($data['p_pincode']!='') ?$data['p_pincode'].',':''; ?> <?php echo $data['p_country'];?></p>
				<?php if($data['contact_person']!=''): ?><p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['contact_person'];?></p><?php endif; ?>
				<p style="margin: 0 0 2px;"><?php if( $data['p_contact'] != ""):?><b style="color:#1b2c60">Tel:</b> <?php echo $data['p_contact'];?>  &nbsp;&nbsp;<?php endif;?> <?php if( $data['p_fax'] != ""):?><b style="color:#1b2c60">Fax:</b> <?php echo $data['p_fax'];?><?php endif;?></p>				
           </td>
		   <td style="width: 10%;">
				 			
           </td>
		   <td style="width: 30%;text-align:left;">				
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;height:30px;">Date. &nbsp;&nbsp;&nbsp;<?php //echo $data['invoicedate'];?> 
				<?php $phpdate = strtotime( $data['invoicedate'] );
				echo  date( 'd-m-Y', $phpdate );?></p>
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;color:#1b2c60">Reference No. &nbsp;&nbsp;&nbsp;<b style="color:#1b2c60;"><?php echo $data['reference'];?></b></p>
				<p style="font-size: 16px;margin: 0 0 2px;color:#1b2c60">Invoice No. &nbsp;&nbsp;&nbsp;<b style="color:#1b2c60;"><?php echo $data['invoiceno'];?></b></p>
				
			</td>
        </tr>		
    </table>
    <br><br>
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #1b2c60; text-align: center; font-size: 10pt;">
       <tr style="background-color: #1b2c60;color:#fff;border-color:#fff;">
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 14%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 43%;text-align: center;padding:4px;">Description</th>
            <th style="width: 6%;text-align: center;padding:4px;">PCS</th>
            <th style="width: 8%;text-align: center;padding:4px;">Carats</th>
			<th style="width: 11%;text-align: center;padding:4px;">Price(INR)</th>
			<th style="width: 13%;text-align: center;padding:4px;">Amount(INR)</th>
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
<?php
    $nb = 15;
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if($i == 1 ):	   
	   $sku = $record[$i]['sku'];		
	?>
		<tr>
            <td style="width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="width: 14%; text-align: left;padding:4px;"><?php //echo $sku; ?></td>
            <td style="width: 43%; text-align: left;padding:4px;">Cut and Polish Diamond</td>
            <td style="width: 6%; text-align: right;padding:4px;"><?php echo $data['pcs'];  ?></td>
            <td style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($data['carat'] , 2, '.', ','); ?> </td>
			<td style="width: 11%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'] / $data['carat']  , 2, '.', ','); ?></td>
            <td style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'], 2, '.', ','); ?> </td>
        </tr>
		<?php 
			$pcs = $pcs  + (float)$record[$i]['pcs'];
			$carat = $carat  + (float)$record[$i]['carat'];
			$price = $price  + (float)$record[$i]['price'];
			$amount = $amount  + (float)$record[$i]['amount'];
		
		else: ?>
		
		<?php if($i==15 && $data['shipping_charge'] !=0 ): ?>
			<tr>
				<td style="width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
				<td style="width: 14%; text-align: left;padding:4px;"></td>
				<td style="width: 43%; text-align: left;padding:4px;"></td>
				<td style="width: 6%; text-align: right;padding:4px;"></td>
				<td style="width: 8%; text-align: right;padding:4px;"></td>
				<td style="width: 11%; text-align: right;padding:4px;">Shipping</td>
				<td style="width: 13%; text-align: right;padding:4px;"><?php echo $data['shipping_charge'] ?></td>
			</tr>
		<?php else:?>
				<tr>
				<td style="width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
				<td style="width: 14%; text-align: left;padding:4px;"></td>
				<td style="width: 43%; text-align: left;padding:4px;"></td>
				<td style="width: 6%; text-align: right;padding:4px;"></td>
				<td style="width: 8%; text-align: right;padding:4px;"></td>
				<td style="width: 11%; text-align: right;padding:4px;"></td>
				<td style="width: 13%; text-align: right;padding:4px;"></td>
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
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($data['pcs'], 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($data['carat'], 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">IRN</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'], 2, '.', ','); ?></th>
        </tr>
		
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
          <td style="width: 50%;text-align:left;;">
				 <p style="margin: 0 0 2px;padding-bottom:2px;color:#1b2c60"><b>Payment Detail:</b></p>
			
				<table style="width: 100%;">
					<tr >
						<td style="width:25%;color:#1b2c60;">A/c. Name</td>
						<td style="width:75%;">SKSM DIA JEWELS LIMITED</td>
					</tr>
					<tr>
						<td style="width:25%;color:#1b2c60;">Bank Name</td>
						<td style="width:75%;">Public bank (Hongkong)Limited
						</td>
					</tr>
					<tr>
						<td style="width:25%;color:#1b2c60;">Account No</td>
						<td style="width:75%;">0754135716026 (USD)<br/> 0754135716031 (HKD)
						</td>
					</tr>
					<tr>
						<td style="width:25%;color:#1b2c60;">Bank Code</td>
						<td style="width:75%;">028</td>
					</tr>
					<tr>
						<td style="width:25%;color:#1b2c60;">Swift Code</td>
						<td style="width:75%;">CBHKHKHH</td>
					</tr>
				</table>
           </td>
		  
		   <td style="width: 50%;">				
				<table style="width: 100%;">
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
					<tr>
						<td style="width:40%;text-align:right;">Received Amount</td>
						<td style="width:60%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['paid_amount'] ?></td>						
					</tr>	
					<tr>
						<td style="width:40%;text-align:right;"></td>
						<td style="width:60%;"></td>						
					</tr>					
				</table>
				<?php if($data['due_amount'] > 0 ):?>
				<table style="width: 100%;">	
					<tr>
						<td style="width:40%;text-align:right;">Remain Amount</td>
						<td style="width:60%;border-bottom:1px dotted #000;text-align:center;"><?php echo $data['due_amount'] ?></td>						
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
				<?php endif; ?>
			</td>
        </tr>		
    </table>
    <br>
	<br>	
    <nobreak>
       <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#1b2c60"><b>Confirmed By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Chop & Buyer's Signature)</p>
           </td>	
			<td style="width: 35%;text-align:left;"></td>
		   <td style="width: 35%;text-align: right;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#1b2c60">For <b>SKSM DIA JEWELS LIMITED</b></p>
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
   <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
            
            <td style="width: 20%; color: #444444;">
                <img style="width: 100px;" src="sksm.jpg" alt="Logo">
                
            </td>
			<td style="width: 60%;text-align:center;">
				<h2 style="padding:1px; color:#000;padding:0px;padding-top:5px;padding-bottom:5px; color:#1b2c60;width:100%;margin:0px;font-size:26px;">SKSM DIA JEWELS LIMITED</h2>
				<p style="width:100%; padding:0px;padding-top:10px; font-size: 12px;margin:0px;">Unit-206, 2/F, Chevalier House,45-51 Chatham Road South,Tsim Sha Tsui, <br>Kowloon, Hong Kong.<br></p>
				<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 12px;"><b>Contact:</b> (+852) 2366-6047 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> sksmdiajewels@gmail.com</p>
				<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 12px;"><b>Website:</b> www.sksmdiamonds.com </p>
				
            </td>
			
			<td style="width: 20%;text-align:right;">
			  <img style="width: 60px;" src="logo-new.png" alt="Logo"><br>			
            </td>
        </tr>
		
    </table>
	<hr style="width:100%;height:1px;color:#1b2c60">
	<p  style="width:100%;padding:0px;margin:0px;padding-top:2px; font-size: 11px;text-align:Center"><b style="color:#1b2c60;font-size: 13px;"> PACKING LIST </b></p>
	<hr style="width:100%;height:1px;color:#1b2c60">
    <br>
	
    
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #1b2c60; text-align: center; font-size: 10pt;">
        <tr style="background-color: #1b2c60;color:#fff;border-color:#fff;">
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 14%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 43%;text-align: center;padding:4px;">Description</th>
            <th style="width: 6%;text-align: center;padding:4px;">PCS</th>
            <th style="width: 8%;text-align: center;padding:4px;">Carats</th>
			<th style="width: 11%;text-align: center;padding:4px;">Price(INR)</th>
			<th style="width: 13%;text-align: center;padding:4px;">Amount(INR)</th>
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
	<?php
    $nb = count($data['record']);
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) 
	{
       
	   $sku = $record[$i]['sku'];
		
		
		$color = $record[$i]['color'];
	?>
		<tr>
            <td style="width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="width: 14%; text-align: left;padding:4px;"><?php echo $sku; ?></td>
            <td style="width: 43%; text-align: left;padding:4px;font-size:11px;"><?php echo $record[$i]['shape'].' '.$color.' '.$record[$i]['clarity'] ?></td>
            <td style="width: 6%; text-align: right;padding:4px;"><?php echo $record[$i]['pcs'];  ?></td>
            <td style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['carat'] , 2, '.', ','); ?> </td>
			<td style="width: 11%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'] , 2, '.', ','); ?></td>
            <td style="width: 13%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'], 2, '.', ','); ?> </td>
        </tr>
	<?php } ?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #1b2c60; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($data['pcs'], 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($data['carat'], 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">IRN</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'], 2, '.', ','); ?></th>
        </tr>
		
    </table>
    
</page>
