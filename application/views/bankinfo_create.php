<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="product-status-wrap breadcome-list">
        <div class="row">

<div class="col-md-12 col-sm-12 col-xs-12 mid_top_padding bankinfo">
<h5 class="mid_bottom_padding">Bank Information List</h5>
	<table class="table table-responsive">
		<tr>
			<th>Account Owner Name</th>
			<th>Bank Name</th>
			<th>Account Number</th>
			<th>Currency Type</th>
			<th>Action</th>
		</tr>
		<tbody>
			<?php   
	foreach($bankinfolist->result() as $row):
  ?>
<tr>
	<td><?php echo $row->acctowner; ?></td>
	<td><?php echo $row->bankname; ?></td>
	<td><?php echo $row->acctno; ?></td>
	<td><?php echo $row->currency_type; ?></td>	
	<td><a href="Admin/bankinfo_editform/<?php echo $row->id; ?>" class="btn btn-primary">Edit</a></td>
</tr>
<?php 
$i++;
endforeach; ?>
		</tbody>
	</table>
</div>
</div>
</div>
</div>
