<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mid_top_padding">
	<div class="breadcome-list">
		<div class="row">
		<div class="col-md-12 bankinfo">
			<?=form_open('Admin/bankinfo_update/')?>
			<h5 class="small_bottom_padding">Update bank information</h5>
			<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
			<div class="col-md-2 small_padding">
				<label>Bank Name</label>
			</div>
			<div class="col-md-10 small_padding">
				<input type="text" name="bankname" class="form-control" value="<?php echo $bankinfo->bankname; ?>">
			</div>
			<div class="col-md-2 small_padding">
				<label>Account Owner</label>
			</div>
			<div class="col-md-10 small_padding">
				<input type="text" name="name" class="form-control" value="<?php echo $bankinfo->acctowner; ?>">
			</div>
			<div class="col-md-2 small_padding">
				<label>Account Number</label>
			</div>
			<div class="col-md-10 small_padding">
				<input type="text" name="acctno" class="form-control" value="<?php echo $bankinfo->acctno; ?>">
			</div>
			<div class="col-md-2 small_padding">
				<label>Currency Type</label>
			</div>
			<div class="col-md-10 small_padding">
<?=form_dropdown("currency_type",array("ETH"=>"ETH","USD"=>"USD","KRW"=>"KRW","VND"=>"VND"),$bankinfo->currency_type,"class='form-control'")?>
			</div>
			<div class="col-md-10 col-md-offset-2">
				<div class="right"><button type="submit" class="btn btn-success">Update</button></div>
			</div>
			<?=form_close()?>
		</div>
		</div>
	</div>
</div>
