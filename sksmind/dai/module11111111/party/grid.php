
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
				<th>Company</th>
				<th>Address</th>
				<th>Country</th>
				<th>Email</th>
				<th>Contact Number</th>
				<th>Contact Person</th>
				<th>Website</th>
				<th>Bank Name</th>
				<th>Edit / Delete</th>
			</tr>
		</thead>

		<tbody>
			
			<?php foreach($model->getAllData() as $value): ?>						
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
				<td><?php echo $value['address']?></td>
				<td><?php echo $value['country']?></td>
				<td><?php echo $value['email']?></td>
				<td><?php echo $value['contact_number']?></td>
				<td><?php echo $value['contact_person']?></td>
				<td><?php echo $value['website']?></td>
				<td><?php echo $value['bank_name']?></td>

				<td>
					
					<div class="hidden-sm hidden-xs action-buttons">
						<a class="blue" href="javascript:void(0);" onClick="loadparty(<?php echo $value['id']?>)">
							<i class="ace-icon fa fa-eye bigger-130"></i>
						</a>
						
						<a class="green editGroup" href="<?php echo $modUrl."index.php?pg=form&id=".$value['id'] ;?>">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>

						<a class="red" href="<?php echo $modUrl.'partyController.php?fn=delete&id='.$value['id']?>">
							<i class="ace-icon fa fa-trash-o bigger-130"></i>
						</a>
						</div>
					

				</td>
			</tr>
			<!-- <tr id="value<?php echo $value['id']?>" style="display:none">
				<td colspan="10">
					<?php foreach($value as $k=>$v):?>
					<?php if(in_array($k,$skipArray) || $v == "" || $v == "0000-00-00")
								continue;
						?>
					
					<div class="grid-value">
						<b><?php echo strToUpper($k); ?> </b>
						<span><?php echo $v; ?></span>
					</div>
					<?php endforeach;?>
				</td>
			</tr>	-->
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
