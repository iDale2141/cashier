<?php

/**
 * 
 */
class Others extends CI_Controller
{
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('Others_model');
	}

	public function other_payees(){
		$term = $this->input->get('term');
		$list = $this->Others_model->other_payees($term);
		echo json_encode($list);
	}

	public function add_payee(){
		$data = $this->input->post();
		$add = $this->Others_model->add_payee($data);
		echo json_encode($add);
	}

	public function update_payee(){
		$data = $this->input->post();
		$update = $this->Others_model->update_payee($data);
		echo json_encode($update);
	}

	public function delete_payee(){
		$id = $this->input->post('id');
		$delete = $this->Others_model->delete_payee($id);
		echo json_encode($delete);
	}

	public function add_particular(){
		$data = (object)$this->input->post();
		$add = $this->Others_model->add_particular($data);
		echo json_encode($add);
	}

	public function get_other_particulars(){
		$key = $this->input->get('key');
		$get = $this->Others_model->other_particulars($key);
		echo json_encode($get);
	}

	public function test(){
		$this->Others_model->test();
	}
}