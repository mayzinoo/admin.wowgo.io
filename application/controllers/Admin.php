<?php

if(!defined('BASEPATH'))
exit('No direct script acceess allowed');

class Admin extends CI_Controller
{
	function __construct()
	{
	parent::__construct();
	error_reporting(1);
	$this->load->model('Admin_model');
	$this->load->library('pagination');
	}
function index()
{
	$this->load->view('admin_login');
	
}				
/*login form*/
function admin_form()
{
	$this->load->view('admin_login', $data);
}

function chat(){
	$this->load->library('pagination');
	$config = array();
	$config["base_url"] = base_url() ."Admin/chat/";
	$config["total_rows"] = $this->db->select('chat_messages.*,users.username')->from('chat_messages')->join('users','users.id=chat_messages.user_id','left')->order_by('chat_messages.created','DESC')->get()->num_rows();
	$config["per_page"] = 50;
	$config["uri_segment"] = 3;
	$config['full_tag_open'] = '<ul class="pagegi">';
	$config['full_tag_close'] = '</ul>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li><a class="current">';
	$config['cur_tag_close'] = '</a></li>';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['prev_link'] = '<< Prev';
	$config['next_link'] = 'Next >>';  
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	$data["links"] = $this->pagination->create_links();
	$data['userdata']=$this->db->select('chat_messages.*,users.username')->from('chat_messages')->join('users','users.id=chat_messages.user_id','left')->order_by('chat_messages.created','DESC')->limit($config["per_page"], $page)->get();
	$data['content']='chat';
	$this->load->view("template",$data);
}

function chat_delete($id){
	$this->db->where('id',$id);
	$this->db->delete('chat_messages');
	redirect("Admin/chat","refresh");

}
function admin_login()
{
	ob_start();		

	$username=$this->input->post("username");
	$password=$this->input->post("password");
	$this->db->select('*');
	$this->db->from('admin');
	$this->db->where(array('username'=>$username,'password'=>$password));
	$query=$this->db->get();

	if($query->num_rows()==1)
	{

		$user=$query->row();
		$userdata=array('username'=>$user->username,'password'=>$user->password);
		$this->session->set_userdata($userdata);

		redirect("Admin/users/","refresh");
	}
	else
	{
		$this->load->view('admin_login',$data);
		?> <script>
		arert("User name and password do not match")
		</script><?php
	}
}
function userpwd_change(){
	$id=$this->uri->segment(3);
	echo $id;
	$data['userdata']=$this->db->get_where("users",array("id"=>$id))->row();
	$data['content']='userpwd_changeform';
	$this->load->view("template",$data);
}
function userpwd_update()
{
	$id=$this->input->post("id");
	$username=$this->input->post("username");
	$pwd=$this->input->post("newpwd");
	
	$data=array(
		"password"=>sha1($pwd)
		);
	$this->db->where("id",$id);
	$this->db->update("users",$data);
	
$userdata=array(
        
        "userid"=>$id
        );
        $this->session->set_userdata($userdata);
	
	redirect('Admin/user_detail/'.$this->session->userdata("userid"));
}
function history_time(){
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{

	//$data['historytime']=$this->db->query("SELECT user_logs.*,users.username as name from user_logs LEFT JOIN users ON user_logs.user_id=users.id order by id desc");
	$data['historytime']=$this->Admin_model->loginhistory("user_logs");
	$data['content']='histroy-time';
	$this->load->view("template",$data);
	}
	else{
		
	$this->load->view('admin_login');
	}
}
function editwow()
{
    $id = $this->uri->segment(3);
	$data['userdata']=$this->db->query("SELECT * from users WHERE id=$id ")->row();
	$data['content']='wowinfo';
	$this->load->view('template',$data);
}	
function update_requestwow()
{
	$id=$this->input->post("id");
	$userid=$this->input->post("userid");
	$wowamt=$this->input->post("wow_amt");
	$bal=$this->input->post("bal");
	$total=$bal+$wowamt;
	$data=array(
		"balance"=>$total
	);

	$this->db->where("id",$userid);
	$this->db->update("users",$data);
	$sta=array(
		"status"=>3,
		"update_wow"=>$wowamt,
		"amount"=>$wowamt
		);
	$this->db->where("id",$id);
	$this->db->update("fundings",$sta);
	redirect("Admin/deposit_request");
}
function profit_losss(){
	$this->load->library('pagination');
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{
	//$data['profitdata']=$this->db->query("SELECT users.*,plays.bet as bet,plays.cash_out as cash_out,users.username as name,plays.created as playdate FROM plays LEFT JOIN users ON plays.user_id=users.id order by DATE(plays.created) desc");
	$data['profitdata']=$this->Admin_model->profitloss("plays");
	$data['netprofit']=$this->db->select("coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as netprofit")
				->get("plays");
	

	$data['content']='profit-loss';
	$this->load->view("template",$data);
	}
	else{
		
	$this->load->view('admin_login');
	}
}
function bankinfo()
{
	$data['bankinfolist']=$this->db->get('bankinfor');
	
	$data['content']='bankinfo_create';
	$this->load->view('template',$data);
}
function bankinfo_insert()
{
	$bankname=$this->input->post("bankname");
	$ownername=$this->input->post("name");
	$acctno=$this->input->post("acctno");

	$data=array(
		"acctowner"=>$ownername,
		"acctno"=>$acctno,
		"bankname"=>$bankname
	);
	$this->db->insert("bankinfor",$data);
	redirect("Admin/bankinfo");
}
function bankinfo_editform()
{
	$id=$this->uri->segment(3);
	
	$data['bankinfo']=$this->db->get_where("bankinfor",array("id"=>$id))->row();
	$data['content']='bankinfo_edit';
	$this->load->view("template",$data);
}
function bankinfo_update()
{
	$id=$this->input->post("id");
	$bankname=$this->input->post("bankname");
	$ownername=$this->input->post("name");
	$acctno=$this->input->post("acctno");
	$currency_type=$this->input->post("currency_type");
	$data=array(
		"acctowner"=>$ownername,
		"acctno"=>$acctno,
		"bankname"=>$bankname,
		"currency_type"=>$currency_type
		);
	$this->db->where("id",$id);
	$this->db->update("bankinfor",$data);
	redirect("Admin/bankinfo");
}
function transaction(){
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{
	$data['transaction']=$this->db->query("select plays.*,game_hashes.* from plays LEFT JOIN game_hashes ON plays.game_id=game_hashes.game_id order by created desc");
	//$data['transactiondata']=$this->db->select("users.username as name,fundings.*")
	//				->join("users","users.id=fundings.user_id","left")
	//				->get("fundings");
	$data['transactiondata']=$this->Admin_model->alltransaction("fundings");
	$data['content']='transaction';
	$this->load->view("template",$data);
	}
	else{
		
	$this->load->view('admin_login');
	}
}
function deposit_request()
{
	$data['depositrequest']=$this->db->query("SELECT fundings.*,fundings.amount as amt,fundings.status as status,users.balance as bal,users.username as username FROM fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is NULL ORDER BY fundings.id DESC");
//	$data['depositrequest']=$this->Admin_model->depositrequest("fundings");
	$data['content']='depositrequest_list';
	$this->load->view("template",$data);
}
function depositrequest_confirm()
{
	$id=$this->input->post("id");
	$userid=$this->input->post("userid");
	$wow=$this->input->post("wow");
	$bal=$this->input->post("bal");
	$total=$wow+$bal;
		
	$data=array(
		"balance"=>$total
		);
	$this->db->where("id",$userid);
	$this->db->update("users",$data);
	$fun=array(
		"status"=>1
		);
	$this->db->where("id",$id);
	$this->db->update("fundings",$fun);
	echo $total;	
}
function depositrequest_cancel()
{
	$id=$this->input->post("id");
	$userid=$this->input->post("userid");
	$wow=$this->input->post("wow");
	$bal=$this->input->post("bal");
	
	$data=array(
		"status"=>2
		);
	$this->db->where("id",$id);
	$this->db->update("fundings",$data);
	
}
function withdrawal_request()
{
//	$data['withdrawalrequest']=$this->db->query("SELECT fundings.*,fundings.amount as requestamt,users.balance as bal,users.username as username FROM fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is not NULL ORDER BY id DESC");
	$data['withdrawalrequest']=$this->Admin_model->withdrawrequest("fundings");
	$data['content']='withdrawalrequest_list';
	$this->load->view("template",$data);
}
function withdrawalinfo()
{
	$id=$this->input->post("id");
	$query=$this->db->query("SELECT * from fundings WHERE id=$id ")->row_array();
	$result=json_encode($query);

	echo $result;
}
function depositinfo()
{
	$id=$this->input->post("id");
	$query=$this->db->query("SELECT * FROM fundings where id=$id")->row_array();
	$result=json_encode($query);
	echo $result;
}
function ctcinfo()
{
	$id=$this->input->post("id");
	$query=$this->db->query("Select * from fundings where id=$id")->row_array();
	$result=json_encode($query);
	echo $result;
}

function withdrawalrequest_confirm()
{
	$id=$this->input->post("id");
	$data=array(
		"status"=>1
		);
	$this->db->where("id",$id);
	$this->db->update("fundings",$data);
}
function withdrawalrequest_cancel()
{
	$id=$this->input->post("id");
	$userid=$this->input->post("userid");
	$wow=$this->input->post("wow");
	$bal=$this->input->post("bal");
	$total=$wow+$bal;
	$data=array(
		"balance"=>$total
		);
	
	$this->db->where("id",$userid);
	$this->db->update("users",$data);
	$sta=array(
		"status"=>2
		);
	$this->db->where("id",$id);
	$this->db->update("fundings",$sta);
}
function statistics()
{
	$data["deposit"]=$this->db->query("SELECT sum(amount) as totaldeposit from fundings where withdrawal_id is NULL AND (status='1' OR status='3')")->row();
	$data["withdraw"]=$this->db->query("SELECT sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL AND status='1'")->row();	
	$data["balance"]=$this->db->query("SELECT count(id) as totalbalance from users")->row();
	$data["totalwow"]=$this->db->query("SELECT sum(balance) as totalwow from users")->row();
	$data["netprofit"]=$this->db->query("SELECT coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays")->row();
	$data['content']='statistics';
	$this->load->view("template",$data);
}

function tipsend()
{
//	$data['tip']=$this->db->query("SELECT tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient FROM tips  JOIN users AS senders on senders.id=tips.from_user_id JOIN users AS recipients on recipients.id=tips.to_user_id order by created desc");
	$data['tip']=$this->Admin_model->tipsend("tips");
	$data['content']='tipsend';

	$this->load->view("template",$data);
}
function statistics_search()
{
	$startdate=$this->input->post("startdate");
	$enddate=$this->input->post("enddate");
	
	if(empty($startdate) && empty($enddate)){
		$deposit=$this->db->query("select sum(amount) as totaldeposit from fundings where withdrawal_id is NUll AND (status='1' OR status='3')")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL AND status='1'")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays")->row();

		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		
		echo json_encode($data);
	}
	elseif(!empty($startdate) && empty($enddate))
	{
		$deposit=$this->db->query("SELECT sum(amount) as totaldeposit FROM fundings where withdrawal_id is NULL and DATE(created)='$startdate' AND (status='1' OR status='3')")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not null and DATE(created)='$startdate' AND status='1'")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where DATE(created)='$startdate'")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where DATE(created)='$startdate'")->row();
		$date['deposit']=$deposit->totaldeposit;
$data['withdraw']=$withdraw->totalwithdraw;
$data['balance']=$balance->totalbalance;
$data['wow']=$totalwow->totalwow;
$data['netprofit']=$netprofit->totalnetprofit;
		echo json_encode($data);
		
	}
	elseif(empty($startdate) && !empty($enddate))
	{
		$deposit=$this->db->query("SELECT sum(amount) as totaldeposit FROM fundings WHERE withdrawal_id is NULL and DATE(created)='$enddate' AND (status='1' OR status='3')")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not null and DATE(created)='$enddate' AND status='1'")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where DATE(created)='$enddate'")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where DATE(created)='$enddate'")->row();
		$data['deposit']=$deposit->totaldeposit;
