<div class="subform">
<div class="divTable" style="width: 2700px;">
	
	<div class="divTableHeading">
		<div class="divTableCell">&nbsp; <!-- <i class="add-more fa fa-plus "> </i> --></div>
		<div class="divTableCell">No.</div>		
		<div class="divTableCell">Mfg. code</div>
		<div class="divTableCell">D. No.</div>
		<div class="divTableCell">SKU</div>
		<div class="divTableCell">R.Pcs</div>
		<div class="divTableCell">R.Carat</div>		
		<div class="divTableCell">P.Pcs</div>
		<div class="divTableCell">P.Carat</div>
		<div class="divTableCell">Cost</div>		
		<div class="divTableCell">Price</div>
		<div class="divTableCell">Amount</div>
		<div class="divTableCell">LOC </div>
		<div class="divTableCell">Remark</div>		
	</div>
		
	<div class="divTableBody">
		<?php if(!isset($_GET['id'])): ?>
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">1</div>			
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][mfg_code]" id="mfg-1" onBlur="addImportRow(1)" type="text" ></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][diamond_no]" id="dno-1" onBlur="generateSku(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][sku]" id="sku-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][rought_pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][rought_carat]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_pcs]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][polish_carat]" id="pcarat-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][cost]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][price]" id="price-1" onBlur="calAmount(1)"  type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right"  name="record[1][amount]" id="amount-1" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][location]" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12"  name="record[1][remark]" type="text"></div>
			
		</div>
		<?php else: ?>
			<?php 
			$i=1;
			foreach($dataRecord as $dr): ?>
			<input type="hidden" value="<?php echo $dr['id']?>" name="record[<?php echo $i; ?>][id]" />
			<div class="divTableRow">
				<div class="divTableCell"><?php echo $i; ?></div>
				<div class="divTableCell" ><input class=" col-sm-12"  name="record[<?php echo $i; ?>][lot]" value="<?php echo $dr['lot']; ?>" placeholder="" type="text" ></div>
				<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i; ?>][lot]" value="<?php echo $dr['lot']; ?>" placeholder="" type="text" ></div>
				<div class="divTableCell"><input class=" col-sm-12 caratsimp a-right" id="carats-1" name="record[<?php echo $i; ?>][carats]" value="<?php echo $dr['carats']; ?>" onkeyup="caratsimp()" placeholder="" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 rateusd a-right" id="rateusd-1" onblur="rateusdimp(this)" value="<?php echo $dr['rateusd']; ?>" name="record[<?php echo $i; ?>][rateusd]" placeholder="" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 valueusd a-right"  id="valueusd-1"name="record[<?php echo $i; ?>][valueusd]" value="<?php echo $dr['valueusd']; ?>" readonly placeholder="" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 rateinr a-right"   id="rateinr-1"name="record[<?php echo $i; ?>][rateinr]" value="<?php echo $dr['rateinr']; ?>" onblur="rateinrimp(this)"placeholder="" type="text"></div>
				<div class="divTableCell"><input class=" col-sm-12 valueinr a-right"  id="valueinr-1" name="record[<?php echo $i; ?>][valueinr]" value="<?php echo $dr['valueinr']; ?>" readonly placeholder="" type="text"></div>
				<div class="divTableCell"></div>
				</div>
				<?php 
				$i++;
				endforeach; ?>
			
		<?php endif; ?>
			
	</div>
	
	<!-- <div class="divTableFooter">
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell a-right">Sub Total : </div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="subcarat" name="subcarats" value="<?php echo $data['subcarats'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="avg_rateusd" name="avg_rateusd" value="<?php echo $data['avg_rateusd'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="subvalueusd" name="subvalueusd" value="<?php echo $data['subvalueusd'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="avg_rateinr" name="avg_rateinr" value="<?php echo $data['avg_rateinr'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="subvalueinr" name="subvalueinr" value="<?php echo $data['subvalueinr'] ?>" readonly  placeholder="" type="text"></div>
			<div class="divTableCell">&nbsp;</div>
		</div>
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell a-right">Shipping Expenses :  </div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="shipping" name="shipping" value="<?php echo $data['shipping'] ?>" onkeyup="shippingimp(this.value)" placeholder="" type="text"></div>
			<div class="divTableCell a-right">Total US$ : </div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="ftotalusd" name="ftotalusd" value="<?php echo $data['ftotalusd'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell a-right">Exchange @ : </div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="exchange" name="exchange" value="<?php echo $data['exchange'] ?>" onkeyup="totalAmountimp(this.value)"  placeholder="" type="text"></div>
			<div class="divTableCell">&nbsp;</div>
		</div>
		<div class="divTableRow">
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell">&nbsp;</div>
			<div class="divTableCell a-right"><b>Total &#8377; </b> </div>
			<div class="divTableCell"><input class=" col-sm-12 a-right" id="ftotalinr" name="ftotalinr" value="<?php echo $data['ftotalinr'] ?>" readonly placeholder="" type="text"></div>
			<div class="divTableCell">&nbsp;</div>
		</div>		
	</div>-->
</div>

</div>
