
<div class="col-xs-12 group-grid">

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table id="dynamic-table" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">No.</th>				
				<th>Date</th>				
				<th>Party</th>
				<th>Book</th>
				<th>Type</th>
				<th>Amount</th>
				<th>Edit / Delete</th>
			</tr>
		</thead>
		<tbody>
			
			<?php $i=0; foreach($model->getAllData() as $value): $i++;	?>						
			<tr>
				<td class="center"><?php echo $i; ?></td>

				<td><?php
			$phpdate = strtotime( $value['date'] );	
			echo  date( 'd-m-Y', $phpdate );?></td>
				<td><?php echo (isset($party[$value['party']])) ? $party[$value['party']] :''; ?></td>
				<td><?php echo (isset($bookData[$value['book']]))?$bookData[$value['book']]:''; ?></td>
				<td><?php echo $value['type']; ?></td>
				<td><?php echo $value['amount']; ?></td>			
				<td>					
					<div class="hidden-sm hidden-xs action-buttons">						
						<a class="green editGroup" href="<?php echo $daiUrl;?>account/advance/index.php?pg=form&id=<?php echo $value['id'] ?>">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>
						<a class="red" href="javascript:void();" onClick="removeEntry('<?php echo $daiUrl.'account/advance/advanceController.php?fn=delete&id='.$value['id']?>')">
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


