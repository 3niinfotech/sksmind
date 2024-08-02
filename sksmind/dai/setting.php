	<div class="ace-settings-container" id="ace-settings-container">
	<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
		<i class="ace-icon fa  fa-calculator bigger-130" title="Interest Calculator"></i>
	</div>

	<div class="ace-settings-box clearfix interestcalculater" id="ace-settings-box">
		<div class="pull-left">
			<h4 style="text-align:center">Interest Calculator</h4>
			<hr/>
			<form class="form-horizontal " style="margin-top:20px;" >
				
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">From</label>
					<div class="col-sm-7">
						<input class="input-sm col-sm-12" id="intfromdate" name="from"   type="text" onChange="onDateChange()">
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">To</label>
					<div class="col-sm-7">
						<input onChange="onDateChange()" class="input-sm col-sm-12" id="inttodate" name="to"   type="text">
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">Days</label>
					<div class="col-sm-7">
						<input class="input-sm col-sm-12" readonly id="intdays" name="days"  type="text">
					</div>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">Amount</label>
					<div class="col-sm-7">
						<input class="input-sm col-sm-12" id="intamount" name="days"   type="text">
					</div>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">Interest %</label>
					<div class="col-sm-7">
						<input class="input-sm col-sm-12" id="intinterest" name="interest"   type="text" onKeyup="calInterest()" onBlur="calInterest()">
					</div>
				</div>
				<div class="form-group col-sm-12">
					<label class="col-sm-5 control-label no-padding-right" for="form-field-4" style="line-height:22px;">Interest Amt.</label>
					<div class="col-sm-7">
						<input class="input-sm col-sm-12" id="inttotalinterest" name="totalinterest" readonly  type="text">
					</div>
				</div>
				
				<div class="form-group col-sm-12" style="text-align:center">
					<button class="btn reset" type="reset">
						<i class="ace-icon fa fa-undo bigger-110"></i>
						Reset
					</button>								
				</div>
				
			
			</form>
						
			
		</div><!-- /.pull-left -->


	</div><!-- /.ace-settings-box -->
	</div><!-- /.ace-settings-container -->
<style>
.dropdown-menu-right .form-group:first-child{margin-top:20px;}
.interestcalculater{width:400px !important;  }
</style>
