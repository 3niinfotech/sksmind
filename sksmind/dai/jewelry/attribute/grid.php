
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
				<th>Name</th>
				<th>Code</th>
				<th>Type</th>
				<th>Status</th>
				<th>Required</th>		
				<th>Order</th>						
				<th>Edit / Delete</th>
			</tr>
		</thead>

		<tbody>
			
			<?php 
			$yeno[1] ="Yes";
			$yeno[0] ="No";
			
			
			foreach($model->getAllData() as $value): ?>						
			<tr>
				<td class="center">
					<label class="pos-rel">
						<input type="checkbox" class="ace" />
						<span class="lbl"></span>
					</label>
				</td>

				<td>
					<a href="#"><?php echo $value['name']?></a>
				</td>
				<td><?php echo $value['code']?></td>
				<td><?php echo $value['type']?></td>
				<td><?php echo $yeno[$value['status']]?></td>
				<td><?php echo $yeno[$value['required']]?></td>	
				<td><?php echo $value['short_order']?></td>					
				<td>
					
					<div class="hidden-sm hidden-xs action-buttons">
						<a class="blue" href="javascript:void(0);" onClick="showValue(<?php echo $value['id']?>)">
							<i class="ace-icon fa fa-eye bigger-130"></i>
						</a>
						
						<a class="green editGroup" href="<?php echo $modUrl."index.php?pg=form&id=".$value['id'] ;?>">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>

						<a class="red" onClick="removeEntry('<?php echo $modUrl.'attributeController.php?fn=delete&id='.$value['id']?>','<?php echo $value['name']?>')" href="javascript:void()">
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
<style>
.grid-value {
  float: left;
  padding: 10px;
  width: 20%;
}
.grid-value > b {
  border-bottom: 1px solid #f1f1f1;
  color: #478fca;
  float: left;
  letter-spacing: 1px;
  margin-bottom: 5px;
  padding-bottom: 5px;
  width: 70%;
}
.grid-value > span {
  float: left;
  font-size: 13px;
  width: 70%;
}
</style>
<script>
	function showValue(id)
	{
		$('#value'+id).slideToggle('slow');
	}
</script>
