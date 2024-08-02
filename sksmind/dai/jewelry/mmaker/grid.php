

<div class="col-xs-6 item-grid">

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table id="dynamic-table" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">
					
				</th>
				<th>Name</th>	
				<th>Mobile</th>					
				<th>Edit / Delete</th>
			</tr>
		</thead>

		<tbody>
			
			<?php foreach($model->getAllData() as $value): ?>
						
			<tr>
				<td class="center">
					
				</td>

				<td>
					<a href="#"><?php echo $value['name']?></a>
				</td>
				<td>
					<?php echo $value['mobile']?>
				</td>
				<td>					
					<div class=" action-buttons">						
						<a class="green editGroup" href="javascript:void(0)" item="<?php echo $value['name']?>" price="<?php echo $value['mobile']?>" gid="<?php echo $value['id']?>"  >
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>&nbsp;&nbsp;&nbsp;
						<a class="red" href="javascript:void(0);" onClick="deleteParty(<?php echo $value['id']?>);">
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
<script>

</script>
