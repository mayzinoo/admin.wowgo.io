<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mid_top_padding">
	<div class="breadcome-list">
		<div class="product-status-wrap bankinfo">
		<h5 class="mid_bottom_padding">Deposit Request List</h5>
		<table class="table table-responsive">
			<tr>
				<th>#</th>
				<th>Username</th>
				<th>Currency</th>
				<th>Amount</th>
				<th>Value in WoW</th>
				<th>Paid Amount</th>
				<th>Current Balance</th>
				<th>Request Information</th>
				<th>Depositor's Name</th>
				<th>Requested Date</th>
				<th>Action</th>
			</tr>
			<tbody>
			<?php 
				$i=1;
				foreach($depositrequest->result() as $row): ?>
				<tr>
			
					<td><?php echo $i; ?></td>
					<td><?php echo $row->username; ?></td>
					<td><?php echo $row->currency_type; ?></td>
					<td><?php echo $row->currency_amount; ?></td>
					<td><?php echo $row->amt; ?></td>
					<?php if($row->status==3) { ?>
						<td style="color:#ee9e2b;"><?php echo $row->update_wow; ?></td>
					<?php }else if($row->status==1){ ?>
						<td><?php echo $row->amt; ?></td>
					<?php }else{ ?>
						<td>0</td>
					<?php } ?>
					<td><?php echo $row->bal;?></td>
					<?php if($row->currency_type=='ETH') {?>
						<td><a onclick="depositinfo(<?=$row->id?>)" class="btn btn-success">View</a></td>
					<?php }else if($row->currency_type=='CTC'){?>
						<td><a onclick="ctcinfo(<?=$row->id?>)" class="btn btn-success">View</a></td>
					<?php }else{ ?>
						<td></td>
					<?php } ?>
					<td><?php echo $row->owner_name; ?></td>
					<td><?php echo $row->created; ?></td>
					
	<?php if($row->status==1 || $row->status==3){ ?>
	
	<td style="line-height:2rem"> 	<p style="color:#57da3e;font-weight:bold;">Success</p></td>
	<?php }else if($row->status==2) { ?>
		<td style="line-height:2rem"><p style="color:#f90606;font-weight:bold;">Cancelled</p></td>
	
	<?php }else{ ?>
<td style="line-height:1rem"><button onclick="confirmdeposit('<?=$row->id?>','<?=$row->user_id?>','<?=$row->amt?>','<?=$row->bal?>','<?=$row->currency_type?>')" style="padding: 6px 12px;
    border-radius: 4px;
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;" id="txtchg<?=$row->id?>">Confirm
<button onclick="canceldeposit('<?=$row->id?>','<?=$row->user_id?>','<?=$row->amt?>','<?=$row->bal?>','<?=$row->currency_type?>')" style="margin-left:10px;padding: 6px 12px;
    border-radius: 4px;
    color: #fff;
    background-color: #ff0000;
    border-color:#ff0000;" class="btn-side" id="txtcancel<?=$row->id?>">Cancel</button>
<a href="Admin/editwow/<?php echo $row->user_id; ?>/<?php echo $row->id; ?>" class="btn btn-success" id="txtupdate<?=$row->id?>">Update</a>
</td>
	<?php	} ?>
					</td>
				</tr>
			<?php 
				$i++;
				endforeach;
			?>
			</tbody>
		</table>
		</div>
	
	</div>
</div>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ethaddressshow" class="modal fade" style="display:none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">X</button>
				<h4 class="modal-title">Deposit Request Information</h4>
				</div>
				<div class="modal-body" id="ethaddress_show">
				<div class="col-md-12">
					<div class="col-md-2 small_bottom_padding">
						<label>ETH Address</label>
					</div>
					<div class="col-md-10 small_bottom_padding">
						<input type="text" class="form-control" id="ethaddress" readonly>
					</div>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-info" type="botton">Close</button>
				</div>
				</div>
			</div>
		</div>
	</div>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ctcaddressshow" class="modal fade" style="display:none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">X</button>
					<h4 class="modal-title">Deposit Request Information</h4>
					
				</div>
				<div class="modal-body" id="ctcaddress_show">
					<div class="col-md-12">
						<div class="col-md-2 small_botton_padding">
							<label>CTC Address</label>
						</div>
						<div class="col-md-10 small_bottom_padding">
							<input type="text" class="form-control" id="ctcaddress" readonly>
						</div>
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-info" type="botton">Close</button>
					</div>
			
				</div>
			</div>
		</div>
	</div>

	<script scr="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>

	<script>
		function confirmdeposit(id,userid,wow,bal,curtype){
		
		var r = confirm("Are You Sure ?");
		var type=curtype;

if (r == true) {	

		$.ajax({
			
			type: "POST",
			url: '<?=base_url()?>'+"Admin/depositrequest_confirm",
			data: {id:id,userid:userid,wow:wow,bal:bal},
			
			success: function(e)
			{		
				alert(e);
			//	$("#txtchg"+id).prop("onclick",null).off("click").html("Success");
			//	$("#txtchg"+id).css({"color": "#57da3e","cursor":"auto","font-weight":"bold"});
				$("#txtchg"+id).css({"display":"none"});
				$("#txtcancel"+id).css({"display":"none"});
				location.reload(); 
			}
			});
		}
else{

	
}
		}
		function canceldeposit(id,userid,wow,bal,curtype){

		var r=confirm("Are You Sure to cancel ?");
		if(r==true)
			{
			$.ajax({
				type:"POST",
				url:'<?=base_url()?>'+"Admin/depositrequest_cancel",
				data: {id:id,userid:userid,wow:wow,bal:bal},
				success: function (e)
					{
				//	$("#txtcancel"+id).prop("onclick",null).off("clcick").html("Cancelled");
				//	$("#txtcancel"+id).css({"color":"#f90606","cursor":"auto","font-weight":"bold"});
					$("#txtcancel"+id).css({"display":"none"});
					$("#txtchg"+id).css({"display":"none"});
					$("#txtupdate"+id).css({"display":"none"});
					location.reload(); 
					}
				});
			}
		}
	function depositinfo(id)
			{
				data="id"+id;
				$.ajax({
					type:"POST",
					url:'<?=base_url()?>'+"Admin/depositinfo",
					data:{id:id},
					success:function(e)
					{
						var v=JSON.parse(e);
						$('#ethaddressshow').modal('show');
						$('#ethaddress').val(v.ethereum_deposit_address);
					}
				});

			}
	function ctcinfo(id)
	{
	
		data="id"+id;
		$.ajax({
			type:"POST",
			url:'<?=base_url()?>'+"Admin/ctcinfo",
			data:{id:id},
			success:function(e)
			{
			
				var v=JSON.parse(e);
				$('#ctcaddressshow').modal('show');
				$('#ctcaddress').val(v.ethereum_deposit_address);
			}
		});
	}
	</script>
