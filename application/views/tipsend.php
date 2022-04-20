<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="product-status-wrap breadcome-list">
        <div class="row">

<div class="col-md-12 col-sm-12 col-xs-12 mid_top_padding bankinfo">
<h5 class="mid_bottom_padding">Tip Send List</h5>
	<table class="table table-responsive">
		<tr>
			<th>#</th>
			<th>From</th>
			<th>To</th>
			<th>Amount</th>
			<th>Commission</th>
			<th>Transaction ID</th>
			<th>Date</th>			
		</tr>
		<tbody>
			<?php 
			    $i=0;  
				foreach($tip->result() as $row):
					
					$i++;
					
  ?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $row->sender; ?></td>	
	<td><?php echo $row->recipient; ?></td>
	<td><?php echo $row->amount; ?></td>
	<td><?php echo $row->commission; ?></td>
	<td><?php echo $row->tip_tx_id; ?></td>
	<td><?php echo $row->created; ?></td>	
</tr>
<?php
 
endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>
