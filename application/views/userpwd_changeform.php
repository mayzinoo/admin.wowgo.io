<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="breadcome-list">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <?=form_open('Admin/userpwd_update')?>
	<h4>Changing <?php echo $userdata->username; ?> 's password</h4>
	<
		<div class="col-md-4 xs_padding">
		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
			<label>User Name</label>
			<input type="text" name="username" class="form-control" value="<?php echo $userdata->username; ?>">
			<br/><br/>
			<label>New Password</label>
			<input type="password" name="newpwd" class="form-control">
			<br/><br/>
			<button type="submit" value="submit" name="submit" class="btn btn-success mobile-button">Change</button>
		</div>
	 <?=form_close()?>
		</div>
	   </div>
	</div>
    </div>
</div>
