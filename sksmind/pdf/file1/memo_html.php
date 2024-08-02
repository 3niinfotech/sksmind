
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
				<span style="color:#4991b1;font-size:18px;width:100%;" >Approval Memo</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(ORIGINAL COPY)</span>
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
            <td style="width: 60%;text-align:left;;">
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
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;color:#cc3399">Approval No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
				
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
    $nb = 14;
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
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
            <td style="width: 43%; text-align: left;padding:4px;"><?php echo $record[$i]['shape'].' '.$color.' '.$record[$i]['clarity'].' '.$record[$i]['size']; ?></td>
            <td style="width: 6%; text-align: right;padding:4px;"><?php echo $record[$i]['pcs'];  ?></td>
            <td style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['carat'] , 2, '.', ','); ?> </td>
			<td style="width: 11%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'] , 2, '.', ','); ?></td>
            <td style="width: 13%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'], 2, '.', ','); ?> </td>
        </tr>
		<?php 
			$pcs = $pcs  + (float)$record[$i]['pcs'];
			$carat = $carat  + (float)$record[$i]['carat'];
			//$price = $price  + (float)$record[$i]['price'];
			//$amount = $amount  + (float)$record[$i]['amount'];
			
			$price += (float) ($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'];
			$amount += (float) ($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'];

		else: ?>
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
<?php
    }
?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($pcs, 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($carat, 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">US$</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($amount, 2, '.', ','); ?></th>
        </tr>
		
    </table>
    <br>
	<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;border-top:1px dashed #000;padding-top:10px;">
        <tr>
			<td style="width: 50%;text-align:left;">	
				<p style="font-size: 15px;margin: 0 0 2px;padding-bottom:10px;color:#cc3399">Approval No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
			</td>
		  
		    <td style="width: 50%;">				
				<p style="text-align:right;font-size: 14px;margin: 0 0 2px;color:#cc3399">Receive the above goods as per condition overleaf.</p>
			</td>
        </tr>		
    </table>
    <br><br>
	
	<nobreak>
     <table cellspacing="0" style="width: 100%; ; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Issued By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For <b>Shree International (HK) Ltd.</b></p>
           </td>
			<td style="width: 5%;text-align:left;"></td>
		   <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Delivery  By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For <b>Shree International (HK) Ltd.</b></p>
           </td>
		   <td style="width: 5%;text-align:left;"></td>
		   <td style="width: 30%;text-align: left;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Accepted  By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">Signature & Chop</p>			
			</td>
        </tr>		
    </table>
	<br>
	<p style="text-align:center;text-transform: uppercase;font-size:12px" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACKNOWLEDGEMENT OF ENTRUSTMENT <span style="margin-left:50px; text-align:right; color:#cc3399">Approval No. &nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></span></p>
	<p style="text-align:center;font-size:12px" > We hereby entrust you the overleaf goods for negotiation of Sale / Manufacturing / inspection on approval basis with the following condition.</p>
	<p style="font-size:10px;">The goods described and value as below are delivered to you for examination and inspection only and remain our property subject to our order and shall be returned to us on demand. Such merchandise until returned to us and actually received, are at your risk from all hazards. No right or power is given to you to sell, pledge, hypothecate or otherwise dispose of this merchandise regardless of prior transaction. A sale of this merchandise can only be effected and title will pass only, if as and when we the said owner shall agree to such sale and a bill of sale rendered thereof. These goods may only be sold with the companies permission and have to be returned immediately at our first request. furthermore, the undersigned declares to have his/her own insurance Policy which covers the total value of the goods appearing on this page.</p>
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
				<span style="color:#4991b1;font-size:18px;width:100%;" >Approval Memo</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(CUSTOMER COPY)</span>
            </td>
			<td style="width: 30%;text-align:left;">
				<p style="  font-size: 13px;margin: 0 0 2px;">206, 2/F, Chevalier House,</P>
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
            <td style="width: 60%;text-align:left;;">
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
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;color:#cc3399">Approval No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
				
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
    $nb = 14;
	$pcs = $carat = $price = $amount = 0;
	$record = $data['record'];
	
    for ($i=1; $i<=$nb; $i++) {
       if(isset($record[$i])):
	   
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
            <td style="width: 43%; text-align: left;padding:4px;"><?php echo $record[$i]['shape'].' '.$color.' '.$record[$i]['clarity'].' '.$record[$i]['size']; ?></td>
            <td style="width: 6%; text-align: right;padding:4px;"><?php echo $record[$i]['pcs'];  ?></td>
            <td style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($record[$i]['carat'] , 2, '.', ','); ?> </td>
			<td style="width: 11%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'] , 2, '.', ','); ?></td>
            <td style="width: 13%; text-align: right;padding:4px;"><?php echo number_format(($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'], 2, '.', ','); ?> </td>
        </tr>
		<?php 
			$pcs = $pcs  + (float)$record[$i]['pcs'];
			$carat = $carat  + (float)$record[$i]['carat'];
			//$price = $price  + (float)$record[$i]['price'];
			//$amount = $amount  + (float)$record[$i]['amount'];
			
			$price += (float) ($record[$i]['sell_price'] ==0)?$record[$i]['price']:$record[$i]['sell_price'];
			$amount += (float) ($record[$i]['sell_amount'] ==0)?$record[$i]['amount']:$record[$i]['sell_amount'];

		else: ?>
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
<?php
    }
?>
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:4px;"></th>
            <th style="width: 14%; text-align: left;padding:4px;"></th>
            <th style="width: 43%; text-align: right;padding:4px;">Total</th>
            <th style="width: 6%; text-align: right;padding:4px;"><?php echo number_format($pcs, 0, '.', ','); ?></th>
            <th style="width: 8%; text-align: right;padding:4px;"><?php echo number_format($carat, 2, '.', ','); ?></th>
			<th style="width: 11%; text-align: right;padding:4px;">US$</th>
            <th style="width: 13%; text-align: right;padding:4px;"><?php echo number_format($amount, 2, '.', ','); ?></th>
        </tr>
		
    </table>
    <br>
	<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;border-top:1px dashed #000;padding-top:10px;">
        <tr>
			<td style="width: 50%;text-align:left;">	
				<p style="font-size: 15px;margin: 0 0 2px;padding-bottom:10px;color:#cc3399">Approval No. &nbsp;&nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></p>
			</td>
		  
		    <td style="width: 50%;">				
				<p style="text-align:right;font-size: 14px;margin: 0 0 2px;color:#cc3399">Receive the above goods as per condition overleaf.</p>
			</td>
        </tr>		
    </table>
    <br><br>
	
	<nobreak>
     <table cellspacing="0" style="width: 100%; ; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Issued By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For <b>Shree International (HK) Ltd.</b></p>
           </td>
			<td style="width: 5%;text-align:left;"></td>
		   <td style="width: 30%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Delivery  By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For <b>Shree International (HK) Ltd.</b></p>
           </td>
		   <td style="width: 5%;text-align:left;"></td>
		   <td style="width: 30%;text-align: left;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;color:#cc3399"><b>Accepted  By :</b></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">Signature & Chop</p>			
			</td>
        </tr>		
    </table>
	<br>
	<p style="text-align:center;text-transform: uppercase;font-size:12px" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACKNOWLEDGEMENT OF ENTRUSTMENT <span style="margin-left:50px; text-align:right; color:#cc3399">Approval No. &nbsp;&nbsp;<b style="color:#4991b1;"><?php echo $data['invoiceno'];?></b></span></p>
	<p style="text-align:center;font-size:12px" > We hereby entrust you the overleaf goods for negotiation of Sale / Manufacturing / inspection on approval basis with the following condition.</p>
	<p style="font-size:10px;">The goods described and value as below are delivered to you for examination and inspection only and remain our property subject to our order and shall be returned to us on demand. Such merchandise until returned to us and actually received, are at your risk from all hazards. No right or power is given to you to sell, pledge, hypothecate or otherwise dispose of this merchandise regardless of prior transaction. A sale of this merchandise can only be effected and title will pass only, if as and when we the said owner shall agree to such sale and a bill of sale rendered thereof. These goods may only be sold with the companies permission and have to be returned immediately at our first request. furthermore, the undersigned declares to have his/her own insurance Policy which covers the total value of the goods appearing on this page.</p>
    </nobreak>
</page>
  