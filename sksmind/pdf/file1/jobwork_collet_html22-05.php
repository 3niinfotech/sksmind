
<page style="font-size: 12pt">
    
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
				<span style="color:#4991b1;font-size:16px;width:100%;" >Jobwork Memo</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(ORIGINAL COPY)</span>
            </td>
        </tr>
		
    </table>
	
		<table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px;padding-top: 10px;">
        <tr>
            <td style="width: 60%;text-align:left;;">
			<p style="margin: 0 0 2px;padding-bottom:2px;color:#cc3399"><b>To.</b></p>
				<p style="font-size:16px; margin: 0 0 2px;padding-bottom:2px;"><b><?php echo $data['p_name']; ?></b>
				</p>
				<p style="font-size:13px;margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_address']; ?></p>
				<p style="font-size:13px;margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_pincode']; ?></p>
				<p style="font-size:13px;margin: 0 0 2px;padding-bottom:2px;"><?php echo $data['p_country']; ?></p>
				<p style="margin: 0 0 2px;font-size:13px;"><b style="color:#cc3399">Tel: </b><?php echo $data['p_contact']; ?> &nbsp; &nbsp;<b style="color:#cc3399"> Fax: </b><?php echo $data['p_fax']; ?></p>				
           </td>
		   <td style="width: 15%;"></td>
		   <td style="width: 25%;text-align:left;">	
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;"><b style="color:#cc3399">Job Memo : &nbsp;&nbsp;&nbsp;</b><?php echo $data['entryno']; ?></p>
				<?php $phpdate = strtotime( $data['date'] );?>
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;height:30px;"><b style="color:#cc3399">Date : &nbsp;&nbsp;&nbsp;</b><?php echo  date( 'd-m-Y', $phpdate ); ?></p>	
			</td>
        </tr>		
    </table>
	<br>
	<?php if($data['job']  =='collet'): ?>
		<h4 style="text-align:center;color:#4991b1;">Collet Making</h4>
	<?php else: ?>
		<h4 style="text-align:center;color:#4991b1;">Collet Repairing</h4>
	<?php endif; ?>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #cc3399; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:3px;">No.</th>
			<th style="width: 16%;text-align: center;padding:3px;">SKU</th>
            <th style="width: 16%;text-align: center;padding:3px;">Color</th>
            <th style="width: 16%;text-align: center;padding:3px;">Shape</th>
            <th style="width: 16%;text-align: center;padding:3px;">Clarity</th>
            <th style="width: 10%;text-align: center;padding:3px;">PCS</th>
            <th style="width: 10%;text-align: center;padding:3px;">Carats</th>			
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 12px;">
	<?php 
	$tpc = $tc = 0;
	$i=0; foreach($data['main_stone'] as $pdata): $i++;
	$tpc += $pdata['pcs'];
	$tc += $pdata['carat'];
	?>
		<tr>
            <td style="width: 5%;height: 4px; text-align: center;padding:3px;"><?php echo $i; ?></td>
			<td style="width: 16%;height: 4px; text-align: left;padding:3px;"><?php echo $pdata['sku']; ?> </td>
            <td style="width: 16%;height: 4px; text-align: left;padding:3px;"><?php echo $pdata['color']; ?></td>
            <td style="width: 16%;height: 4px; text-align: left;padding:3px;"><?php echo $pdata['shape']; ?></td>
            <td style="width: 16%;height: 4px; text-align: left;padding:3px;"> <?php echo $pdata['clarity']; ?></td>
			<td style="width: 10%;height: 4px; text-align: right;padding:3px;"><?php echo $pdata['pcs']; ?></td>
            <td style="width: 10%;height: 4px; text-align: right;padding:3px;"><?php echo $pdata['carat']; ?></td>
           
        </tr>
	<?php endforeach;?>	
		
	 </table>
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #4991b1; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%; text-align: center;padding:3px;"></th>
			<th style="width: 16%; text-align: left;padding:3px;"></th>
            <th style="width: 16%; text-align: right;padding:3px;">Total</th>
            <th style="width: 16%; text-align: right;padding:3px;"></th>
            <th style="width: 16%; text-align: right;padding:3px;"></th>
			<th style="width: 10%; text-align: right;padding:3px;"><?php echo $tpc; ?></th>
            <th style="width: 10%; text-align: right;padding:3px;"><?php echo $tc; ?></th>            
        </tr>
		
    </table>
    <br><br>
	
	<?php if($data['job']  =='collet_repair'): ?>
		<h5 style="text-align:left;color:#ce2334;">Note : <?php echo $data['narretion'] ?></h5>	
	<?php endif; ?>


