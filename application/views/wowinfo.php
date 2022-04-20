<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="breadcome-list">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <div class="col-md-4">
                        <h5>Add WoW Amount</h5>
                        
		 <?=form_open('Admin/update_requestwow/')?>
			<input type="hidden" value="<?php echo $this->uri->segment(3); ?>" name="userid">
			<input type="hidden" value="<?php echo $userdata->balance; ?>" name="bal">
			<input type="hidden" value="<?php echo $this->uri->segment(4); ?>" name="id">
                            <div class="col-md-12 mid_top_padding">
                            <div class="col-md-2 small_bottom_padding">
                                <label>Amount</label>
                            </div>
                            <div class="col-md-8 small_bottom_padding">
                            <input type="text" class="form-control" name="wow_amt" >
                            </div>
                            <div class="col-md-2">
                                <button type="submit" value="submit" name="submit" class="btn btn-info">Add</button>
                            </div>
                            </div>
                            </div>  
		<?=form_close()?>
                    
                </div>
            </div>
        </div>
    </div>
</div>      
