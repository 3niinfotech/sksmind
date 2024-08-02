
<div class="col-xs-12 group-grid">

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table id="dynamic-table" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" />
						<span class="lbl"></span>
					</label>
				</th>
				<th>Entry No.</th>
				<th>Party</th>				
				<th>Reference </th>
				<th>Due Date</th>
				<th>Total Carat</th>				
				<th>Total Amount</th>
				<th>Paid Amount</th>
				<th>Balance</th>
				<th>Edit / Delete</th>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach($model->getAllData() as $value):	?>						
			<tr>
				<td class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" />
						<span class="lbl"></span>
					</label>
				</td>

				<td><a href="#"><?php echo $value['entryno']?></a></td>
				<td><?php echo $party[$value['ledger']]; ?></td>
				<td><?php echo $value['reference']; ?></td>
				<td><?php echo $value['duedate']; ?></td>
				<td><?php echo $value['subcarats']; ?></td>
				<td><?php echo $value['ftotalinr']; ?></td>
				<td><?php echo $value['paidinr']; ?></td>
				<td><?php echo $value['balanceinr']; ?></td>
				<td>					
					<div class="hidden-sm hidden-xs action-buttons">						
						<a class="green editGroup" href="<?php echo $daiUrl;?>module/purchase/purchase.php?pg=form&id=<?php echo $value['id'] ?>">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>
						<a class="red" href="<?php echo $moduleUrl.'purchase/purchaseController.php?fn=delete&id='.$value['id'].'&eid='.$value['entryno']?>">
							<i class="ace-icon fa fa-trash-o bigger-130"></i>
						</a> 
					</div>
				</td>
			</tr>	
			<?php endforeach; ?>
		</tbody>
		
	</table>
</div>
</div>


