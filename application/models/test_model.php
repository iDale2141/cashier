

<?php

class Test_model extends CI_Model { 


	public function select() { 

		$this->load->database();

		$query = $this->db->get('accounts');


		$result = $query->result();;
		return $result;

		foreach ($result as $row)
		{
		        echo $row->username . "<br>";
		}

	}

	public function insert(){
		$this->load->database();
		$data = array( 
					'account_type' 	=> 'type1',
					'username' 	=> "John Smith",
					'password' 	=> "Secret" 
				);

		$this->db->insert('accounts', $data);
		echo $this->db->affected_rows();
	}


	public function select_where($data){
		
		// print_r($data);
		$this->load->database();

		$query = $this->db->get_where('accounts', array('username' => $data['username'], 'password' => $data['password']));		


		print_r($query->result());	
	}

	public function register_new_user($data){
		

		$this->load->database();

		$username = $this->input->post('username');
		$password = $this->input->post('password');


		$data = array(
					'account_type'  => 'Alumnus',
					'username' 		=> $username,
					'password' 		=> $password
				);

		$this->db->insert('accounts', $data);

		if($this->db->affected_rows() > 0){

			return "Successful";

		}
	}

	public function login_auth($data){
		
		$username = $data['username'];
		$password = $data['password'];

		$this->load->database();

		$query = $this->db->get_where('accounts', array('username' => $username, 'password' => $password) );
		$result = $query->result();

		if(count($result)){


			echo "<h1>Welcome " . $result[0]->username . " </h1>";

		}

		else{

			return redirect('test/login_form', 'refresh');

		}
	}

	public function delete($id){

		$this->load->database();
		
		$this->db->delete('accounts', array('account_id' => $id)); 

		return "success";

	}

	public function update($id, $new_username, $new_password){

		$this->load->database();

		$array = array(
		        'username' => $new_username,
		        'password' => $new_password
		);

		$this->db->set($array);
		$this->db->where('account_id', $id);
		$this->db->update('accounts');

		return "success";
	}

} 

?>