$data['withdraw']=$withdraw->totalwithdraw;
$data['balance']=$balance->totalbalance;
$data['wow']=$totalwow->totalwow;
$data['netprofit']=$netprofit->totalnetprofit;
		echo json_encode($data);
		
		
	}
	elseif(!empty($startdate) && !empty($enddate))
	{
		$deposit=$this->db->query("SELECT sum(amount) as totaldeposit FROM fundings WHERE withdrawal_id is NULL and (status='1' OR status='3') and DATE(created) BETWEEN '$startdate' AND '$enddate'")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not null and status='1' and DATE(created) BETWEEN '$startdate' AND '$enddate' ")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where DATE(created) BETWEEN '$startdate' AND '$enddate'")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where DATE(created) BETWEEN '$startdate' AND '$enddate'")->row();
		$data['deposit']=$deposit->totaldeposit;
$data['withdraw']=$withdraw->totalwithdraw;
$data['balance']=$balance->totalbalance;
$data['wow']=$totalwow->totalwow;
$data['netprofit']=$netprofit->totalnetprofit;
		echo json_encode($data);
		
	}
}

	function search24()

	
	{
		
		$deposit=$this->db->query("SELECT sum(amount) as totaldeposit FROM fundings WHERE withdrawal_id is NULL and DATE(created)=DATE(NOW()) and (status='1' OR status='3')")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL and DATE(created)=DATE(NOW()) and status='1'")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where DATE(created)=DATE(NOW())")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where DATE(created)=DATE(NOW())")->row();
		
		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		$data['status']="24 Hours";
		echo json_encode($data);
	
	}
	function search3d()
	{
		$deposit=$this->db->query("select count(amount) as totaldeposit from fundings where withdrawal_id is NUll and (status='1' OR status='3') and created > current_date - interval '3' day")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL and status='1' and created > current_date - interval '3' day")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where created > current_date - interval '3' day")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where created > current_date - interval '3' day")->row();

		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		$data['status']="3 Days";
		echo json_encode($data);
	}
	function search1w()
	{
		$deposit=$this->db->query("select sum(amount) as totaldeposit from fundings where withdrawal_id is NUll and (status='1' OR status='3') and created > current_date - interval '7' day")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL and status='1' and created > current_date - interval '7' day")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where created > current_date - interval '7' day")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where created > current_date - interval '7' day")->row();

		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		
		echo json_encode($data);	
	}
	function search1m()
	{
		
		$deposit=$this->db->query("select sum(amount) as totaldeposit from fundings where withdrawal_id is NUll and (status='1' OR status='3') and created > current_date - interval '1' month")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL and status='1' and created > current_date - interval '1' month")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where created > current_date - interval '1' month")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where created > current_date - interval '1' month")->row();

		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		
		echo json_encode($data);	
	}
	function search3m()
	{
		$deposit=$this->db->query("select sum(amount) as totaldeposit from fundings where withdrawal_id is NUll and (status='1' OR status='3') and created > current_date - interval '3' month")->row();
		$withdraw=$this->db->query("select sum(amount) as totalwithdraw from fundings where withdrawal_id is not NULL and status='1' and created > current_date - interval '3' month")->row();
		$balance=$this->db->query("select count(id) as totalbalance from users")->row();
		$totalwow=$this->db->query("select sum(balance) as totalwow from users where created > current_date - interval '3' month")->row();
		$netprofit=$this->db->query("select coalesce(sum(bet),0)-coalesce(sum(cash_out),0) as totalnetprofit from plays where created > current_date - interval '3' month")->row();

		$data['deposit']=$deposit->totaldeposit;
		$data['withdraw']=$withdraw->totalwithdraw;
		$data['balance']=$balance->totalbalance;
		$data['wow']=$totalwow->totalwow;
		$data['netprofit']=$netprofit->totalnetprofit;
		
		echo json_encode($data);
	}
