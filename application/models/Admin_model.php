<?php
/**
*
*/

class Admin_model extends CI_Model
{
	public function __construct()
	{

	}

	function update_user_data($arr, $userid)
	{
		$this->db->where('id', $id);
		$this->db->update('users', $arr);
	}
	public function gettips($page)
	{ 
		$offset = 30*$page; $limit = 30; 
		$sql = "SELECT tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient FROM tips  JOIN users AS senders on senders.id=tips.from_user_id JOIN users AS recipients on recipients.id=tips.to_user_id where tips.from_user_id = $id OR
		tips.to_user_id = $id order by created desc limit $offset ,$limit"; 
		$result = $this->db->query($sql)->result();

        return $result;

        }
	function alluser()
	{
	    $config["base_url"]=base_url()."Admin/users/";
	$this->db->select("users.*,users.id as userid");
	    $config['total_rows'] = $this->db->get("users")->num_rows();
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
	$this->db->select("users.*,users.id as userid");
	    $this->db->group_by("id");
	    $this->db->order_by('id','desc');
	    $query=$this->db->get('users',$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function alltransaction()
	{
	    $config["base_url"]=base_url()."Admin/transaction/";
	    $this->db->select("users.username as name,fundings.*");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $config['total_rows'] = $this->db->get("fundings")->num_rows();
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
	    $this->db->select("users.username as name,fundings.*");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $this->db->order_by('fundings.created','desc');
	    $query=$this->db->get('fundings',$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function loginhistory()
	{
	    $config["base_url"]=base_url()."Admin/history_time/";
	    $this->db->select("users.username as name,user_logs.*");
	    $this->db->join("users","users.id=user_logs.user_id","left");
	    $config['total_rows'] = $this->db->get("user_logs")->num_rows();
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

	    $this->pagination->initialize($config);
	    $this->db->select("users.username as name,user_logs.*");
	    $this->db->join("users","users.id=user_logs.user_id","left");
	    $this->db->order_by('user_logs.id','desc');
	    $query=$this->db->get('user_logs',$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function profitloss()
	{
	    $config["base_url"]=base_url()."Admin/profit_losss/";
	    $this->db->select("users.*,plays.bet as bet,plays.cash_out as cash_out,users.username as name,plays.created as playdate");
	    $this->db->join("users","users.id=plays.user_id","left");
	    $config['total_rows'] = $this->db->get("plays")->num_rows();
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

	    $this->pagination->initialize($config);
	    $this->db->select("users.*,plays.bet as bet,plays.cash_out as cash_out,users.username as name,plays.created as playdate");
	    $this->db->join("users","users.id=plays.user_id","left");
	    $this->db->order_by('plays.created','desc');
	    $query=$this->db->get('plays',$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function depositrequest()
	{
	    $config["base_url"]=base_url()."Admin/deposit_request/";
	    $this->db->select("fundings.*,fundings.amount as amt,fundings.status status,users.balance as bal,users.username as username");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $config['total_rows'] = $this->db->get_where("fundings",array("withdrawal_id"=>NULL))->num_rows();
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

	    $this->pagination->initialize($config);
	    $this->db->select("fundings.*,fundings.amount as amt,fundings.status status,users.balance as bal,users.username as username");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $this->db->order_by('fundings.id','desc');
	    $query=$this->db->get_where("fundings",array("withdrawal_id"=>NULL),$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function withdrawrequest()
	{
	    $config["base_url"]=base_url()."Admin/withdrawal_request/";
	    $this->db->select("fundings.*,fundings.amount as requestamt,users.balance as bal,users.username as username");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $config['total_rows'] = $this->db->get_where("fundings",array("withdrawal_id !="=>NULL))->num_rows();
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
	    $this->db->select("fundings.*,fundings.amount as requestamt,users.balance as bal,users.username as username");
	    $this->db->join("users","users.id=fundings.user_id","left");
	    $this->db->order_by('fundings.id','desc');
	    $query=$this->db->get_where("fundings",array("withdrawal_id !="=>NULL),$config['per_page'],$this->uri->segment(3));

	    return $query;
	}
	function tipsend()
	{
	    $config["base_url"]=base_url()."Admin/tipsend/";
	$data['tipsend']=$this->db->query("SELECT tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient FROM tips  JOIN users AS senders on senders.id=tips.from_user_id JOIN users AS recipients on recipients.id=tips.to_user_id order by created desc");	    
	    $config['total_rows'] = $data['tipsend']->num_rows();
	
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

	    $this->pagination->initialize($config);
	$this->db->select("tips.*,tips.amount as amount,tips.tip_tx_id as tip_tx_id, senders.username as sender, recipients.username as recipient");
	$this->db->join("users as senders","senders.id=tips.from_user_id");
	$this->db->join("users as recipients","recipients.id=tips.to_user_id");
	$this->db->order_by("created","desc");
	$query=$this->db->get("tips",$config['per_page'],$this->uri->segment(3));
	  return $query;
	}
}

?>
