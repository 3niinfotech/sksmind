

<div class="col-xs-6 group-grid">

<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div>
	<table id="dynamic-table" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center" style="width:100px">Sr. No.</th>
				<th class="center">Sub Group</th>
				<th class="center">Under</th>				
				<th class="center" style="width:100px">Action</th>
			</tr>
		</thead>

		<tbody>
			
			<?php

			$i=0; foreach($model->getData() as $value):	$i++;
			?>
						
			<tr>
				<td class="center" style="width:100px"><?php echo $i; ?></td>
				<td><a href="#"><?php echo $value['name']; ?></a>	</td>
				<td><?php echo (isset($Group[$value['under']])) ? $Group[$value['under']]:''; ?></td>
				<td class="center" style="width:100px">					
					<div class="hidden-sm hidden-xs action-buttons">						
						<a class="green editGroup" href="javascript:void(0)" gid="<?php echo $value['id']?>" gname="<?php echo $value['name']?>" >
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>

						<a class="red" onClick="removeEntry('<?php echo $daiUrl.'account/subgroup/subgroupController.php?fn=delete&id='.$value['id']?>')" href="javascript:void()">						
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