<table cellspacing="0" style="width: 100%;text-align: left; font-size: 
	14px;padding-top:4px;">
	<tr>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Gold Carat:
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['gold']; ?></b>
					</td>
				</tr>
		</table>
	</td>
	<td  style="width: 10%;"></td>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Handling Charge :
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['handling_charge']; ?></b>
					</td>
				</tr>
		</table>
	</td>
	</tr>
	</table>

<table cellspacing="0" style="width: 100%;text-align: left; font-size: 
	14px;padding-top:4px;">
	<tr>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Gold Color:
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['gold_color']; ?></b>
					</td>
				</tr>
		</table>
	</td>
	<td  style="width: 10%;"></td>
	<td  style="width: 45%;">
			<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Labour Charge
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['labour_charge']; ?></b>
					</td>
				</tr>
		</table>
	
	</td>
		
	</tr>
	</table>	
	
	<table cellspacing="0" style="width: 100%;text-align: left; font-size: 
	14px;padding-top:4px;">
	<tr>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Gold Weight:
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['gold_carat']; ?>(gram)</b>
					</td>
				</tr>
		</table>
	</td>
	<td  style="width: 10%;"></td>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Delivery Date
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;">
						<?php if($data['duedate'] != '0000-00-00'):?>
						<?php $phpdate = strtotime( $data['duedate'] );?>
						<?php echo date( 'd-m-Y', $phpdate );; ?></b>
						<?php endif; ?>
					</td>
				</tr>
		</table>
	</td>
	</tr>
	</table>	
	
	<table cellspacing="0" style="width: 100%;text-align: left; font-size: 
	14px;padding-top:4px;">
	<tr>
	<td  style="width: 45%;">
		<table cellspacing="0" style="width: 100%;text-align: left; font-size: 14px;padding-top:4px;">
				<tr>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:left;">
						Gold Rate
					</td>
					<td style="border-bottom:1px solid #999;width: 50%;text-align:right;">
						<b style="color:#4991b1;"><?php echo $data['gold_price']; ?> (per gram)</b>
					</td>
				</tr>
		</table>
	</td>
	<td  style="width: 10%;"></td>
	<td  style="width: 45%;">
			
	</td>
	</tr>
	</table>
	
    <br><br>
	
	<nobreak>
     <table cellspacing="0" style="width: 100%; ; text-align: left; font-size: 14px">
        <tr>
           <td style="width: 40%;text-align:left;">
				<p style="margin: 0 0 2px;padding-bottom:40px;"><b style="color:#cc3399">Memo Maker :</b> <?php echo $mmaker[$data['memo_maker']]; ?></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For, <b>SKSM Diamonds Impex Ltd.</b></p>
           </td>
		
		   <td style="width: 20%;text-align:left;"></td>
		   <td style="width: 40%;text-align: left;">				
				<p style="margin: 0 0 2px;padding-bottom:40px;"><b style="color:#cc3399">Jewelry Maker:</b> <?php echo $data['contact_person'] ?></p>
				<hr style="width:100%;height:1px;">
				<p style="margin: 0 0 2px;padding-bottom:10px;">For, <?php echo $data['p_name'] ?></p>			
			</td>
        </tr>		
    </table>
	<br>
	<br>

    </nobreak>
</page>