function getreferral(){
	$page = $this->input->post("page");
$id=$this->input->post("userid");
$offset= 10*$page; $limit=10;
$this->db->select("referral.*");
$this->db->where("referred_by",$id);
$this->db->limit($limit);
$rr=$this->db->get("referral");
foreach($rr->result() as $r){
	echo "
		<tr class='a41'>
			<td>".$r->user_id."</td>
			<td>".$r->totalamtearn."</td>
			<td>".$r->created_on."</td>
		
		</tr>
		";}
	exit;
}
function getdailyprofit(){
	$page = $this->input->post("page");
$id=$this->input->post("userid");
$offset= 30*$page; $limit=30;
$this->db->select("coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as netprofit,DATE(created) as mydate");
$this->db->where("user_id",$id);
$this->db->group_by("DATE(created)");
$this->db->limit($limit);
$this->db->order_by("DATE(created)", "desc");
$dd=$this->db->get("plays");
foreach($dd->result() as $dp){
	echo "
		<tr>
			<td>".$dp->mydate."</td>
			<td>".$dp->netprofit."</td>
			
		
		</tr>
		";}
	exit;
}
function getloginhistory(){
	 $page =  $this->input->post("page");
	$id=$this->input->post("userid");
        
	$offset = 30*$page; $limit = 30;
       			
$this->db->select("user_logs.*,users.username as name");
$this->db->join("users","users.id=user_logs.user_id");
$this->db->where("user_id",$id);
$this->db->limit($limit);
$this->db->order_by("id","desc");
$hist=$this->db->get("user_logs");

        foreach($hist->result() as $lg){
            echo "
			<tr>
			<td>".$lg->name."</td>
			<td>".$lg->login_time."</td>
			<td>".$lg->ip."</td>
			<td>".$lg->location."</td>

			</tr>
			";}
			        exit;
}
function gettransaction(){
$page = $this->input->post("page");
$id=$this->input->post("userid");
$offset= 30*$page; $limit=30;
$this->db->select("users.username as name,fundings.*,fundings.created as fundate");
$this->db->join("users","fundings.user_id=users.id");
$this->db->where("fundings.user_id",$id);
$this->db->limit($limit);
$this->db->order_by("fundings.created","desc");
$trns=$this->db->get("fundings");
foreach($trns->result() as $tran){
	echo "
		<tr>
			
			<td>".(empty($tran->withdrawal_id) ? "$tran->ethereum_deposit_txid" : "$tran->withdrawal_id")."</td>
			<td>".(empty($tran->withdrawal_id) ? "Deposit":"Withdrawal")."</td>
			<td>".(($tran->status=='2') ? "<p style='color:#f90606'>Cancelled</p>" : (($tran->status=='1') ? "<p style='color:green'>Success</p>" : "<p>In Progress</p>"))."</td>
			<td>".$tran->amount."</td>
			<td>".$tran->fundate."</td>
		
		</tr>
		";}
	exit;
}
function getprofitloss(){
$page = $this->input->post("page");
$id=$this->input->post("userid");
$offset= 30*$page; $limit=30;
$this->db->select("plays.bet as bet,plays.cash_out as cash_out,users.username as name");
$this->db->join("users","plays.user_id=users.id");
$this->db->where("plays.user_id",$id);
$this->db->limit($limit);
$this->db->order_by("plays.id","desc");
$pp=$this->db->get("plays");
foreach($pp->result() as $profit){
	echo "
		<tr>
			<td>".$profit->name."</td>
			<td>".$profit->bet."</td>
			<td>".(empty($profit->cash_out) ? "Loss" : "Profit")."</td>
			<td>".(empty($profit->cash_out) ? "$profit->bet":"$profit->cash_out")."</td>
		
		</tr>
		";}
	exit;
}
public function gettips(){
        $page =  $this->input->post("page");
	$id=$this->input->post("userid");
        
	$offset = 30*$page; $limit = 30;
       			
$this->db->select("tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient");
$this->db->join("users as senders","senders.id=tips.from_user_id");
$this->db->join("users as recipients","recipients.id=tips.to_user_id");
$this->db->where("tips.from_user_id",$id);
$this->db->or_where("tips.to_user_id = $id");
$this->db->limit($limit);
$this->db->order_by("created","desc");
$tt=$this->db->get("tips");

        foreach($tt->result() as $tip){
            echo "
			<tr>

			<td>".$tip->sender."</td>

			<td>".$tip->recipient."</td>

			<td>".$tip->amount."</td>
			<td>".$tip->commission."</td>
			<td>".$tip->tip_tx_id."</td>
			<td>".$tip->created."</td>

			</tr>
			";}
			        exit;
		}
function user_detail(){
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{
	$id=$this->uri->segment(3);

	$data['tip']=$this->db->query("SELECT tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient FROM tips  JOIN users AS senders on senders.id=tips.from_user_id JOIN users AS recipients on recipients.id=tips.to_user_id where tips.from_user_id = $id OR
		tips.to_user_id = $id order by created desc Limit 30" );

	$data['userdata']=$this->db->select("users.*,users.rate as rate,users.id as userid,users.status as user_status,fundings.*")
	->join("fundings","fundings.user_id=users.id","left")
	->get_where("users",array("users.id"=>$id))->row();
	$data['deposit']=$this->db->query("SELECT SUM(amount) AS deposit FROM fundings WHERE user_id=$id AND withdrawal_id is NULL")->row();
				
	$data['withdraw']=$this->db->query("select sum(amount) as withdraw from fundings where user_id=$id AND withdrawal_id is not NULL")->row();
	$data['netprofit']=$this->db->select('coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as netprofit')
				->get_where("plays",array("user_id"=>$id))->row();
	$data['netprofitdata']=$this->db->select("coalesce(sum(cash_out),0)+coalesce(sum(bonus),0)-coalesce(sum(bet),0) as netprofit,DATE(created) as mydate")
				->group_by('Date(created)')				
				->order_by('DATE(created)','desc')			
				->Limit(30)->get_where("plays",array("user_id"=>$id));
//	$data['dailynetprofit']=$this->db->query("Select coalesce(cash_out,0)+ coalesce(bonus,0)-coalesce(bet,0) as netprofit,DATE(created) as mydate From plays Where user_id=$id Group By created");
	$data['userfunding']=$this->db->query("select * from fundings where user_id=$id")->row();
	$data['profitdata']=$this->db->query("SELECT plays.bet as bet,plays.cash_out as cash_out,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE plays.user_id=$id order by plays.id desc Limit 30");
	$data['userlog']=$this->db->query("SELECT user_logs.*,users.username as name from user_logs LEFT JOIN users ON user_logs.user_id=users.id WHERE user_id=$id order by id desc Limit 30");

	$data['transaction']=$this->db->select("users.username as name,fundings.*,fundings.created as fundate")
					->join("users","users.id=fundings.user_id","left")
					->order_by("fundings.created","desc")
					->Limit(30)->get_where("fundings",array("user_id"=>$id));	

	$data['totalprofit']=$this->db->select("sum(amount) as totalamtearn")
				->get_where("referral_earnings",array("user_id_benefit"=>$id))->row();
	$data['totalwithdraw']=$this->db->select("sum(amount_transferred) as totalreferralwithdraw")
					->get_where("referral_withdrawls",array("user_id"=>$id))->row();
	$data['referral']=$this->db->query("SELECT count(referred_by) as totalrefer FROM referral WHERE referred_by=$id")->row();

	$data['referralaffili']=$this->db->select("referral.*")
//				->join("users","users.username=referral.user_id")				
//				->join("referral_earnings","referral_earnings.user_id_benefit=referral.referred_by")	
//				->group_by("referral_earnings.user_id_benefit")				
			
				->limit(10)->get_where("referral",array("referred_by"=>$id));
//	$data['referralaffili']=$this->db->query("SELECT earn.totalamtearn FROM referral refer LEFT JOIN (SELECT users.id as userid FROM users) user ON user.username=refer.user_id LEFT JOIN (SELECT user_id ,Sum(amount) totalamtearn FROM referral_earnings WHERE user_id_benefit=$id GROUP BY user_id) earn ON user.userid=earn.user_id WHERE refer.referred_by=$id");
//	$data['refer_profit']=$this->db->query("Select sum(amount) as totalamtearn From referral_earnings Group By user_id Where user_id_benefit=$id")->row();
	$data['content']='user-detail';
	$this->load->view("template",$data);
	}
	else{		
	$this->load->view('admin_login');
	}
}
function getnetdeposit()
{
	$result["progit"]=$this->db->query("SELECT student_id,student_name,marks FROM tbl_marks ORDER BY student_id");
	$data = array();
	foreach ($result->result() as $list) {
		$item = array();
		  $item[0] = $list->no_account;
		  $item[1] = $list->student_name;
		  $item[2] = $list->marks;
		  $data[] = $item;
	}
	$json = json_encode($data); 
echo $json;
}
function users(){
	if($this->session->userdata("username") && ($this->session->userdata("password")))
	{
	//$data['user']=$this->db->select("users.*,users.id as userid")
	//			->group_by("users.id")
	//			->get("users");
	$data['user']=$this->Admin_model->alluser("users");
	$data['content']='usermanagement';

	$this->load->view('template',$data);
	}
	else{
		
		$this->load->view('admin_login');
	}
}
function transaction_search()
{
	
	if($this->input->post('submit')==true)
	{
		
		$type=$this->input->post("type");

		$data=array(
			'type'=>$type
		);
		$this->session->set_userdata($data);
	}
	else{
	
		$type=$this->session->userdata("type");
	}
if($this->uri->segment(3))
        {
            $start=$this->uri->segment(3);
        }
        else
        {
            $start=0;
        }
	$config["base_url"]=base_url()."Admin/transaction_search/"; 
if($type=="deposit"){

		$row=$this->db->query("SELECT fundings.*,users.username as name FROM fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is NULL");
		
	}
	elseif($type=="withdrawal"){
		$row=$this->db->query("SELECT fundings.*,users.username as name from fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is not NULL");
	}	
$config['total_rows'] =$row->num_rows(); 
$config['per_page'] = 30;
$config['uri_segment'] = 3;
$config['num_links'] = 5;
$config['full_tag_open'] = '<ul class="pagegi">';
$config['full_tag_close'] = '</ul>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li><a class="current">';
$config['cur_tag_close'] = '</a></li>';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
$config['prev_link'] = '<< Prev';
$config['next_link'] = 'Next >>';  
$this->pagination->initialize($config); 

$perpg=$config['per_page'];	
	if($type=="deposit"){

		$query=$this->db->query("SELECT fundings.*,users.username as name FROM fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is NULL Order by fundings.id DESC");
		
	}
	elseif($type=="withdrawal"){
		$query=$this->db->query("SELECT fundings.*,users.username as name from fundings LEFT JOIN users ON users.id=fundings.user_id WHERE withdrawal_id is not NULL");
	}	
	
	if($query->num_rows()>=1)
	{
	$data["message"]="";
	$data["lists"]=$query;		

	$data["content"]="transaction-search";
	$this->load->view("template",$data);
	}
	else{		

	$data["message"]="No Data Found!";
	$data["content"]="nodata";
	$this->load->view("template",$data);
	}
}
function user_search(){
	if($this->input->post('submit')==true)
	{			
	$type=$this->input->post("type");
	$username=$this->input->post("username");

	$userdata=array(				
	'type'=>$type,
	'username'=>$username
	);
	$this->session->set_userdata($userdata);
	}
	else{			
	$type=$this->session->userdata("type");
	$username=$this->session->userdata("username");
	}

$config["base_url"]=base_url()."Admin/user_search/"; 
if(($type=="deposit") && empty($username))
	{

	$row=$this->db->query("SELECT users.*,users.username as name,users.id as userid,fundings.* FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE ethereum_deposit_txid!=''");
			
	}
	elseif(($type=="withdrawal") && empty($username))
	{
	$row=$this->db->query("SELECT users.*,users.username as name,users.id as userid,fundings.* FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE withdrawal_id is not NULL");
	
	}
	elseif(empty($type) && !empty($username))
	{
	$row=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE username LIKE '%$username%'");
			
	}
	elseif($type=="deposit" && !empty($username))
	{
	$row=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE ethereum_deposit_txid!='' AND username LIKE '%$username%'");
	
	}
	elseif($type=="withdrawal" && !empty($username))
	{
	$row=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE username LIKE '%username%' AND withdrawal_id is not NULL");
	
	}
	elseif(empty($type) && empty($username))
	{
	$row=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id");
	
	}
$config['total_rows'] =$row->num_rows(); 
$config['per_page'] = 10;
$config['uri_segment'] = 3;
$config['num_links'] = 5;
$config['full_tag_open'] = '<ul class="pagegi">';
$config['full_tag_close'] = '</ul>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li><a class="current">';
$config['cur_tag_close'] = '</a></li>';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
$config['prev_link'] = '<< Prev';
$config['next_link'] = 'Next >>';  
$this->pagination->initialize($config); 
$perpg=$config['per_page'];

	if(($type=="deposit") && empty($username))
	{

	$query=$this->db->query("SELECT users.*,users.username as name,users.id as userid,fundings.* FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE ethereum_deposit_txid!=''");
	$data["userlist"]=$query;			
	}
	elseif(($type=="withdrawal") && empty($username))
	{
	$query=$this->db->query("SELECT users.*,users.username as name,users.id as userid,fundings.* FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE withdrawal_id is not NULL");
	$data["userlist"]=$query;
	}
	elseif(empty($type) && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE users.username LIKE '%$username%'");
	$data["userlist"]=$query;			
	}
	elseif($type=="deposit" && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE ethereum_deposit_txid!='' AND username LIKE '%$username%'");
	$data["userlist"]=$query;
	}
	elseif($type=="withdrawal" && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id WHERE username LIKE '%username%' AND withdrawal_id is not NULL");
	$data["userlist"]=$query;
	}
	elseif(empty($type) && empty($username))
	{
	$query=$this->db->query("SELECT users.*,fundings.*,users.username as name,users.id as userid FROM users LEFT JOIN fundings ON fundings.user_id=users.id");
	$data["userlist"]=$query;
	}

	if($query->num_rows()>=1)
	{
	$data["message"]="";
	$data["lists"]=$query;		

	$data["content"]="user_list_search";
	$this->load->view("template",$data);
	}
	else{		

	$data["message"]="No Data Found!";
	$data["content"]="nodata";
	$this->load->view("template",$data);
	}
}
function profitloss_search(){
	if($this->input->post('submit')==true)
	{

	$type=$this->input->post("type");
	$username=$this->input->post("username");

	$userdata=array(		
	'type'=>$type,
	'username'=>$username
	);
	$this->session->set_userdata($userdata);
	}
	else{			
	$type=$this->session->userdata("type");
	$username=$this->session->userdata("username");
	}

if($this->uri->segment(3))
        {
            $start=$this->uri->segment(3);
        }
        else
        {
            $start=0;
        }
	$config["base_url"]=base_url()."Admin/profitloss_search/";
	if($type=="profit" && empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id where cash_out is not NULL");
}
elseif($type=="loss" && empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id where cash_out is NULL");
}
elseif(empty($type) && !empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id where username LIKE '%$username%'");
}
elseif($type=="profit" && !empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id where cash_out is not NULL and username LIKE '%$username%'");
}
elseif($type=="loss" && !empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id where cash_out is NULL and username LIKE '%$username%'");
}
elseif(empty($type) && empty($username))
{
	$row=$this->db->query("select users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name from plays LEFT JOIN users ON plays.user_id=users.id");
}

$config['total_rows'] =$row->num_rows(); 
$config['per_page'] = 50;
$config['uri_segment'] = 3;
$config['num_links'] = 5;
$config['full_tag_open'] = '<ul class="pagegi">';
$config['full_tag_close'] = '</ul>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li><a class="current">';
$config['cur_tag_close'] = '</a></li>';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
$config['prev_link'] = '<< Prev';
$config['next_link'] = 'Next >>';  
$perpg=$config['per_page'];
$this->pagination->initialize($config); 

	if($type=="profit" && empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE cash_out is not NULL ORDER BY plays.id DESC");
	$data["userlist"]=$query;			
	}
	elseif($type=="loss" && empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE cash_out is NULL ORDER BY plays.id DESC");
	$data["userlist"]=$query;
	}
	elseif(empty($type) && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE username LIKE '%$username%' ORDER BY plays.id DESC");
	$data["userlist"]=$query;			
	}
	elseif($type=="profit" && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE cash_out is not NULL AND username LIKE '%$username%' ORDER BY plays.id DESC");
	$data["userlist"]=$query;
	}
	elseif($type=="loss" && !empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id WHERE cash_out is NULL AND username LIKE '%username%' ORDER BY plays.id DESC");
	$data["userlist"]=$query;
	}
	elseif(empty($type) && empty($username))
	{
	$query=$this->db->query("SELECT users.*,plays.cash_out as cash_out,plays.bet as bet,users.username as name FROM plays LEFT JOIN users ON plays.user_id=users.id ORDER BY plays.id DESC");
	$data["userlist"]=$query;
	}
	// echo $this->db->last_query();exit;
	if($query->num_rows()>=1)
	{
	$data["message"]="";
	$data["lists"]=$query;		

	$data["content"]="profit_loss_search";
	$this->load->view("template",$data);
	}
	else{		

	$data["message"]="No Data Found!";
	$data["content"]="nodata";
	$this->load->view("template",$data);
	}
}

function update_action(){

	$this->form_validation->set_rules('id', '<b>id</b>', 'trim|required');
	$this->form_validation->set_rules('action', '<b>action</b>', 'trim|required');
	// $id=$this->input->post('id');
	// $action=$this->input->post("action");	

	
	$arr2=array(
		"status"=>$this->input->post("action")
	);
	$this->db->where('id',$this->input->post('id'));
	$this->db->update("users",$arr2);
		// $this->Admin_model->update_user_data($arr, $this->input->post('id'));
		$useraction=$this->db->get_where("users",array("id"=>$id))->row();
		$arr = array();
		$arr["name"]=$useraction->name;
		$arr["address"]=$useraction->address;
		$arr["type"]=$useraction->type;
		$arr["action"]=$this->input->post("action");
		$arr["referral_action"]=$useraction->referral_action;
		$arr["referral_fee"]=$useraction->referral_fee;
		$arr['success'] = true;
		// $arr["name"]="ppa";
		//  $arr["address"]="No44";
		//  $arr["type"]="M";
		//  $arr["action"]="BB";
		//  $arr["referral_action"]="Yes";
		//  $arr["referral_fee"]="0.1";
		//  $arr['success'] = true;
	//echo $arr[0];		
	//print_r($arr[0]); exit();
	
	
	echo json_encode($arr);
	// redirect('Admin/users');

}
function referral_system()
{
	$this->form_validation->set_rules('id', '<b>id</b>', 'trim|required');
	$this->form_validation->set_rules('referralfee', '<b>referralfee</b>', 'trim|required');

	$arr2=array(
		"referral"=>'yes',
		"rate"=>$this->input->post('referralfee')
	);
	$this->db->where('id',$this->input->post('id'));
	$this->db->update("users",$arr2);
	$arr=array();
	$arr["referral_action"]="yes";	
	$arr["referralfee"]=$this->input->post("referralfee");	
	$arr['success'] = true;
	echo json_encode($arr);
}
function admin_profile(){
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{
	$data["admindata"]=$this->db->get("admin")->row();

	$data["content"]="admin_profile";
	$this->load->view("template",$data);
	}
	else{
			
		$this->load->view('admin_login');
	}
}
function setting(){
	if($this->session->userdata("username") && $this->session->userdata("password"))
	{
	$data["admindata"]=$this->db->get("admin");
	$data["content"]="setting";
	$this->load->view("template",$data);
	}
	else{
			
		$this->load->view('admin_login');
	}
}
function update_setting()
{
	// $id=$this->session->userdata('id');
    $username=$this->input->post('username');
	$password=$this->input->post('password');
	$data=array(
	    "username" =>$username,
	    "password" =>$password);
	// $this->db->where('id',$id);
	$this->db->update("admin",$data);
	session_destroy();
	redirect('Admin/admin_login',"refresh");
}
/*logout*/
function logout()
{
	session_destroy();
	redirect('Admin/admin_login',"refresh");
}
/**/
}
?>
