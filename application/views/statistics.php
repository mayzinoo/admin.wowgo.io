<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mid_top_padding">
	<div class="breadcome-list">
	
	<div class="row">

		<div class="product-status-wrap">
		<div class="col-md-12 col-lg-12 col-sm-12 mid_padding">
			
				<div class="col-md-3 col-sm-12 xs_padding">
					<label>Start Date</label>
					<input type="date" class="form-control" name="start_date" id="startdate">
				</div>
				<div class="col-md-3 col-sm-12 xs_padding">
					<label>End Date</label>
					<input type="date" class="form-control" name="end_date" id="enddate">
				</div>
				<div class="col-md-2 mid_top_padding">
					<button class="btn btn-success mobile-button" onclick="searchbtn()">Filter</button>
				</div>
		
		</div>
		<div class="col-md-12 col-lg-12 col-sm-12 mid_padding">

	<button class="btn btn-success" onclick="search24()" id="sch24">24 Hours</button>
			<button class="btn btn-success" onclick="search3d()" id="sch3d">3 Days</button>
			<button class="btn btn-success" onclick="search1w()" id="sch1w">1 Week</button>
			<button class="btn btn-success" onclick="search1m()" id="sch1m">1 Month</button>
			<button class="btn btn-success" onclick="search3m()" id="sch3m">3 Months</button>
			
		</div>
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="col-md-2 col-sm-12">
				<div class="totaldeposit">			
					<h5>Total Deposit</h5>
					<p><span id="deposit"><?php echo number_format($deposit->totaldeposit); ?></span> wow </p>
				</div>
			</div>
			<div class="col-md-2 col-sm-12">
				<div class="totalwithdraw">
					<h5>Total withdrawal</h5>
					<p>-<span id="withdraw"><?php echo number_format($withdraw->totalwithdraw); ?></span> wow</p>
				</div>
			</div>
			<div class="col-md-2 col-sm-12">
				<div class="totalbalance">
					<h5>Total users</h5>
					<p><span id="balance"><?php echo number_format($balance->totalbalance); ?></span> </p>
				</div>
			</div>
			<div class="col-md-2 col-sm-12">
				<div class="totalbalance">
					<h5>Total Amount of WoW</h5>
					<p><span id="totalwow"><?php echo number_format($wow->totalwow); ?></span> wow</p>
				</div>
			</div>
			<div class="col-md-2 col-sm-12">
				<div class="totalnetprofit">
					<h5>NET Profit</h5>
					<p><span id="netprofit"><?php echo number_format($netprofit->totalnetprofit); ?></span> wow</p>
				</div>
			</div>
		</div>
		</div>
	</div>
	</div>
</div>
<script>
	function thousands_separators(num)
  {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
  }
	function searchbtn(){
		var startdate=document.getElementById("startdate").value;
		var enddate=document.getElementById("enddate").value;
		
		$.ajax({
		type:"POST",
		url: '<?=base_url()?>'+"Admin/statistics_search",
		data: {startdate:startdate,enddate:enddate},
		success:function(e)
			{
			var v=JSON.parse(e);
			
			$("#deposit").html(v.deposit);	
			$("#withdraw").html(v.withdraw);
			$("#balance").html(v.balance);
			$("#totalwow").html(v.wow);
			$("#netprofit").html(v.netprofit);
			
			}
		
		});
	}
	function search24(){
	
	var sch24=document.getElementById("sch24").innerHTML;
	
$.ajax({
	type: "POST",
	url: '<?=base_url()?>'+"Admin/search24",
	data: {sch24:sch24},
	success:function(e)
	{
		var v=JSON.parse(e)
		$("#sch24").css({"background":"red"});
		$("#sch3d").css({"background":"#5cb85c"});
$("#sch1w").css({"background":"#5cb85c"});
$("#sch1m").css({"background":"#5cb85c"});
$("#sch3m").css({"background":"#5cb85c"});
		$("#deposit").html(v.deposit);
		$("#withdraw").html(v.withdraw);
		$("#balance").html(v.balance);	
		$("#totalwow").html(v.wow);
		$("#netprofit").html(v.netprofit);
		
	}
		});
	}
	function search3d(){
	var sch3d=document.getElementById("sch3d").innerHTML;
	
	$.ajax({
	type:"POST",
	url:'<?=base_url()?>'+"Admin/search3d",
	data: {sch3d:sch3d},
	success:function(e)
	{
		var v=JSON.parse(e)
		$("#sch3d").css({"background":"red"});
		$("#sch24").css({"background":"#5cb85c"});
$("#sch1w").css({"background":"#5cb85c"});
$("#sch1m").css({"background":"#5cb85c"});
$("#sch3m").css({"background":"#5cb85c"});
		$("#deposit").html(v.deposit);
		$("#withdraw").html(v.withdraw);
		$("#balance").html(v.balance);	
		$("#totalwow").html(v.wow);
		$("#netprofit").html(v.netprofit);
	}
	});
	}
	function search1w(){
	var sch1w=document.getElementById("sch1w").innerHTML;
	
	$.ajax({
	type:"POST",
	url:'<?=base_url()?>'+"Admin/search1w",
	data: {sch1w:sch1w},
	success:function(e)
	{
		var v=JSON.parse(e)
		$("#sch1w").css({"background":"red"});
	$("#sch3d").css({"background":"#5cb85c"});
$("#sch24").css({"background":"#5cb85c"});
$("#sch1m").css({"background":"#5cb85c"});
$("#sch3m").css({"background":"#5cb85c"});
		$("#deposit").html(v.deposit);
		$("#withdraw").html(v.withdraw);
		$("#balance").html(v.balance);	
		$("#totalwow").html(v.wow);
		$("#netprofit").html(v.netprofit);
	}
	});
	}
	function search1m(){
	var sch1m=document.getElementById("sch1m").innerHTML;
	
	$.ajax({
	type:"POST",
	url:'<?=base_url()?>'+"Admin/search1m",
	data: {sch1m:sch1m},
	success:function(e)
	{
		var v=JSON.parse(e)
		$("#sch1m").css({"background":"red"});
		$("#sch3d").css({"background":"#5cb85c"});
$("#sch1w").css({"background":"#5cb85c"});
$("#sch24").css({"background":"#5cb85c"});
$("#sch3m").css({"background":"#5cb85c"});
		$("#deposit").html(v.deposit);
		$("#withdraw").html(v.withdraw);
		$("#balance").html(v.balance);	
		$("#netprofit").html(v.netprofit);
	}
	});
	}
	function search3m(){
	var sch3m=document.getElementById("sch3m").innerHTML;
	
	$.ajax({
	type:"POST",
	url:'<?=base_url()?>'+"Admin/search3m",
	data: {sch3m:sch3m},
	success:function(e)
	{
		var v=JSON.parse(e)

		$("#sch3m").css({"background":"red"});
		$("#sch3d").css({"background":"#5cb85c"});
$("#sch1w").css({"background":"#5cb85c"});
$("#sch1m").css({"background":"#5cb85c"});
$("#sch24").css({"background":"#5cb85c"});
		$("#deposit").html(v.deposit);
		$("#withdraw").html(v.withdraw);
		$("#balance").html(v.balance);	
		$("#totalwow").html(v.wow);
		$("#netprofit").html(v.netprofit);
	}
	});
	}
</script>
