<?php
//include('model.php');
//$model  = new model();
?>
<form class="form-horizontal" method="POST" role="form" action="<?php echo $moduleUrl.'attribute/attributeController.php'?>">
<input type="hidden" name="fn" value="save" />
<?php 
	if(isset($_GET['id']))
	{ ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
<?php } ?>
<div class="col-xs-12 col-sm-6 ">
	
	<h4 class="widget-title">Attribute Detail</h4>
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Attribute Name</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="name" name="name" value="<?php echo $data['name']  ?>"  placeholder="Attribute Name"  type="text">
		</div>
	</div>

	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Attribute Code</label>
		<div class="col-sm-8">			
			<input class="input-sm col-sm-10" id="code" name="code" value="<?php echo $data['code']  ?>"  placeholder="Code"  type="text">
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Type</label>
		<div class="col-sm-8">
			<select class="col-sm-10" name="type">
				<option> --- Select Type --- </option>
				<option value="text" <?php echo ($data['type'] =='text')?'selected':'' ?> >TextBox</option>
				<option value="select" <?php echo ($data['type'] =='select')?'selected':'' ?>>Select</option>
			</select>
		</div>
	</div>

	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Status</label>
		<div class="col-sm-8">
			<select class="col-sm-10" name="status">
				<option> --- Select Status --- </option>
				<option value="1" <?php echo ($data['status'] ==1)?'selected':'' ?> >Enable</option>
				<option value="0" <?php echo ($data['status'] ==0)?'selected':'' ?> >Disable</option>
			</select>
		</div>
	</div>
	
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Required</label>
		<div class="col-sm-8">
			<select class="col-sm-10" name="required">
				<option> --- Select Required --- </option>
				<option value="1" <?php echo ($data['required'] ==1)?'selected':'' ?> >Yes</option>
				<option value="0"<?php echo ($data['required'] ==0)?'selected':'' ?> >No</option>
			</select>
		</div>
	</div>
	<div class="form-group col-sm-8">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-4">Sort Order</label>
		<div class="col-sm-8">
			<input class="input-sm col-sm-10" id="short_order" name="short_order" value="<?php echo $data['short_order']  ?>"  placeholder="Code"  type="text">
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-6 ">	
	<h4 class="widget-title">Attribute Value</h4>
	
	<div class="subform">
		<div class="divTable" style="width: 100%;">
			
			<div class="divTableHeading">			
				<div class="divTableCell">Sr No.</div>
				<div class="divTableCell">Code</div>
				<div class="divTableCell">Value</div>
				<div class="divTableCell"><i class="add-more fa fa-plus "></i></div>
			</div>
				
			<div class="divTableBody">
				<?php if(!isset($_GET['id'])): ?>
				<div class="divTableRow">
					<div class="divTableCell">1</div>					
					<div class="divTableCell"><input class=" col-sm-12"  name="record[1][code]" type="text" ></div>
					<div class="divTableCell"><input class=" col-sm-12" name="record[1][label]" type="text"></div>
					<div class="divTableCell"></div>
				</div>
				<?php else: ?>
					<?php 
					$i=1;					
					foreach($dataRecord as $dr): ?>
					<input type="hidden" value="<?php echo $dr['attribute_id']?>" name="record[<?php echo $i; ?>][id]" />
						<div class="divTableRow" id="rowid-<?php echo $i; ?>" >
							<div class="divTableCell"><?php echo $i; ?></div>						
							<div class="divTableCell"><input class=" col-sm-12"  name="record[<?php echo $i; ?>][code]" value="<?php echo $dr['code']; ?>"  type="text" ></div>
							<div class="divTableCell"><input class=" col-sm-12" name="record[<?php echo $i; ?>][label]" value="<?php echo $dr['label']; ?>" type="text"></div>
							<div class="divTableCell"><i class="delete-more fa fa-times " onClick="removeRow(<?php echo $i; ?>)" ></i></div>
						</div>
						<?php 
						$i++;
						endforeach; ?>					
				<?php endif; ?>					
			</div>			
		</div>

	</div>

</div>
<br>
<hr>
<div class="col-md-offset-5 col-md-6">
	<button class="btn btn-info" type="submit">
		<i class="ace-icon fa fa-check bigger-110"></i>
		Submit
	</button>

	&nbsp; &nbsp; &nbsp;
	<button class="btn reset" type="reset">
		<i class="ace-icon fa fa-undo bigger-110"></i>
		Reset
	</button>
</div>

</form>

<style>
.form-group {
  margin-bottom: 30px;
}


.divTableCell {
  width: 30%;
}

.divTableCell {
  float: left;
  padding: 7px;
  text-align: center;
}
.divTableCell:first-child {
 width: 10% !important;
}
.divTableCell:last-child {
  padding: 5px 0 0;
  width: 10% !important;
}
.divTableHeading, .divTableBody, .divTableFooter {
  float: left;
  width: 100%;
}
.divTableHeading {
  border-bottom: 1px solid #f1f1f1;
  border-top: 1px solid #f1f1f1;
  margin-bottom: 10px;
  margin-top: 10px;
  padding-bottom: 10px;
  padding-top: 5px;
}
.divTableHeading .divTableCell {
  color: #666;
  font-size: 13px;
  font-weight: bold;
  letter-spacing: 1px;
}
.divTableRow {
  float: left;
  margin: 0;
  width: 100%;
}
.divTable {
  font-size: 14px;
}
.a-right {
  padding-top: 15px;
  text-align: right !important;
}
.add-more {
  background-color: #69aa46;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 25px;
  padding: 6px 5px 5px;
  width: 25px;
  cursor: pointer;
}

.delete-more {
  background-color: #dd5a43;
  border-radius: 100%;
  color: #fff;
  font-size: 14px;
  height: 22px;
  padding: 4px 5px 5px;
  width: 22px;
  cursor: pointer;
}
</style>