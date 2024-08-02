<?php
//include('model.php');
//$model  = new model();
?>

<div class="col-xs-12 col-sm-5 right">
	<div class="widget-box">
		<div class="widget-header">
			<h4 class="widget-title">Create Memo Maker</h4>

			<span class="widget-toolbar">
				<a href="#" data-action="reload">
					<i class="ace-icon fa fa-refresh"></i>
				</a>

				<a href="#" data-action="collapse">
					<i class="ace-icon fa fa-chevron-up"></i>
				</a>

				<a href="#" data-action="close">
					<i class="ace-icon fa fa-times"></i>
				</a>
			</span>
		</div>
		<form class="form-horizontal" method="POST" role="form" action="<?php echo $jewelryUrl.'mmaker/mmakerController.php'?>">
		<div class="widget-body">
			<div class="widget-main">
				<br>
				<input type="hidden" name="fn" value="saveGroup"/>
				<input type="hidden" name="id" id="gid" value=""/>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
	
					<div class="col-sm-9">
						<input id="item" required name="name" placeholder="Description" class="col-xs-10" type="text">
					</div>
				</div>				
				<br>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mobile </label>
	
					<div class="col-sm-9">
						<input id="price" required name="mobile" placeholder="Description" class="col-xs-10" type="text">
					</div>
				</div>				
				
			<div class="clearfix form-actions">
				<div class="col-md-offset-3 col-md-9">
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
			</div>
			</div>
		</div>
		</form>
	</div>
</div><!-- /.span -->
