
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
				<span style="color:#4991b1;font-size:16px;width:100%;" >Repair Memo</span>
				<br>
				<span style="font-size:10px;width:100%;margin-top:5px;" >(ORIGINAL COPY)</span>
            </td>
        </tr>
		
    </table>
	<br>
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
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;"><b style="color:#cc3399">Repair Memo : &nbsp;&nbsp;&nbsp;</b><?php echo $data['entryno']; ?></p>
				<?php $phpdate = strtotime( $data['date'] );?>
				<p style="font-size: 16px;margin: 0 0 2px;padding-bottom:10px;height:30px;"><b style="color:#cc3399">Date : &nbsp;&nbsp;&nbsp;</b><?php echo  date( 'd-m-Y', $phpdate ); ?></p>	
			</td>
        </tr>		
    </table>
	<br><br><h4>Repairing Jewelry Detail</h4><br>
	
	  
    <table cellspacing="0" border="1" cellpadding="5" style="border-collapse:collapse;width: 100%; border: solid 1px black; color: #cc3399; text-align: center; font-size: 10pt;">
        <tr>
            <th style="width: 5%;text-align: center; padding:3px;">No.</th>						
            <th style="width: 11%;text-align: center;padding:3px;">SKU</th>
            <th style="width: 11%;text-align: center;padding:3px;">Design</th>
            <th style="width: 11%;text-align: center;padding:3px;">Type</th>
            <th style="width: 7%;text-align: center;padding:3px;">Gross Cts</th>			
			<th style="width: 6%;text-align: center;padding:3px;">Side Pcs</th>
            <th style="width: 7%;text-align: center;padding:3px;">Side Cts</th>                        
            <th style="width: 42%;text-align: center;padding:3px;">Comment</th>			
        </tr>
    </table>
	<table cellspacing="0" border="1"  cellpadding="5" style="width: 100%;   border-collapse:collapse;text-align: center; font-size: 12px;">
	<?php $i=0; foreach($data['record'] as $pdata): $i++;?>
		<tr>
            <td style="width: 5%;height: 4px; text-align: center;padding:3px;"><?php echo $i; ?></td>
			
            <td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php echo $pdata['sku']; ?> </td>
            <td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php echo $design[$pdata['jew_design']]; ?></td>
            <td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php echo $pdata['jew_type']; ?></td>
            <td style="width: 7%;height: 4px; text-align: center;padding:3px;"><?php echo $pdata['total_carat']; ?></td>
            <td style="width: 6%;height: 4px; text-align: right;padding:3px;"> <?php echo $pdata['sidepcs']; ?></td>
			<td style="width: 7%;height: 4px; text-align: right;padding:3px;"><?php echo $pdata['sidecarat']; ?></td>            
            <td style="width: 42%;height: 4px; text-align: left;padding:3px;font-size:10px;"><?php echo $pdata['comment']; ?></td>
           
        </tr>
	<?php endforeach;?>	
	<?php for($j=$i+1;$j<=15; $j++): ?>
	<tr>
		<td style="width: 5%;height: 4px; text-align: center;padding:3px;"><?php echo $j; ?></td>
		
		<td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php //echo $pdata['sku']; ?> </td>
		<td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php //echo $pdata['jew_design']; ?></td>
		<td style="width: 11%;height: 4px; text-align: center;padding:3px;"><?php //echo $pdata['jew_type']; ?></td>
		<td style="width: 7%;height: 4px; text-align: center;padding:3px;"><?php //echo $pdata['gold_type']; ?></td>
		<td style="width: 6%;height: 4px; text-align: right;padding:3px;"> <?php //echo $pdata['total_carat']; ?></td>
		<td style="width: 7%;height: 4px; text-align: right;padding:3px;"><?php //echo $pdata['gold_gram']; ?></td>		
		<td style="width: 42%;height: 4px; text-align: left;padding:3px;"><?php //echo $pdata['gross_cts']; ?></td>
	   
	</tr>
	<?php endfor;?>
	 </table>
    
    <br><br>
	



	
    <br><br> <br><br>    <br> <br><br>
	
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

