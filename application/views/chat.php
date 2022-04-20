<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="product-status-wrap breadcome-list">
        <div class="row">

<div class="col-md-12 col-sm-12 col-xs-12 mid_top_padding bankinfo">
<h5 class="mid_bottom_padding">Chat</h5>
	<table class="table table-responsive">
		<tr>
			<th>#</th>
			<th>Username</th>
			<th>Chat Message</th>
			<th>Channel</th>
			<th>Date</th>
			<th>Action</th>			
		</tr>
		<tbody>
			<?php 
			    $i=0;  
				foreach($userdata->result() as $row):
					
					$i++;
					
  ?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $row->username; ?></td>	
	<td><?php echo $row->message; ?></td>
	<td><?php echo $row->channel; ?></td>
	<td><?php echo $row->created; ?></td>
	<td>
	
	<button type="button" id="submit" class="btn btn-primary mobile-button" ><a href="Admin/chat_delete/<?php echo $row->id;?>">Delete</a></button></td>
</tr>
<?php
 
endforeach; ?>
		</tbody>
	</table>
</div>
<p><?php echo $links; ?></p>
</div>
</div>
</div>
