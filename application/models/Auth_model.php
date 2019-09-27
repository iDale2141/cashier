<?php 

/**
 * 
 */
class Auth_model extends CI_Model
{
	
	public function __construct(){
		parent::__construct();
		$this->load->database();

	}

	public function create($data){
		$data = (object)$data;

		$data = array( 
					'username' 	=> $data->username,
					'password' 	=> md5($data->password),
					'role_id'	=> 4
				);

		$this->db->insert('users', $data);
		$res = $this->db->affected_rows();

		if($res){
			return true;
		}
		else{
			return false;
		}
	}

	public function select($data){
		$array = array( 'username' => $data['username'], 'password' => md5($data['password']) );
		$this->db->where($array);
		$this->db->limit(1);
		$res   = $this->db->get('users');
		$arr   = $res->result();

		if(count($arr)){
			unset($arr[0]->password);
			$data = [
				"user_data" => $arr[0],
				"sy"  => $data['sy'],
				"sem" => $data['sem']
			];
			return $data;
		}
		return false;
	}

}

?>