
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
            
            <td style="width: 10%; color: #444444;text-align: left;">
                <img style="width: 100%;" src="sksm.jpg" alt="Logo"><br>
                
            </td>
			
			<td style="width: 60%;text-align:left;padding-left:10px;">
				<h2 style="margin-top:0px;padding-top:0px;color:#ce2334">SKSM Diamonds Impex Ltd.</h2>
				<p style="  font-size: 13px;margin: 0 0 2px;">9, Ground Floor, Princess Plaza,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Sardar Chowk, Minibazar,</P>
				<p style="  font-size: 13px;margin: 0 0 2px;">Varachha Road, Surat - 395006</P><br>
				<p style="  font-size: 13px;margin: 0 0 2px;"><b>Contact:</b> 0261 2545551 &nbsp;&nbsp;&nbsp;<b>E-mail:</b> infosksmimpex@gmail.com	</P>
				
            </td>
			<td style="width:30%;text-align:right;">
				<span style="color:#4991b1;font-size:16px;width:100%;" >Consignment</span>
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
				<p style="padding-bottom:10px;font-size: 16px;margin: 0 0 2px;color:#ce2334">Consignment No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
					
				<p style="font-size: 16px;margin: 0 0 2px;height:30px;">Date. &nbsp;&nbsp;&nbsp;<?php //echo $data['invoicedate'];?> 
				<?php $phpdate = strtotime( $data['date'] );
				echo  date( 'd-m-Y', $phpdate );?>
				</p>
				
			
				
			</td>
        </tr>		
    </table>
    <br>
   
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #ce2334; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 26%;text-align: center; padding:4px;">Item / Description</th>
            <th style="width: 8%;text-align: center; padding:4px;">Gross Weight</th>
            <th style="width: 23%;text-align: center;padding:4px;">Gold</th>
            <th style="width: 23%;text-align: center;padding:4px;">Diamond</th>
            <th style="width: 10%;text-align: center;padding:4px;">Labour</th>
            <th style="width: 10%;text-align: center;padding:4px;">Amount</th>
        </tr>
    </table>
	<table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #ce2334; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 4%;text-align: center; padding:4px;">No.</th>
            <th style="width: 11%;text-align: center;padding:4px;">SKU</th>
            <th style="width: 11%;text-align: center;padding:4px;">Type</th>
            <th style="width: 8%;text-align: center;padding:4px;font-size:11px;">Gold +Carat</th>
            <th style="width: 6%;text-align: center;padding:4px;">Gram</th>            
			<th style="width: 7%;text-align: center;padding:4px;">Rate</th>
			<th style="width: 10%;text-align: center;padding:4px;">Amount</th>
			<th style="width: 6%;text-align: center;padding:4px;">Carat</th>            
			<th style="width: 7%;text-align: center;padding:4px;">Rate</th>
			<th style="width: 10%;text-align: center;padding:4px;">Amount</th>
			<th style="width: 10%;text-align: center;padding:4px;">Charge</th>
			<th style="width: 10%;text-align: center;padding:4px;">US$</th>
        </tr>
    </table>
	 <table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 10pt;">
<?php
    $nb = 12;
	$pcs = $carat = $gram = $gross = $ga = $amount = $ca = 0 ;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
	    $sku = $record[$i]['sku'];
		
	$rate = 	$record[$i]['total_amount'] / $record[$i]['total_carat'];
	?>
		<tr>
            <td style="border-bottom:0px;width: 4%; text-align: center;padding:4px;"><?php echo $i; ?></td>
            <td style="border-bottom:0px;width: 11%; text-align: left;padding:4px;"><?php echo $sku; ?></td>
            <td style="border-bottom:0px;width: 11%; text-align: left;padding:4px;"><?php echo ucfirst($record[$i]['jew_type']) ?></td>                        
			<td style="border-bottom:0px;width: 8%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['gross_cts'] , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 6%; text-align: right;padding:4px;"><?php echo $record[$i]['gold_gram']; ?></td>
			<td style="border-bottom:0px;width: 7%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['gold_price'] , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['gold_amount'] , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 6%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['total_carat'] , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 7%; text-align: right;padding:4px;"><?php echo number_format($rate , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['total_amount'] , 2, '.', ','); ?></td>
			<td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['labour_fee'] , 2, '.', ','); ?></td>
            <td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['final_cost'], 2, '.', ','); ?> </td>
        </tr>
		<?php 
		$pcs += $record[$i]['total_pcs'];
		$carat += $record[$i]['total_carat'];
		$ca += $record[$i]['total_amount'];
		$gram += $record[$i]['gold_gram'];
		$ga += $record[$i]['gold_amount'];
		$gross += $record[$i]['gross_cts'];
		$amount += $record[$i]['final_cost'];
		else: ?>
		
			<tr>
				<td style="border-bottom:0px;width: 4%; text-align: center;padding:4px;"> &nbsp;<?php //echo $i; ?></td>
				<td style="border-bottom:0px;width: 11%; text-align: left;padding:4px;"></td>
				<td style="border-bottom:0px;width: 11%; text-align: left;padding:4px;"></td>
				<td style="border-bottom:0px;width: 8%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 6%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 7%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 10%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 6%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 7%; text-align: left;padding:4px;"></td>				
				<td style="border-bottom:0px;width: 10%; text-align: left;padding:4px;"></td>				

				<td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"></td>
				<td style="border-bottom:0px;width: 10%; text-align: right;padding:4px;"></td>
			</tr>		
		<?php endif;?>
<?php
    }
?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 4%; text-align: center;padding:4px;"></th>
            <th style="width: 11%; text-align: left;padding:4px;"></th>
            <th style="width: 11%; text-align: left;padding:4px;"></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($gross, 2, '.', ','); ?></th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($gram, 2, '.', ','); ?></th>            
			<th style="width: 7%; text-align: right;padding:4px;"></th>
			<th style="width: 10%; text-align: right;padding:4px;"><?php echo number_format($ga, 2, '.', ','); ?></th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($carat, 2, '.', ','); ?></th>            
			<th style="width: 7%; text-align: right;padding:4px;"><?php //echo number_format($gross, 2, '.', ','); ?></th>
			<th style="width: 10%; text-align: right;padding:4px;"><?php echo number_format($ca, 2, '.', ','); ?></th>
            <th style="width: 10%; text-align: right;padding:4px;"><?php //echo number_format($amount, 2, '.', ','); ?></th>
            <th style="width: 10%; text-align: right;padding:4px;"><?php echo number_format($amount, 2, '.', ','); ?></th>
        </tr>
		
    </table>
	<br>
    <nobreak>
       <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:25px;color:#ce2334"><b>Confirmed By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Chop & Buyer's Signature)</p>
           </td>	
			<td style="width: 35%;text-align:left;"></td>
		   <td style="width: 35%;text-align: right;">				
				<p style="margin: 0 0 2px;padding-bottom:25px;color:#ce2334">For <b>SKSM Diamonds Impex Ltd.</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">(Authorised Signature)</p>			
			</td>
        </tr>		
    </table>
	<p style="font-size:10px;">The Diamond herein invoiced have been purchased  from legitimate sources not involved in funding conflict and in compliance with United Nation resolutions. The seller hereby guarantees that these diamonds are conflict free, based on personal knowledge and / or written guarantees provided by the supplier of these diamonds.</p>
    </nobreak>
</page>
