<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mid_top_padding">
    <div class="breadcome-list">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Searching User's Transaction</h4>
                <table>    
                    <tr>
                        <th>Username</th>
                        <th>Tx Hash</th>
                        <th>Transaction Type</th>
                        <th>Transaction Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                        
                    </tr>
                    <?php
                    $i=1;
                        foreach($lists->result() as $row):
                        ?>
                    <tr>
                        <td><?php echo $row->name; ?></td>
                        <?php if(empty($row->withdrawal_id)){ ?>
				<td><?php echo $row->ethereum_deposit_txid; ?></td>
				<td>Deposit</td>
			<?php }else{ ?>
				<td><?php echo $row->withdrawal_id; ?>
				<td>Withdrawal</td>
			<?php } ?>
			
                        <td>
			<?php if($row->status==1 || $row->status==3) { ?>
				<p style="color:green;">Success</p>
			<?php }else if($row->status==2) { ?>
				<p style="color:red;">Cancelled<p>
			<?php }else{ ?>
				<p></p>
			<?php } ?>
			</td>
                        <td><?php echo $row->amount; ?></td>
			<td><?php echo $row->created; ?></td>                                               
                    </tr>
                    <?php 
                        $i++;
                        endforeach; ?>
                </table>
                </div>
	<?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
</div>
