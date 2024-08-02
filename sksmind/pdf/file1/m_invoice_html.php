
<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
-->
</style>

<page style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
        <tr>
            
            <td style="width: 30%; color: #444444;">
                <img style="width: 100%;" src="logo.png" alt="Logo"><br>
                
            </td>
			<td style="width: 40%;text-align:center;">
				<span style="color:#4991b1;font-size:18px;" >Invoice</span>
            </td>
			<td style="width: 30%;text-align:left;">
				<p style="  font-size: 13px;margin: 0 0 2px;">Unit-206, 2/F, Chevalier House,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">45-51 Chatham Road South,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Tsim Sha Tsui, Kowloon, Hong Kong.</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Tel:(+852) 2366-6047</P>				
				<p style="  font-size: 13px;margin: 0 0 2px;">E-Mail:shreeintlhk@gmail.com</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Web:www.shreehk.com</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Skype ID:shreeintl.hk</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Rapnet ID: 91552</p>
            </td>
        </tr>
		
    </table>
	<hr style="width:100%;height:1px;color:#ccc">
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
          <td style="width: 60%;text-align:left;">
			<p style="margin: 0 0 2px;padding-bottom:2px;color:#cc3399"><b>To.</b></p>
				<p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_name'];?></p>
				<?php if($data['p_address']!=''): ?><p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_address'];?>,</p><?php endif; ?>
				<p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo ($data['p_pincode']!='') ?$data['p_pincode'].',':''; ?> <?php echo $data['p_country'];?></p>
				<?php if($data['contact_person']!=''): ?><p style="margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['contact_person'];?></p><?php endif; ?>
				<p style="margin: 0 0 2px;"><?php if( $data['p_contact'] != ""):?><b style="color:#cc3399">Tel:</b> <?php echo $data['p_contact'];?>  &nbsp;&nbsp;<?php endif;?> <?php if( $data['p_fax'] != ""):?><b style="color:#cc3399">Fax:</b> <?php echo $data['p_fax'];?><?php endif;?></p>				
           </td>
		   <td style="width: 10%;">
				 			
           </td>
		   <td style="width: 30%;text-align:left;">				
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;height:30px;">Date. &nbsp;&nbsp;&nbsp;<?php //echo $data['invoicedate'];?> 
				<?php $phpdate = strtotime( $data['invoicedate'] );
				echo  date( 'd-m-Y', $phpdate );?></p>
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;color:#cc3399">Reference No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['reference'];?></b></p>
				<p style="font-size: 16px;margin: 0 0 2px;color:#cc3399">Invoice No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
				
			</td>
        </tr>		
    </table>
    <br><br>
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #cc3399; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 14%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 43%;text-align: center;padding:4px;">Description</th>
            <th style="width: 6%;text-align: center;padding:4px;">PCS</th>
            <th style="width: 8%;text-align: center;padding:4px;">Carats</th>
			<th style="width: 11%;text-align: center;padding:4px;">Price(US$)</th>
			<th style="width: 13%;text-align: center;padding:4px;">Amount(US$)</th>
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
<?php
    $nb = 15;
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if($i == 1 ):
	   
	    if($record[$i]['outward_parent'] == 0)
		{
			$sku = $record[$i]['sku'];
		}
		else
		{
			$parentData = $helper->getProductDetail ($record[$i]['outward_parent']);					
			$sku = $parentData['sku'];
		}
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
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($data['pcs'], 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($data['carat'], 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">US$</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'], 2, '.', ','); ?></th>
        </tr>
		
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 50%;text-align:left;;">
				 <p style="margin: 0 0 2px;padding-bottom:2px;color:#cc3399"><b>Payment Detail:</b></p>
			
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
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Confirmed By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Chop & Buyer's Signature)</p>
           </td>	
			<td style="width: 35%;text-align:left;"></td>
		   <td style="width: 35%;text-align: right;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399">For <b>Shree International (HK) Ltd.</b></p>
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
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
        <tr>
            
            <td style="width: 30%; color: #444444;">
                <img style="width: 100%;" src="logo.png" alt="Logo"><br>
                
            </td>
			<td style="width: 40%;text-align:center;">
				<span style="color:#4991b1;font-size:18px;" >Packing List</span>
            </td>
			<td style="width: 30%;text-align:left;">
				<p style="  font-size: 13px;margin: 0 0 2px;">Unit-206, 2/F, Chevalier House,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">45-51 Chatham Road South,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Tsim Sha Tsui, Kowloon, Hong Kong.</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Tel:(+852) 2366-6047</P>				
				<p style="  font-size: 13px;margin: 0 0 2px;">E-Mail:shreeintlhk@gmail.com</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Web:www.shreehk.com</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Skype ID:shreeintl.hk</p>
				<p style="  font-size: 13px;margin: 0 0 2px;">Rapnet ID: 91552</p>
            </td>
        </tr>
		
    </table>
	<hr style="width:100%;height:1px;color:#ccc">
    <br>
	
    
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #cc3399; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:4px;">No.</th>
            <th style="width: 14%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 43%;text-align: center;padding:4px;">Description</th>
            <th style="width: 6%;text-align: center;padding:4px;">PCS</th>
            <th style="width: 8%;text-align: center;padding:4px;">Carats</th>
			<th style="width: 11%;text-align: center;padding:4px;">Price(US$)</th>
			<th style="width: 13%;text-align: center;padding:4px;">Amount(US$)</th>
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
	<?php
    $nb = count($data['record']);
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) 
	{
       
	    if($record[$i]['outward_parent'] == 0)
		{
			$sku = $record[$i]['sku'];
		}
		else
		{
			$parentData = $helper->getProductDetail ($record[$i]['outward_parent']);					
			$sku = $parentData['sku'];
		}
		
		$color = ($record[$i]['main_color'] !="" ) ? $record[$i]['main_color']:$record[$i]['color'];
	?>
		<tr>
            <td style="width: 5%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="width: 14%; text-align: left;padding:4px;"><?php echo $sku; ?></td>
            <td style="width: 43%; text-align: left;padding:4px;font-size:11px;"><?php echo $record[$i]['shape'].' '.$color.' '.$record[$i]['clarity'].' '.$record[$i]['size']; 
			echo ($record[$i]['report_no'] != '')?' GIA : '.$record[$i]['report_no']:''; ?></td>
            <td style="width: 6%; text-align: right;padding:4px;"><?php echo $record[$i]['pcs'];  ?></td>
            <td style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['carat'] , 2, '.', ','); ?> </td>
			<td style="width: 11%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'] , 2, '.', ','); ?></td>
            <td style="width: 13%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'], 2, '.', ','); ?> </td>
        </tr>
	<?php } ?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($data['pcs'], 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($data['carat'], 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">US$</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($data['final_amount'], 2, '.', ','); ?></th>
        </tr>
		
    </table>
    
</page>
